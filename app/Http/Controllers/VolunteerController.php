<?php

namespace CodeDay\Http\Controllers;

use CodeDay\Models;

class VolunteerController extends Controller
{
    public function getIndex()
    {
        return \View::make('volunteer/index', [
            'loaded_batch' => Models\Batch::current(),
            'partner'      => $this->getPartner(),
            'tz_regions'   => $this->getTzList(),
        ]);
    }

    public function getApplyStaff()
    {
        return $this->getApply('staff');
    }

    public function getApplyJudge()
    {
        return $this->getApply('judge');
    }

    public function getApplyMentor()
    {
        return $this->getApply('mentor');
    }

    protected function getPartner()
    {
        return config('partners.'.\Input::get('partner'));
    }

    protected function getTzList()
    {
        $visitor_info = Models\Ip::find(\Request::getClientIp());
        $event = Models\Event::closestNearby($visitor_info->lat, $visitor_info->lng);
        $current_regions = iterator_to_array(Models\Region::nearby($visitor_info->lat, $visitor_info->lng, null, null, true));

        $tz_regions = ['America/Los_Angeles' => [], 'America/Denver' => [], 'America/Chicago' => [], 'America/Detroit' => []];
        foreach ($current_regions as $region) {
            $tz_regions[$region->timezone][] = $region;
        }

        return $tz_regions;
    }

    protected function getApply(string $type)
    {
        if (\Input::get('region')) {
            return \View::make('volunteer/apply', [
                'form'          => config('wufoo.'.$type.'.form'),
                'partner_field' => config('wufoo.'.$type.'.partner_field'),
                'partner'       => $this->getPartner(),
                'region'        => \Input::get('region'),
            ]);
        } else {
            return \View::make('volunteer/pick-region', [
                'loaded_batch' => Models\Batch::current(),
                'partner'      => $this->getPartner(),
                'tz_regions'   => $this->getTzList(),
                'type'         => $type,
            ]);
        }
    }
}
