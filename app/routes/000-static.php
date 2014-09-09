<?php

\Route::get('/', '\CodeDay\Controllers\StaticController@getGlobal');

\Route::get('/press', '\CodeDay\Controllers\StaticController@getPress');
\Route::get('/sponsor', '\CodeDay\Controllers\StaticController@getSponsor');
\Route::get('/rules', '\CodeDay\Controllers\StaticController@getRules');
