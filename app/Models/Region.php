<?php

namespace CodeDay\Models;

class Region extends ClearModel
{
    public static function find($webname)
    {
        return new self(self::clearGet('/region/'.urlencode($webname)));
    }

    public static function all()
    {
        return array_map(function($region) { return new self($region); }, self::clearGet('/regions'));
    }

    public static function nearby($lat, $lng, $radius = null, $limit = null, $with_current_event = false)
    {
        $query = ['lat' => $lat, 'lng' => $lng];
        if (isset($radius)) {
            $query['radius'] = $radius;
        }
        if (isset($limit)) {
            $query['limit'] = $limit;
        }
        if ($with_current_event) {
            $query['with_current_event'] = '1';
        }

        return array_map(function($nearby) { return new self($nearby); }, self::clearGet('/regions/nearby', $query));
    }

    public function current_event()
    {
        if ($this->remote_data['current_event']) {
            return Event::find($this->remote_data['current_event']['id']);
        } else {
            return;
        }
    }
}
