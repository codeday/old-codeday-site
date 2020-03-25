<?php

namespace CodeDay\Http\Controllers;

use CodeDay\Models;

class PhoneController extends Controller
{
    private function getEventsByTimezone()
    {
        $current_regions = Models\Region::nearby(0, 0, null, null, true);
        $tz_regions = ['America/Los_Angeles' => [], 'America/Denver' => [], 'America/Chicago' => [], 'America/Detroit' => []];
        foreach ($current_regions as $region) {
            $tz_regions[$region->timezone][] = $region;
        }

        return $tz_regions;
    }

    public function getIndex()
    {
        $xml = '<Response>';
        $xml .= '<Gather numDigits="1" action="/phone/region" method="GET">';
        $xml .= '<Play>/assets/mp3/phonetimezones.mp3</Play>';
        $xml .= '<Pause length="4" />';
        $xml .= '<Play>/assets/mp3/phonetimezones.mp3</Play>';
        $xml .= '</Gather>';
        $xml .= '</Response>';

        $response = \Response::make($xml, 200);
        $response->header('Content-type', 'text/xml');

        return $response;
    }

    public function getRegion()
    {
        $region_index = \Input::get('Digits');

        if (!$region_index || $region_index > 4) {
            $response = \Response::make('<Response><Say>Sorry, that region does not exist.</Say></Response>', 200);
            $response->header('Content-type', 'text/xml');

            return $response;
        }

        $regions = [
            'America/Los_Angeles',
            'America/Denver',
            'America/Chicago',
            'America/Detroit',
        ];
        $region = $this->getEventsByTimezone()[$regions[$region_index - 1]];

        $xml = '<Response>';
        $xml .= '<Gather numDigits="1" action="/phone/event?region='.$region_index.'" method="GET">';

        $i = 0;
        foreach ($region as $event) {
            $i++;
            $xml .= '<Play>/assets/mp3/phonespeakto.mp3</Play>';
            $xml .= '<Say>'.$event->name.'</Say>';
            $xml .= '<Play>/assets/mp3/phonepress.mp3</Play>';
            $xml .= '<Say>'.$i.'</Say>';
            $xml .= '<Pause length="1" />';
        }

        $xml .= '</Gather>';
        $xml .= '</Response>';

        $response = \Response::make($xml, 200);
        $response->header('Content-type', 'text/xml');

        return $response;
    }

    public function getEvent()
    {
        $region_index = \Input::get('region');
        $event_index = \Input::get('Digits');

        if (!$event_index) {
            $response = \Response::make('<Response><Say>Sorry, that event does not exist.</Say></Response>', 200);
            $response->header('Content-type', 'text/xml');

            return $response;
        }

        $regions = [
            'America/Los_Angeles',
            'America/Denver',
            'America/Chicago',
            'America/Detroit',
        ];
        $region = $this->getEventsByTimezone()[$regions[$region_index - 1]];
        $event = $region[$event_index - 1]->current_event;

        $xml = '<Response>';
        $xml .= '<Dial>'.(strpos($event->emergency_phone, '@') !== false ? ('<Sip>'.$event->emergency_phone.'</Sip>') : $event->emergency_phone).'</Dial>';
        $xml .= '</Response>';

        $response = \Response::make($xml, 200);
        $response->header('Content-type', 'text/xml');

        return $response;
    }
}
