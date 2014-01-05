<?php
// Config that gets passed into main Bullet/App instance
return [
    'env' => getenv('BULLET_ENV') ? getenv('BULLET_ENV') : 'development',
    'template' => [
        'path' => __DIR__ . '/templates/',
        'path_layouts' => __DIR__ . '/templates/layout/',
        'auto_layout' => 'application'
    ],
];
