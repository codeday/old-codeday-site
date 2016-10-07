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

$routes = function() {
    // Static Routes
    \Route::get('/', '\CodeDay\Http\Controllers\StaticController@getGlobal');
    \Route::get('/video', '\CodeDay\Http\Controllers\StaticController@getVideo');
    \Route::post('/swag', '\CodeDay\Http\Controllers\StaticController@postSwag');

    \Route::get('/arizona', '\CodeDay\Http\Controllers\StaticController@getArizona');
    \Route::get('/press', '\CodeDay\Http\Controllers\StaticController@getPress');
    \Route::get('/sponsor', '\CodeDay\Http\Controllers\StaticController@getSponsor');
    \Route::get('/rules', '\CodeDay\Http\Controllers\StaticController@getRules');
    \Route::get('/evangelist', '\CodeDay\Http\Controllers\StaticController@getEvangelist');

    \Route::get('/new', '\CodeDay\Http\Controllers\StaticController@getNew');
    \Route::get('/volunteer', '\CodeDay\Http\Controllers\VolunteerController@getIndex');
    \Route::get('/volunteer/apply/mentor', '\CodeDay\Http\Controllers\VolunteerController@getApplyMentor');
    \Route::get('/volunteer/apply/judge', '\CodeDay\Http\Controllers\VolunteerController@getApplyJudge');
    \Route::get('/volunteer/apply/staff', '\CodeDay\Http\Controllers\VolunteerController@getApplyStaff');

    \Route::get('/splunk', '\CodeDay\Http\Controllers\SplunkController@getIndex');
    \Route::post('/splunk', '\CodeDay\Http\Controllers\SplunkController@postIndex');

    \Route::controller('/phone', '\CodeDay\Http\Controllers\PhoneController');


    // Event-based Routes
    \Route::bind('event', function($webname) {
        $event = Models\Region::find(strtolower($webname))->current_event;
        if ($event->batch['id'] != Models\Batch::current()->id) {
            return null;
        }
        if ($event->webname != $webname) {
            \App::abort(302, '', ['Location' => '/'.$event->webname]);
        } else {
            return $event;
        }
    });

    \Route::get('/schools', '\CodeDay\Http\Controllers\StaticController@getSchools');
    \Route::get('/{event}/register', '\CodeDay\Http\Controllers\EventController@getRegister');
    \Route::post('/{event}/register', '\CodeDay\Http\Controllers\EventController@postRegister');
    \Route::get('/{event}/register/schools', '\CodeDay\Http\Controllers\StaticController@getSchools');
    \Route::get('/{event}', '\CodeDay\Http\Controllers\EventController@getIndex');
};

\View::share('nonLangUri', '/'.request()->path());
\Route::bind('locale', function($locale) {
    \App::setLocale($locale);
    \View::share('lang', $locale); 
    \View::share('langPrefix', '/'.$locale); 
    \View::share('nonLangUri', substr(request()->path(), strlen($locale)));
    \session_start();
    $_SESSION['lang'] = $locale;
});
\Route::any('/en_US', function() {
    \session_start();
    $_SESSION['lang'] = '';
    return \redirect('/');
});
\Route::any('/en_US/{rest}', function($rest) {
    \session_start();
    $_SESSION['lang'] = '';
    return \redirect($rest);
})->where('rest', '(.*)?');
\Route::group(['prefix' => '/{locale}'], $routes);
\Route::group(['prefix' => '/', 'middleware' => 'default-lang'], $routes);
