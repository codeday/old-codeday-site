<?php

namespace CodeDay\Http\Controllers;

use CodeDay\Models;

class EventController extends Controller
{
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
        return \View::make('event', [
            'is_mobile' => preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER['HTTP_USER_AGENT']),
        ]);
    }

    public function getPast()
    {
        return redirect()->to(config('showcase.api_base').'/region/'.\Route::input('event')->region_id);
    }

    public function getSchedule()
    {
        return json_encode(\Route::input('event')->schedule);
    }

    public function getSchoolsRegister()
    {
        return \View::make('schools-register');
    }

    public function getIcs()
    {
        return response()->view('event-ics')->header('Content-Type', 'text/plain');
    }

    public function getDisambiguation()
    {
        return \View::make('disambiguation');
    }

    public function getRegister()
    {
        $event = \Route::input('event');
        if (!$event->registration_info['is_open'] || $event->registration_info['remaining'] == 0) {
            return \Redirect::to('/'.$event->webname);
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
