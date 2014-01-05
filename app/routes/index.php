<?php
// Options from root URL (should expose all available user choices)
$app->path(array('/', 'index'), function($request) {
    $this->get(function($request) {
        $res = new Hyperspan\Response();
        $res->title = 'Main';
        $res->rel = array('index');
        $res->class = array('index');
        $res->addLink('index_nav', [
            'rel' => ['nav', 'home'],
            'class' => ['home'],
            'title' => t('Home'),
            'href' => $this->url('/')
        ]);
        $res->addAction('search', [
            'title' => 'Package Search',
            'href'   => $this->url('/search/'),
            'method' => 'GET',
            'fields' => [
                ['name' => 'name', 'type' => 'string']
            ]
        ]);
        return $res;
    });
});

