<?php
$app = app();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Application Error</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?php echo $app->url('/assets/styles/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
  <link href="<?php echo $app->url('/assets/styles/application.css'); ?>" rel="stylesheet" type="text/css" />
</head>
<body>
  <div class="container">

    <!-- Content -->
    <div class="row text-center">
      <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
        <div class="panel panel-danger">
          <div class="panel-heading">
            <h1 class="panel-title"><?php echo isset($title) ? $title : 'Application Error'; ?></h1>
          </div>
          <div class="panel-body">
            <?php echo $yield; ?>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScripts -->
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo $app->url('assets/scripts/bootstrap.min.js'); ?>"></script>
</body>
</html>
