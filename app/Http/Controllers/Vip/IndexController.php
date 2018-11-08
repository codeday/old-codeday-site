<?php

namespace CodeDay\Http\Controllers\Vip;

use CodeDay\Http\Controllers;
use CodeDay\Models;

class IndexController extends Controllers\Controller
{
    protected $ticket;

    public function __construct()
    {
        if (\Route::input('ticket')) {
            $this->ticket = Models\Ticket::find(\Route::input('ticket'));
            if (!isset($this->ticket->id)) {
                \App::abort(404);
            }
            \View::share('ticket', $this->ticket);
        }
    }

    public function getFind()
    {
        return \View::make('vip/index');
    }

    public function postFind()
    {
        $tickets = Models\Ticket::findByEmail(\Input::get('email'));

        return \View::make('vip/all-tickets', ['email' => \Input::get('email'), 'tickets' => $tickets->all_registrations]);
    }

    public function getIndex()
    {
        return \View::make('vip/ticket');
    }

    public function getParent()
    {
        return \View::make('vip/parent');
    }

    public function getVenue()
    {
        return \View::make('vip/venue-agreement');
    }

    public function postParent()
    {
        if (!isset($this->ticket->age) || !$this->ticket->age) {
            $this->ticket->setParentInfo(\Input::get('age'));
            if (\Input::get('age') > 18 && \Input::get('age') < 24) {
                return \Redirect::to('/'.$this->ticket->id);
            }

            return \Redirect::to('/'.$this->ticket->id.'/parent');
        } else {
            $this->ticket->setParentInfo(null, \Input::get('parent_name'), \Input::get('parent_email'),
                                            \Input::get('parent_phone'), \Input::get('parent_secondary_phone'),
                                            \Input::get('request_loaner'));

            return \Redirect::to('/'.$this->ticket->id);
        }
    }

    public function getFeedback()
    {
        return \View::make('vip/survey', ['id' => $this->ticket->type == 'student' ? 28 : 29]);
    }

    public function getSurvey()
    {
        return \View::make('vip/survey', ['id' => 2]);
    }

    public function getWaiver()
    {
        if ($this->ticket->waiver_pdf !== null) {
            return \Redirect::to($this->ticket->waiver_pdf);
        }
        $key = hash_hmac('whirlpool', $this->ticket->id.'-'.$this->ticket->age, \Config::get('app.key'));

        if (\Input::get('key') == $key) {
            if (\Input::get('consent')) {
                $waiverContent = file_get_contents(sprintf('https://srnd-cdn.net/codeday/waiver-%sminor.html', ($this->ticket->is_minor ? '' : 'non')));
                return \View::make('vip/waiver', ['waiver_content' => $waiverContent]);
            } else {
                return \View::make('vip/waiver-consent', ['key' => $key]);
            }
        } else {
            return \redirect('/'.$this->ticket->id.'/waiver?key='.$key);
        }
    }

    public function getSyncwaiver()
    {
        $this->ticket->syncWaiver();

        return \Redirect::to('/'.$this->ticket->id);
    }

    public function getCognitojs()
    {
        return response(file_get_contents('https://services.cognitoforms.com/s/7hYXr3TPxk6yIpJxjqVoFQ'))->header('Content-type', 'text/plain');
    }
}
