<?php

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'local.json'), true);

return [
    'consumer_key'    => $config['twitter']['consumer_key'],
    'consumer_secret' => $config['twitter']['consumer_secret'],
];
