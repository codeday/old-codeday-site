<?php

return [
    'driver'          => 'redis',
    'connection'      => 'default',
    'lifetime'        => 43200,
    'expire_on_close' => false,
    'cookie'          => env('SESSION_COOKIE'),
    'domain'          => null,
    'path'            => '/',
    'secure'          => false,
    'lottery'         => [2, 100],
];
