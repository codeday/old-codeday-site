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

    public function photos()
    {
        if (!\Cache::has('flickr.'.$this->hashtag) || \Config::get('app.debug')) {
            try {
                $query = [
                    'api_key' => \Config::get('flickr.key'),
                    'group_id' => \Config::get('flickr.group'),
                    'tags' => $this->hashtag,
                    'format' => 'json',
                    'nojsoncallback' => 1,
                    'method' => 'flickr.groups.pools.getPhotos',
                    'per_page' => 10
                ];
                $url = 'https://api.flickr.com/services/rest/?'.http_build_query($query);
                $response = file_get_contents($url);
                $obj = json_decode($response)->photos->photo;

                \Cache::put('flickr.'.$this->hashtag, $obj, 60);
            } catch (\Exception $ex) {}
        }

        return \Cache::get('flickr.'.$this->hashtag, []);
    }
} 
