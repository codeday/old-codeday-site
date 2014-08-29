<?php
namespace CodeDay\Controllers;

use \CodeDay\Models;

class StaticController extends \Controller {
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
