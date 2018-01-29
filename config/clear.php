<?php

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'local.json'), true);

return [
    'access_token'    => $config['clear']['access_token'],
    'secret'          => $config['clear']['secret'],
    'api_base'        => $config['clear']['api_base'],
    'api_base_public' => isset($config['clear']['api_base_public']) ? $config['clear']['api_base_public'] : $config['clear']['api_base'],
];
