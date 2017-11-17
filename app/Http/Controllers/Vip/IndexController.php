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

    public function postParent()
    {
        if (!isset($this->ticket->age) || !$this->ticket->age) {
            $this->ticket->setParentInfo(\Input::get('age'));
            if (\Input::get('age') > 18 && \Input::get('age') < 24) return \Redirect::to('/'.$this->ticket->id);
            return \Redirect::to('/'.$this->ticket->id.'/parent');
        } else {
            $this->ticket->setParentInfo(null, \Input::get('parent_name'), \Input::get('parent_email'),
                                         \Input::get('parent_phone'), \Input::get('parent_secondary_phone'));
            return \Redirect::to('/'.$this->ticket->id);
        }
    }

    public function getFeedback()
    {
        $surveyId = 'r1jqi4o91s9dh5y';
        $surveyIds = [
            'judge'     => 'r102046d1g4b10v',
            'mentor'    => 'r1e4qtd71ums611',
            'volunteer' => 'rpjcym718b9oig',
            'teacher'   => 'r6ipajq0noogbo',
            'student'   => 'z1y2xzx4017odw2',
            'sponsor'   => 'rxm2qhh1if32l2'
        ];
        $surveyId = $surveyIds[$this->ticket->type] ?? $surveyId;
        return \View::make('vip/survey', ['id' => $surveyId]);
    }

    public function getSurvey()
    {
        return \View::make('vip/survey', ['id' => 'mhft44g178lpcj']);
    }

    public function getWaiver()
    {
        $url = $this->ticket->getSigningLink();
        if ($this->ticket->waiver_pdf !== null) {
            return \Redirect::to('/'.$this->ticket->id);
        }
        return \View::make('vip/waiver', ['url' => $url]);
    }

    public function getSyncwaiver()
    {
        $this->ticket->syncWaiver();
        return \Redirect::to('/'.$this->ticket->id);
    }
}
