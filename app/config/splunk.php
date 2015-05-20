<?php

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'local.json'), true);

return [
    'admin' => $config['splunk']['admin'],
    'server' => $config['splunk']['server']
];
