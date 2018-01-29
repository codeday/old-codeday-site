<?php

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'local.json'), true);

return [
    'driver'   => 'smtp',
    'host'     => 'smtp.mailgun.org',
    'port'     => 587,
    'from'     => ['name' => null, 'address' => null],
    'username' => $config['mailgun']['smtp']['username'],
    'password' => $config['mailgun']['smtp']['password'],
    'pretend'  => $config['mailgun']['pretend'],
];
