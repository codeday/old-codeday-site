<?php
namespace CodeDay\Controllers;

use \CodeDay\Models;

class EventController extends \Controller {
    public function __construct()
    {
        $visitor_info = Models\Ip::find(\Request::getClientIp());

        \View::share('event', \Route::input('event'));
        \View::share('visitor', $visitor_info);
    }

    public function getIndex()
    {
        return \View::make('index');
    }

    public function getRegister()
    {
        $event = \Route::input('event');
        if (!$event->registration_info['is_open']) {
            return \Redirect::to('/');
        }

        return \View::make('register');
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
