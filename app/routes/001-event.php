<?php

use \CodeDay\Models;

\Route::bind('event', function($webname) {
    $event = Models\Region::find(strtolower($webname))->current_event;
    if ($event->webname != $webname) {
        \App::abort(302, '', ['Location' => '/'.$event->webname]);
    } else {
        return $event;
    }
});

\Route::get('/{event}/register', '\CodeDay\Controllers\EventController@getRegister');
\Route::post('/{event}/register', '\CodeDay\Controllers\EventController@postRegister');
\Route::get('/{event}', '\CodeDay\Controllers\EventController@getIndex');
