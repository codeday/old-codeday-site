<?php
namespace CodeDay\Http\Controllers;

use \CodeDay\Models;

class StaticController extends Controller {
    public function getGlobal()
    {
        $visitor_info = Models\Ip::find(\Request::getClientIp());
        $event = Models\Event::closestNearby($visitor_info->lat, $visitor_info->lng);
        $current_regions = iterator_to_array(Models\Region::nearby($visitor_info->lat, $visitor_info->lng, null, null, true));

        $tz_regions = ['America/Los_Angeles' => [], 'America/Denver' => [], 'America/Chicago' => [], 'America/Detroit' => []];
        foreach ($current_regions as $region) {
            $tz_regions[$region->timezone][] = $region;
        }

        return \View::make('global', [
            'visitor' => $visitor_info,
            'nearest_event' => $event,
            'batch_regions' => $current_regions,
            'loaded_batch' => Models\Batch::current(),
            'tz_regions' => $tz_regions
        ]);
    }
    
    public function getArizona()
    {
        // HACK
        return \View::make('arizona');
    }

    public function postSwag()
    {
        if (!\Session::get('swag-request')) {
            $swag = new Models\Swag;
            $swag->name = \Input::get('name');
            $swag->address_1 = \Input::get('address_1');
            $swag->address_2 = \Input::get('address_2');
            $swag->city = \Input::get('city');
            $swag->state = \Input::get('state');
            $swag->postal = \Input::get('postal');
            $swag->save();
            \Session::set('swag-request', true);
        }
        return ['status' => 200];
    }

    public function getPress()
    {
        return \Redirect::to('https://srnd.org/press');
    }

    public function getNew()
    {
        return \View::make('new');
    }

    public function getSponsor()
    {
        return \View::make('sponsor');
    }

    public function getRules()
    {
        return \View::make('rules');
    }

    public function getVideo()
    {
        return \View::make('video');
    }
} 
