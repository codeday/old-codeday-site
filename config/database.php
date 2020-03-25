<?php

return [
    'fetch'       => \PDO::FETCH_CLASS,
    'default'     => 'mysql',
    'migrations'  => 'migrations',
    'connections' => [],
    'redis'       => [
      'client' => 'phpredis',
      'default' => [
        'host' => env('REDIS_HOST'),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DB', 0),
        'prefix' => env('REDIS_PREFIX', 'codeday_www_'),
        'persistent' => env('REDIS_PERSISTENT', true),
      ]
    ],
];
