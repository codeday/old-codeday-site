<?php

\Route::get('/', '\CodeDay\Controllers\StaticController@getGlobal');
\Route::post('/swag', '\CodeDay\Controllers\StaticController@postSwag');

\Route::get('/press', '\CodeDay\Controllers\StaticController@getPress');
\Route::get('/sponsor', '\CodeDay\Controllers\StaticController@getSponsor');
\Route::get('/rules', '\CodeDay\Controllers\StaticController@getRules');
