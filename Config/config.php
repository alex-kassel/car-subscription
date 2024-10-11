<?php

return [
    'connections' => [
        'cars' => [
            'driver' => 'mysql',
            'url' => env('DB_CARS_URL'),
            'host' => env('DB_CARS_HOST', '127.0.0.1'),
            'port' => env('DB_CARS_PORT', '3306'),
            'database' => env('DB_CARS_DATABASE', 'laravel'),
            'username' => env('DB_CARS_USERNAME', 'root'),
            'password' => env('DB_CARS_PASSWORD', ''),
            'unix_socket' => env('DB_CARS_SOCKET', ''),
            'charset' => env('DB_CARS_CHARSET', 'utf8mb4'),
            'collation' => env('DB_CARS_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],
    ],
];
