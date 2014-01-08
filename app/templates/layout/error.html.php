<?php
$app = app();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Application Error</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700" rel="stylesheet" type="text/css" />
  <link href="<?php echo $app->url('/assets/styles/semantic.min.css'); ?>" rel="stylesheet" media="screen" type="text/css" />
  <link href="<?php echo $app->url('/assets/styles/application.css'); ?>" rel="stylesheet" media="screen" type="text/css" />
</head>
<body class="error">
  <div class="ui grid">
    <!-- Content -->
    <div class="row">
      <div class="three wide column"></div>
      <div class="ten wide column">
        <div class="ui red segment">
          <h1 class="ui header">
            <i class="ui warning icon"></i>
            <?php echo isset($title) ? $title : 'Application Error'; ?>
          </h1>
          <div class="ui clearing divider"></div>
          <div id="content">
            <?php echo $yield; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScripts -->
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo $app->url('assets/scripts/semantic.min.js'); ?>"></script>
</body>
</html>
