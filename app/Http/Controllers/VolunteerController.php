<?php
namespace CodeDay\Http\Controllers;

use \CodeDay\Models;

class VolunteerController extends Controller {
    public function getIndex()
    {
        $visitor_info = Models\Ip::find(\Request::getClientIp());
        $event = Models\Event::closestNearby($visitor_info->lat, $visitor_info->lng);
        $current_regions = iterator_to_array(Models\Region::nearby($visitor_info->lat, $visitor_info->lng, null, null, true));

        $tz_regions = ['America/Los_Angeles' => [], 'America/Denver' => [], 'America/Chicago' => [], 'America/Detroit' => []];
        foreach ($current_regions as $region) {
            $tz_regions[$region->timezone][] = $region;
        }

        return \View::make('volunteer/index', [
            'loaded_batch' => Models\Batch::current(),
            'partner' => $this->getPartner(),
            'tz_regions' => $tz_regions
        ]);
    }

    public function getApplyStaff()
    {
        return \View::make('volunteer/apply', [
            'form' => config('wufoo.staff.form'),
            'partner_field' => config('wufoo.staff.partner_field'),
            'partner' => $this->getPartner()
        ]);
    }
    
    public function getApplyJudge()
    {
        return \View::make('volunteer/apply', [
            'form' => config('wufoo.judge.form'),
            'partner_field' => config('wufoo.judge.partner_field'),
            'partner' => $this->getPartner()
        ]);
    }

    public function getApplyMentor()
    {
        return \View::make('volunteer/apply', [
            'form' => config('wufoo.mentor.form'),
            'partner_field' => config('wufoo.mentor.partner_field'),
            'partner' => $this->getPartner()
        ]);
    }

    protected function getPartner()
    {
        return config('partners.'.\Input::get('partner'));
    }
} 
