<?php

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'local.json'), true);

return [
    'driver' => 'memcached',
    'prefix' => $config['memcached']['prefix'],
    'memcached' => $config['memcached']['servers']
];
