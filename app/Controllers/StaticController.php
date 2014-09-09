<?php
namespace CodeDay\Controllers;

use \CodeDay\Models;

class StaticController extends \Controller {
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
