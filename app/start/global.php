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

$include_all_directories = ['events', 'filters', 'routes'];
foreach ($include_all_directories as $directory) {
    foreach (glob(implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), $directory, "*.php"])) as $filename) {
        include_once($filename);
    }
}

