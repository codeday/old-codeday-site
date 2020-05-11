<?php

namespace CodeDay\Http\Controllers;

use CodeDay\Models;

class VolunteerController extends Controller
{
    public function getIndex()
    {
      return \View::make('volunteer/covid');
      /*
        return \View::make('volunteer/index', [
            'loaded_batch' => Models\Batch::current(),
            'tz_regions'   => $this->getTzList(),
        ]);
        */
    }

    public function getApplyStaff()
    {
        return \redirect()->to('/volunteer');
    }

    public function getApplyJudge()
    {
        return \redirect()->to('/volunteer');
    }

    public function getApplyMentor()
    {
        return \redirect()->to('/volunteer');
    }

    protected function getTzList()
    {
        $visitor_info = Models\Ip::find(\Request::getClientIp());
        $event = Models\Event::closestNearby($visitor_info->lat, $visitor_info->lng);
        $current_regions = Models\Region::nearby($visitor_info->lat, $visitor_info->lng, null, null, true);

        $tz_regions = ['America/Los_Angeles' => [], 'America/Denver' => [], 'America/Chicago' => [], 'America/Detroit' => [], 'other' => []];
        foreach ($current_regions as $region) {
            $key = isset($tz_regions[$region->timezone]) ? $region->timezone : 'other';
            $tz_regions[$key][] = $region;
        }

        return $tz_regions;
    }

    protected function getApply()
    {
        $manager_username = null;
        try {
            $manager_username = Models\Event::find(Models\Region::find(\Input::get('region'))->current_event->id)->manager['username'];
        } catch (\Exception $ex) {}

        if (\Input::get('region')) {
            return \View::make('volunteer/apply', [
                'region'        => \Input::get('region'),
                'manager'       => $manager_username
            ]);
        } else {
            return \View::make('volunteer/pick-region', [
                'loaded_batch' => Models\Batch::current(),
                'partner'      => $this->getPartner(),
                'tz_regions'   => $this->getTzList(),
            ]);
        }
    }
}
