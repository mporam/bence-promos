<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],

        'pdo' => [
            'servername' => "localhost",
            'database' => 'eliteweb_bristolfavourscart',
            'username' => "eliteweb_admin",
            'password' => '&1kya369y',
        ]
    ],
];
