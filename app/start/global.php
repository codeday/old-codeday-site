<?php

ClassLoader::addDirectories(array(

	app_path().'/Commands',
	app_path().'/Controllers',
	app_path().'/Models',
	app_path().'/database/seeds',

));

Log::useFiles(storage_path().'/logs/laravel.log');

App::down(function()
{
	return Response::make("Be right back!", 503);
});

if(\Config::get('app.debug')){
	\App::error(function(Exception $exception, $code){});
}else{
	\App::error(function(Exception $exception, $code)
	{
        if (!in_array($code, [500, 404, 401])) return;

        $traceback_encrypted = null;

        $traceback = $exception->getMessage()."\n Line ".$exception->getLine()." of ".$exception->getFile()
            ."\n\n".$exception->getTraceAsString();

        try {
            $iv = str_random(8);
            $cipher = mcrypt_module_open(MCRYPT_BLOWFISH,'','cbc','');

            mcrypt_generic_init($cipher, \Config::get('app.key'), $iv);
            $traceback_encrypted = $iv.base64_encode(mcrypt_generic($cipher,$traceback));
            mcrypt_generic_deinit($cipher);

        } catch (\Exception $ex) {}

		return Response::view('errors.'.$code, array("uri" => Request::path(), "technical_details" => $traceback_encrypted), $code);
	});
}

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
\View::share('api_base_public', \Config::get('clear.api_base_public'));
\View::share('api_access_token', \Config::get('clear.access_token'));

$include_all_directories = ['events', 'filters', 'routes'];
foreach ($include_all_directories as $directory) {
    foreach (glob(implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), $directory, "*.php"])) as $filename) {
        include_once($filename);
    }
}
