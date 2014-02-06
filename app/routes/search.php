<?php
// Options from root URL (should expose all available user choices)
$app->path('search', function($request) {
    $this->get(function($request) {
        // Response data
        $data = new Hyperspan\Response();
        $data->title = 'Main';
        $data->rel = array('index');
        $data->class = array('index');
        $data->addLink('index_nav', [
            'rel' => ['nav', 'home'],
            'class' => ['home'],
            'title' => t('Home'),
            'href' => $this->url('/')
        ]);
        $data->addAction('search', [
            'title' => 'Package Search',
            'href'   => $this->url('/search/'),
            'method' => 'GET',
            'fields' => [
                ['name' => 'name', 'type' => 'string']
            ]
        ]);

        // Search by keyword
        if($request->q) {
            $packagist = new Packagist\Api\Client();
            foreach ($packagist->search($request->q) as $result) {
                $package = $this['mapper']->upsert('Entity\Package', [
                    'name' => $result->getName(),
                    'description' => $result->getDescription(),
                    'url' => $result->getUrl(),
                    'download_count' => $result->getDownloads(),
                    'favorites_count' => $result->getFavers(),
                    'source' => 'packagist',
                    'language' => 'php'
                ], [
                    'name' => $result->getName(),
                    'source' => 'packagist'
                ]);
                $data->addItem($package->toResponse());
            }
        } else {

        }

        $this->format('json', function($request) use($data) {
            return $data;
        });
        $this->format('html', function($request) use($data) {
            return $this->template('search/index', compact('data'));
        });
    });
});

