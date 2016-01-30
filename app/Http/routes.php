<?php

use CodeDay\Models;

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


// Static Routes
\Route::get('/', '\CodeDay\Http\Controllers\StaticController@getGlobal');
\Route::get('/video', '\CodeDay\Http\Controllers\StaticController@getVideo');
\Route::post('/swag', '\CodeDay\Http\Controllers\StaticController@postSwag');

\Route::get('/press', '\CodeDay\Http\Controllers\StaticController@getPress');
\Route::get('/sponsor', '\CodeDay\Http\Controllers\StaticController@getSponsor');
\Route::get('/rules', '\CodeDay\Http\Controllers\StaticController@getRules');

\Route::get('/splunk', '\CodeDay\Http\Controllers\SplunkController@getIndex');
\Route::post('/splunk', '\CodeDay\Http\Controllers\SplunkController@postIndex');

\Route::controller('/phone', '\CodeDay\Http\Controllers\PhoneController');


// Event-based Routes
\Route::bind('event', function($webname) {
        $event = Models\Region::find(strtolower($webname))->current_event;
            if ($event->webname != $webname) {
                        \App::abort(302, '', ['Location' => '/'.$event->webname]);
                            } else {
                                        return $event;
                                            }
});

\Route::get('/{event}/register', '\CodeDay\Http\Controllers\EventController@getRegister');
\Route::post('/{event}/register', '\CodeDay\Http\Controllers\EventController@postRegister');
\Route::get('/{event}', '\CodeDay\Http\Controllers\EventController@getIndex');
