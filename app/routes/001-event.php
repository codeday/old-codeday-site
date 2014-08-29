<?php

use \CodeDay\Models;

\Route::bind('event', function($webname) {
    return Models\Region::find($webname)->current_event;
});

\Route::get('/', function() {
    $visitor_info = Models\Ip::find(\Request::getClientIp());
    $event = Models\Event::closestNearby($visitor_info->lat, $visitor_info->lng);
    return \Redirect::to('/'.$event->webname);
});

\Route::get('/{event}/register', '\CodeDay\Controllers\EventController@getRegister');
\Route::post('/{event}/register', '\CodeDay\Controllers\EventController@postRegister');
\Route::get('/{event}', '\CodeDay\Controllers\EventController@getIndex');
