<?php
namespace CodeDay\Controllers;

use \CodeDay\Models;

class EventController extends \Controller {
    public function __construct()
    {
        $visitor_info = Models\Ip::find(\Request::getClientIp());

        if (!\Session::has('loaded_event')) {
            $event = Models\Event::closestNearby($visitor_info->lat, $visitor_info->lng);
            \Session::set('loaded_event', $event->id);
        }

        $event = Models\Event::find(\Session::get('loaded_event'));
        \View::share('event', $event);
        \View::share('visitor', $visitor_info);
    }

    public function getIndex()
    {

        if (\Input::get('region')) {
            try {
                $region = Models\Region::find(\Input::get('region'));
                \Session::put('loaded_event', $region->current_event->id);
            } catch (\Exception $ex) {}


            // Try to redirect
            if (\Request::isMethod('get')) {
                return \Redirect::to(\Request::url());
            }
        }

        return \View::make('index');
    }

    public function getRegister()
    {
        return \View::make('register');
    }
} 
