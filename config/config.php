<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'app_url' => env('APP_URL'),
    'microservice_name' => env('MICROSERVICE_NAME'),
    'authservice' => [
        'url' => env('AUTH_SERVER_URL', 'http://auth.stallionexpress.xyz'),
        'client_id' => env('CLIENT_ID'),
        'client_secret' => env('CLIENT_SECRET'),
        'front_end_url' => env('FRONT_END_URL'),
    ],
];
