<?php
$app = app();
$request = $app->request();
$packages = $data->getItems();
?>
  <h1>Package Search '<?= $request->q; ?>'</h1>

<div class="">
  <?php foreach($packages as $package): ?>
    <div class="ui purple segment">
      <h2>
        <div class="ui top right attached label">Downloads <div class="detail"><?= $package['download_count']; ?></div></div>
        <a href="/packages/<?= $package['name']; ?>"><?= $package['name']; ?></a>
      </h2>
      <p><?= $package['description']; ?></p>
    </div>
  <?php endforeach; ?>
</div>

