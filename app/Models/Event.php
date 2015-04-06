<?php
namespace CodeDay\Models;

class Event extends ClearModel {
    public static function find($eventID)
    {
        return new self(self::clearGet('/event/'.urlencode($eventID)));
    }

    public static function all()
    {
        foreach (self::clearGet('/events/') as $event) {
            yield new self($event);
        }
    }

    public static function closestNearby($lat, $lng)
    {
        $closest_region = Region::nearby($lat, $lng, null, 1, true);
        if (count($closest_region) > 0) {
            if (defined('HHVM_VERSION')) {
                $closest_region->next();
            }
            return $closest_region->current()->current_event;
        } else {
            return null;
        }
    }
} 