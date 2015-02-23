<?php

\Route::get('/', '\CodeDay\Controllers\StaticController@getGlobal');
\Route::post('/swag', '\CodeDay\Controllers\StaticController@postSwag');

\Route::get('/press', '\CodeDay\Controllers\StaticController@getPress');
\Route::get('/sponsor', '\CodeDay\Controllers\StaticController@getSponsor');
\Route::get('/rules', '\CodeDay\Controllers\StaticController@getRules');

\Route::get('/splunk', '\CodeDay\Controllers\SplunkController@getIndex');
\Route::post('/splunk', '\CodeDay\Controllers\SplunkController@postIndex');

\Route::controller('/phone', '\CodeDay\Controllers\PhoneController');