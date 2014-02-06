<?php
$app = app();
$request = $app->request();
$user = $app['user'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Packagim</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet" type="text/css" />
  <link href="<?php echo $app->url('/assets/styles/semantic.min.css'); ?>" rel="stylesheet" media="screen" type="text/css" />
  <link href="<?php echo $app->url('/assets/styles/application.css'); ?>" rel="stylesheet" media="screen" type="text/css" />
</head>
<body>
  <!-- Header -->
  <h1 class="ui header">Packagim &mdash; The PHP Package Dependency Manager</h1>
  <div class="ui large purple inverted menu">
    <a class="active item" href="/">
      <i class="home icon"></i> Home
    </a>
    <a class="item" href="/packages">
      <i class="search icon"></i> Packages
    </a>
    <div class="right menu">
    <?php if($user->isLoggedIn()): ?>
      <a href="<?php echo $app->url('/users/' . $user->id); ?>">
        <i class="user icon"></i> Profile
      </a>
      <a href="<?php echo $app->url('/logout'); ?>">Logout</a>
    <?php else: ?>
      <div class="item">
        <a href="<?php echo $app->url('/oauth/github'); ?>" class="ui red button">
          <i class="github icon"></i> Sign In with Github
        </a>
      </div>
    <?php endif; ?>
    </div>
    <div class="right menu">
      <form action="/search">
        <div class="item">
          <div class="ui icon input">
            <input name="q" type="text" placeholder="Search Packages...">
            <i class="search link icon"></i>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="ui grid">
    <!-- Content -->
    <div class="row">
      <div class="two wide column"></div>
      <div id="content" class="twelve wide column">
        <?php echo $yield; ?>
      </div>
    </div>

    <!-- Footer -->
    <div class="row">
      <div class="column">
        <div id="footer">
          <p class="pull-right">Made with &hearts; by <a href="http://vancelucas.com">Vance Lucas</a></p>
          <p>All content &copy; Packagim.com <?php echo date('Y'); ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScripts -->
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo $app->url('assets/scripts/semantic.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo $app->url('assets/scripts/application.js'); ?>"></script>
</body>
</html>
