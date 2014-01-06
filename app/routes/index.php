<?php
// Options from root URL (should expose all available user choices)
$app->path(array('/', 'index'), function($request) {
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

        $this->format('json', function($request) use($data) {
            return $data;
        });
        $this->format('html', function($request) use($data) {
            return $this->template('index', compact('data'));
        });
    });
});

