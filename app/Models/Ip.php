<?php

namespace CodeDay\Models;

class Ip extends RemoteModel{
    public static function find($ip)
    {
        $ip_parts = explode('.', $ip);
        if (($ip_parts[0] == '192' && $ip_parts[1] == '168')
            || $ip_parts[0] == '10'
            || $ip_parts[0] == '127') {
            $ip = '24.143.69.226';
        }

        if (!\Cache::has('ip.telize.'.$ip)) {
            $url = 'http://www.telize.com/geoip/'.urlencode($ip);
            $json = file_get_contents($url);
            $user_data = json_decode($json, true);

            try {
                $user_data['lat'] = $user_data['latitude'];
                $user_data['lng'] = $user_data['longitude'];

                $user_data['location'] = [
                    'lat' => $user_data['lat'],
                    'lng' => $user_data['lng']
                ];
            } catch (\Exception $ex) {
                $user_data = json_decode('{"timezone":"America\/Los_Angeles","isp":"Broadstripe","region_code":"WA","country":"United States","dma_code":"0","area_code":"0","region":"Washington","ip":"24.143.69.226","asn":"AS23292","continent_code":"NA","city":"Kirkland","postal_code":"98033","longitude":-122.1873,"latitude":47.6727,"country_code":"US","country_code3":"USA"}');
            }

            \Cache::put('ip.telize.'.$ip, $user_data, 60 * 24 * 7);
        }

        return new self(\Cache::get('ip.telize.'.$ip));
    }
}