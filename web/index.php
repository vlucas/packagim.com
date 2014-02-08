<?php
define('BULLET_ROOT', dirname(__DIR__));
define('BULLET_WEB_ROOT', BULLET_ROOT . '/htdocs/');
define('BULLET_APP_ROOT', BULLET_ROOT . '/app/');
define('BULLET_SRC_ROOT', BULLET_APP_ROOT . 'src/');

// Composer Autoloader
$loader = require BULLET_ROOT . '/vendor/autoload.php';
$request = new Bullet\Request();

// ENV globals
if(defined('PHPUNIT_RUN')) {
    define('BULLET_ENV', 'testing');
} else {
    define('BULLET_ENV', $request->env('BULLET_ENV', 'development'));
}

// Load required environment variables from .env in development
if(BULLET_ENV == 'development') {
    Dotenv::load(dirname(__DIR__));
}
Dotenv::required([
    'DATABASE', 'ENCRYPTION_KEY',
    'GITHUB_APP_ID', 'GITHUB_APP_SECRET'
]);

// Bullet App
$app = new Bullet\App(require BULLET_APP_ROOT . 'config.php');

// Common include
require BULLET_APP_ROOT . '/common.php';

// Require all paths/routes
$routesDir = BULLET_APP_ROOT . '/routes/';
require $routesDir . 'index.php';
require $routesDir . 'search.php';
require $routesDir . 'packages.php';
require $routesDir . 'oauth.php';

// CLI routes
if($request->isCli()) {
    require $routesDir . 'cli/db.php';
    require $routesDir . 'cli/tasks.php';
}

// Response
if(BULLET_ENV !== 'testing') {
    echo $app->run($request);
}

