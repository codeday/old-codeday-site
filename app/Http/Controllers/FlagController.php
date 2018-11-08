<?php

namespace CodeDay\Http\Controllers;

class FlagController extends Controller
{
    public function getIndex()
    {
        if (hash('sha256', \Input::get('password')) !== 'd2ee6b50c79265b62565317be23f9c8ca6d81963767a277f4d5338527e6c1f8b') {
            return '<form><input name="password" type="password" /><input type="submit" /></form>';
        } else {
            return 'the flag is billowing in the wind';
        }
    }
}
