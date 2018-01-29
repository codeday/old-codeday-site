<?php

namespace CodeDay\Http\Controllers;

class ChallengeController extends Controller
{
    public function getChallenge()
    {
        $password = \Request::input('password');

        if ($password == \Config::get('challenge')) {
            return response()->download('../resources/assets/challenge.zip');
        } else {
            return response('Unauthorized')->header('X-The-Secret-You-Seek', '?password='.\Config::get('challenge'));
        }
    }
}
