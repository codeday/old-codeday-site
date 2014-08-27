<?php

\Route::get('/register', '\CodeDay\Controllers\EventController@getRegister');
\Route::get('/changeEvent', '\CodeDay\Controllers\EventController@getChangeEvent');
\Route::post('/register', '\CodeDay\Controllers\EventController@postRegister');
\Route::get('/', '\CodeDay\Controllers\EventController@getIndex');