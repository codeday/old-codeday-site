<?php
namespace CodeDay\Controllers;

use \CodeDay\Models;

class EventController extends \Controller {
    public function __construct()
    {
        if (!\Session::has('loaded_event')) {
            $visitor_info = Models\Ip::find(\Request::getClientIp());
            $event = Models\Event::closestNearby($visitor_info->lat, $visitor_info->lng);
            \Session::set('loaded_event', $event->id);
        }

        $event = Models\Event::find(\Session::get('loaded_event'));
        \View::share('event', $event);
    }

    public function getIndex()
    {
        return \View::make('index');
    }

    public function getRegister()
    {
        return \View::make('register');
    }
} 