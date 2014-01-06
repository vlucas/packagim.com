<?php
// Config that gets passed into main Bullet/App instance
return [
    'env' => getenv('BULLET_ENV') ? getenv('BULLET_ENV') : 'development',
    'template' => [
        'path' => __DIR__ . '/templates/',
        'path_layouts' => __DIR__ . '/templates/layout/',
        'auto_layout' => 'application'
    ],

    'config' => [
        // Opauth - OAuth provider
        // @link http://opauth.org
        'opauth' => [
            'path' => '/oauth/',
            'callback_url' => '{path}callback',
            'security_salt' => '43#f47$ce919890e68146ee&46dd4^abe9c!1fd98b051d',

            'Strategy' => array(
                'GitHub' => array(
                    'client_id'     => $_SERVER['GITHUB_APP_ID'],
                    'client_secret' => $_SERVER['GITHUB_APP_SECRET']
                ),
            ),
        ]
    ]
];

