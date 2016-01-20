<?php

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'local.json'), true);

return [
    'driver' => 'file',
    'files' => storage_path().'/framework/sessions',
    'lifetime' => 2592000,
    'expire_on_close' => false,
    'cookie' => $config['session']['cookie'],
    'domain' => null,
    'path' => '/',
    'secure' => false,
    'lottery' => [2, 100],
];
