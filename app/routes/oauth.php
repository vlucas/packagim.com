<?php
$app->path('oauth', function($request) {
    $this->path('github', function($request) {
        $this->get(function($request) {
            // Run Oauth based on current request URI
            return $this['opauth']->run();
        });

        // Oauth callback
        $this->path('oauth2callback', function($request) {
            $this->get(function($request) {
                // Run Oauth based on current request URI
                return $this['opauth']->run();
            });
        });
    });

    // Oauth callback to actually store the data
    $this->path('callback', function($request) {
        $this->get(function($request) {
            /**
            * Auth response dump
            */
            echo "<h1>Session Data</h1><pre>";
            print_r($_SESSION);
            echo "</pre>";
        });
    });
});

