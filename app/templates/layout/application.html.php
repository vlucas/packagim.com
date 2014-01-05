<?php
$app = app();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Packagim</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link href="<?php echo $app->url('/assets/styles/bootstrap.min.css'); ?>" rel="stylesheet" media="screen" type="text/css" />
  <link href="<?php echo $app->url('/assets/styles/bootstrap-datetimepicker.css'); ?>" rel="stylesheet" media="screen" type="text/css" />
  <link href="<?php echo $app->url('/assets/styles/application.css'); ?>" rel="stylesheet" media="screen" type="text/css" />
</head>
<body>
  <!-- Header -->
  <div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
    <div class="navbar-header">
      <a class="navbar-brand" href="<?= $app->url('/'); ?>">Packagim</a>
    </div>
    <ul class="nav navbar-nav navbar-right">
    <?php if($app['user']->isLoggedIn()):
        $user = $app['user'];
    ?>
      <li class="dropdown">
        <a href="<?php echo $app->url('/users/' . $user->id); ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $user->name; ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="<?php echo $app->url('/users/' . $user->id); ?>">Profile</a></li>
          <li class="divider"></li>
          <li><a href="<?php echo $app->url('/logout'); ?>">Logout</a></li>
        </ul>
      </li>
    <?php else: ?>
      <li><a href="<?php echo $app->url('/login'); ?>">Sign In</a></li>
    <?php endif; ?>
    </ul>
  </div>

  <div class="container">
    <!-- Content -->
    <div class="row">
      <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <div id="sidebar">
          <ul id="nav_sidebar">
          <li><a href="<?php echo $app->url('/'); ?>">Home</a></li>
        <ul>
        </div>
      </div>

      <div id="content_container" class="col-12 col-sm-9 col-md-9 col-lg-9" style="margin-left: 0;">
        <div id="content" class="bBox">
          <?php echo $yield; ?>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="footer">
          <p class="pull-right">Made with &hearts; by <a href="http://vancelucas.com">Vance Lucas</a></p>
          <p>All content &copy; Packagim.com <?php echo date('Y'); ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScripts -->
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo $app->url('assets/scripts/bootstrap.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo $app->url('assets/scripts/bootstrap-datetimepicker.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo $app->url('assets/scripts/application.js'); ?>"></script>
</body>
</html>
