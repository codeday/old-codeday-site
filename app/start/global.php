<?php

ClassLoader::addDirectories(array(

	app_path().'/Commands',
	app_path().'/Controllers',
	app_path().'/Models',
	app_path().'/database/seeds',

));

Log::useFiles(storage_path().'/logs/laravel.log');

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});

App::down(function()
{
	return Response::make("Be right back!", 503);
});

\Request::setTrustedProxies([
    '199.27.128.0/21',
    '173.245.48.0/20',
    '103.21.244.0/22',
    '103.22.200.0/22',
    '103.31.4.0/22',
    '141.101.64.0/18',
    '108.162.192.0/18',
    '190.93.240.0/20',
    '188.114.96.0/20',
    '197.234.240.0/22',
    '198.41.128.0/17',
    '162.158.0.0/15',
    '104.16.0.0/12'
]);

\View::share('api_base', \Config::get('clear.api_base'));

$include_all_directories = ['events', 'filters', 'routes'];
foreach ($include_all_directories as $directory) {
    foreach (glob(implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), $directory, "*.php"])) as $filename) {
        include_once($filename);
    }
}

