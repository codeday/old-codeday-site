<?php
namespace CodeDay\Models;

class Region extends ClearModel {
    public static function find($webname)
    {
        return new self(self::clearGet('/region/'.urlencode($webname)));
    }

    public static function all()
    {
        foreach (self::clearGet('/regions') as $event) {
            yield new self($event);
        }
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

        foreach (self::clearGet('/regions/nearby', $query) as $nearby) {
            yield new self($nearby);
        }
    }

    public function current_event()
    {
        if ($this->remote_data['current_event']) {
            return Event::find($this->remote_data['current_event']);
        } else {
            return null;
        }
    }
} 