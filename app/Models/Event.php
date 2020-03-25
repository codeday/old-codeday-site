<?php

namespace CodeDay\Models;

class Event extends ClearModel
{
    public static function find($eventID)
    {
        return new self(self::clearGet('/event/'.urlencode($eventID)));
    }

    public static function all()
    {
        return array_map(function($event) { return new self($event); }, self::clearGet('/events/'));
    }

    public static function closestNearby($lat, $lng)
    {
        $closest_region = Region::nearby($lat, $lng, null, 1, true);
        if (count($closest_region) > 0) {
            if (defined('HHVM_VERSION')) {
                $closest_region->next();
            }

            return $closest_region[0]->current_event;
        } else {
            return;
        }
    }

    public function teams()
    {
        if (!\Cache::has('teams.'.$this->webname) || \Config::get('app.debug')) {
            try {
                $response = file_get_contents('https://showcase.codeday.org/api/event/'.$this->webname);
                $obj = json_decode($response);
                \Cache::put('teams.'.$this->webname, $obj, 600);
            } catch (\Exception $ex) {
            }
        }

        return \Cache::get('teams.'.$this->webname, []);
    }

    public function photos()
    {
        if (!\Cache::has('showcase.photo.'.$this->webname) || \Config::get('app.debug')) {
            try {
                $response = file_get_contents(\config('showcase.api_base').'/api/region/'.$this->region_id.'/photos.json?random=10');
                $obj = json_decode($response);

                \Cache::put('showcase.photo.'.$this->webname, $obj, 600);
            } catch (\Exception $ex) {
            }
        }

        return \Cache::get('showcase.photo.'.$this->webname, []);
    }
}
