<?php

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'local.json'), true);

return [
    'key'   => $config['flickr']['key'],
    'group' => $config['flickr']['group'],
];
