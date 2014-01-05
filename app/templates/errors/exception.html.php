<?php
$view->set('title', 'An Error Has Occurred');
$view->layout('error');
?>

<div class="text-left">
  <p><strong><?php echo $e->getMessage(); ?></strong></p>
  <p><code><?php echo $e->getFile(); ?>:<?php echo $e->getLine(); ?></code></p>
  <pre><?php echo $e->getTraceAsString(); ?></pre>
</div>

