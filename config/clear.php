<?php

return [
    'access_token'    => env('CLEAR_ACCESS_TOKEN'),
    'secret'          => env('CLEAR_SECRET'),
    'api_base'        => env('CLEAR_API_BASE'),
    'api_base_public' => env('CLEAR_API_BASE', env('CLEAR_API_BASE_PUBLIC')),
];
