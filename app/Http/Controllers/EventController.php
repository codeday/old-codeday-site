<?php
namespace CodeDay\Http\Controllers;

use \CodeDay\Models;

class EventController extends Controller {
    public function __construct()
    {
        $visitor_info = Models\Ip::find(\Request::getClientIp());

        if (!\Route::input('event')) {
            \App::abort(404);
        }

        \View::share('event', \Route::input('event'));
        \View::share('visitor', $visitor_info);
        \View::share('loaded_batch', Models\Batch::current());
    }

    public function getIndex()
    {
        return \View::make('event');
    }

    public function getRegister()
    {
        $event = \Route::input('event');
        if (!$event->registration_info['is_open'] || $event->registration_info['remaining'] == 0) {
            return \Redirect::to('/'.$event->webname);
        }

        return \View::make('register');
    }

    public function getSchools()
    {
        return \View::make('schools');
    }

    public function getPress()
    {
        return \View::make('press');
    }

    public function getSponsor()
    {
        return \View::make('sponsor');
    }

    public function getRules()
    {
        return \View::make('rules');
    }
} 
