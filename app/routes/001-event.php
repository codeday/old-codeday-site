<?php

use \CodeDay\Models;

\Route::bind('event', function($webname) {
    return Models\Region::find($webname)->current_event;
});

\Route::get('/{event}/register', '\CodeDay\Controllers\EventController@getRegister');
\Route::post('/{event}/register', '\CodeDay\Controllers\EventController@postRegister');
\Route::get('/{event}', '\CodeDay\Controllers\EventController@getIndex');
