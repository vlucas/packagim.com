<?php
// Setup defaults...
date_default_timezone_set('UTC');
error_reporting(-1); // Display ALL errors
ini_set('display_errors', '1');
ini_set("session.cookie_httponly", '1'); // Mitigate XSS javascript cookie attacks for browers that support it
ini_set("session.use_only_cookies", '1'); // Don't allow session_id in URLs

// Production setting switch
if(BULLET_ENV == 'production') {
    // Hide errors in production
    // error_reporting(0);
    // ini_set('display_errors', '0');

    // Force HTTPS only
    /*
    if(!$request->isCli()) {
        if ($request->isSecure()) {
            header('Strict-Transport-Security: max-age=31536000');
        } elseif (!$request->isSecure()) {
            header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], true, 301);
            die();
        }
    }
   */
}

// Throw Exceptions for everything so we can see the errors
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    // Don't catch suppressed errors with '@' sign
    // @link http://stackoverflow.com/questions/7380782/error-supression-operator-and-set-error-handler
    $error_reporting = ini_get('error_reporting');
    if (!($error_reporting & $errno)) {
        return;
    }
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler("exception_error_handler");

// Start user session
if(session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
    session_start();
}

// Share 'mapper' instance
$app['mapper'] = function($app) use($request) {
    $cfg = new \Spot\Config();
    $cfg->addConnection('mysql', $request->env('DATABASE'));
    return new \Spot\Mapper($cfg);
};

// Share 'Guzzle' instance
$app['guzzle'] = function($app) use($request) {
    $client = new Guzzle\Http\Client();
    return $client;
};

// Share 'Opauth' instance
$app['opauth'] = function($app) use($app, $request) {
    $opauth = new Opauth($app['config']['opauth'], false);
    return $opauth;
};

// Share SwiftMailer instance
$app['mailer'] = $app->share(function($app) use($request) {
    // Create the Transport
    $transport = Swift_SmtpTransport::newInstance('smtp.mandrillapp.com', 587)
        ->setUsername($request->env('MANDRILL_USER'))
        ->setPassword($request->env('MANDRILL_PASS'));
    return Swift_Mailer::newInstance($transport);
});

// User object (always available blank object)
$app['user'] = new \Entity\User();

// Force HTTPS only on production
$app->on('before', function(\Bullet\Request $request, \Bullet\Response $response) use($app) {
    // Not for CLI requests
    if($request->isCli()) { return; }

    if(BULLET_ENV == 'production') {
        if ($request->isSecure()) {
            header('Strict-Transport-Security: max-age=31536000');
        } elseif (!$request->isSecure()) {
            header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], true, 301);
            die();
        }
    }
});

// Register helpers
$app->helper('api',  'App\Helper\Api');
$app->helper('form', 'App\Helper\Form');
$app->helper('date', 'App\Helper\Date');

// Shortcut to access $app instance anywhere
function app() {
    return $GLOBALS['app'];
}

// Display exceptions with error and 500 status
$app->on('Exception', function(\Bullet\Request $request, \Bullet\Response $response, \Exception $e) use($app) {
    if($request->format() === 'json') {
        $data = array(
            'error' => str_replace('Exception', '', get_class($e)),
            'message' => $e->getMessage()
        );

        // Debugging info for development ENV
        if(BULLET_ENV !== 'production') {
            $data['file'] = $e->getFile();
            $data['line'] = $e->getLine();
            $data['trace'] = $e->getTrace();
        }

        $response->content($data);
    } elseif($request->isCli()) {
        $response->content($e);
    } else {
        $response->content($app->template('errors/exception', array('e' => $e))->content());
    }

    if(BULLET_ENV === 'production') {
        // An error happened in production. You should really let yourself know about it.
        // TODO: Email, log to file, or send to error-logging service like Sentry, Airbrake, etc.
    }
});

// Custom 404 Error Page
$app->on(404, function(\Bullet\Request $request, \Bullet\Response $response) use($app) {
    if($request->format() === 'json' || $request->isCli()) {
        $data = array(
            'error' => 404,
            'message' => 'Not Found'
        );
        $response->contentType('application/json');
        $response->content(json_encode($data));
    } else {
        $response->content($app->template('errors/404')->content());
    }
});

$app->registerResponseHandler(
    function($response) {
        return $response->content() instanceof \Hyperspan\Response;
    },
    function($response) use($app) {
        $request = $app->request();
        $format = new Hyperspan\Formatter\Siren($response->content());

        if($request->format() === 'html') {
            $res = new App\Api\Template($format->toArray());
            $response->content($res->content());
        } elseif($request->format() === 'json') {
            /* $response->contentType('application/vnd.siren+json'); */
            $response->contentType('application/json');
            $response->content($format->toJson());
        } else {
            $response->status(406);
            $response->content(json_encode(array(
                'error' => 406,
                'message' => "Invalid request format. Accepted formats are 'text/html' and 'application/json'"
            )));
        }
    }
);

// Super-simple language translation by key => value array
function t($string) {
    static $lang = null;
    static $langs = array();
    if($lang === null) {
        $lang = app()->request()->get('lang', 'en');
        if(!preg_match("/^[a-z]{2}$/", $lang)) {
            throw new \Exception("Language must be a-z and only two characters");
        }
    }
    if(!isset($langs[$lang])) {
        $langFile = __DIR__ . '/lang/' . $lang . '.php';
        if(!file_exists($langFile)) {
            throw new \Exception("Language '$lang' not supported. Sorry :(");
        }
        $langs[$lang] = require($langFile);
    }

    if(isset($langs[$lang][$string])) {
        return $langs[$lang][$string];
    }
    return $string;
}

