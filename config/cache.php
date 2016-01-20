<?php

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'local.json'), true);
$config['memcached']['driver'] = 'memcached';

return [
    'default' => 'memcached',
    'prefix' => $config['memcached']['prefix'],
    'stores' => ['memcached' => $config['memcached']]
];
