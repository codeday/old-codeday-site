<?php

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'local.json'), true);

return [
    'user_id' => $config['maxmind']['user_id'],
    'license_key' => $config['maxmind']['license_key']
];
