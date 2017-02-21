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
        if (!\Cache::has('flickr.'.$this->webname) || \Config::get('app.debug')) {
            try {
                $query = [
                    'api_key' => \Config::get('flickr.key'),
                    'group_id' => \Config::get('flickr.group'),
                    'tags' => $this->webname,
                    'format' => 'json',
                    'nojsoncallback' => 1,
                    'method' => 'flickr.groups.pools.getPhotos',
                    'extras' => 'tags,o_dims,o_width,o_height,url_o',
                    'per_page' => 200
                ];
                $url = 'https://api.flickr.com/services/rest/?'.http_build_query($query);
                $response = file_get_contents($url);
                $obj = json_decode($response)->photos->photo;

                \Cache::put('flickr.'.$this->webname, $obj, 60);
            } catch (\Exception $ex) {}
        }

        return \Cache::get('flickr.'.$this->webname, []);
    }

    public function photosFeatured()
    {
        $all = $this->photos();
        $bestOfPhotos = array_filter($all, function($x) { return in_array('bestof', explode(' ', $x->tags)); });
        
        if (count($bestOfPhotos) < 3) {
            $bestOfPhotos = $all;
        }


        $bestOfPhotos = array_filter($bestOfPhotos, function($x) { return isset($x->width_o) && isset($x->height_o) && $x->width_o > $x->height_o; });

        foreach ($bestOfPhotos as $photo) { // More recent photos will be sorted closer to the top
            $recencyFactor = 0.2;
            $entropy = 10;
            $photo->sort = intval(((time() - $photo->dateadded)/60*60*24*(1/$recencyFactor)) * rand(0,$entropy));
        }

        usort($bestOfPhotos, function($x, $y) { return $x->sort - $y->sort; });

        return $bestOfPhotos;
    }
} 
