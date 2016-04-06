<?php

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'local.json'), true);

return [
    'staff' => $config['wufoo']['staff'],
    'mentor' => $config['wufoo']['mentor'],
    'judge' => $config['wufoo']['judge'],
];
