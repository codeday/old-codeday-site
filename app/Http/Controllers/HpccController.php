<?php

namespace CodeDay\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;

class HpccController extends Controller
{
    public function getIndex()
    {
        return \View::make('hpcc');
    }
}
