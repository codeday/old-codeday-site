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
            \View::share('ticket', $this->ticket);
        }
    }

    public function getFind()
    {
        // TODO
    }

    public function getIndex()
    {
        return \View::make('vip/ticket', ['ticket' => $this->ticket]);
    }
}