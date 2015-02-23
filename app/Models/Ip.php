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

        if (!\Cache::has('ip.maxmind.'.$ip)) {
            try {
                $url = 'https://geoip.maxmind.com/geoip/v2.1/city/'.urlencode($ip);
                $username = \Config::get('maxmind.user_id');
                $password = \Config::get('maxmind.license_key');

                $context = stream_context_create(array(
                    'http' => array(
                        'header'  => "Authorization: Basic " . base64_encode("$username:$password")
                    )
                ));

                $json = @file_get_contents($url, false, $context);
                $user_data = @json_decode($json, true);


                if (!isset($user_data['location']['latitude']) || !isset($user_data['location']['longitude'])) {
                    throw new \Exception();
                }

                $user_data = $user_data['location'];

                $user_data['lat'] = $user_data['latitude'];
                $user_data['lng'] = $user_data['longitude'];

                $user_data['location'] = [
                    'lat' => $user_data['lat'],
                    'lng' => $user_data['lng']
                ];
            } catch (\Exception $ex) {
                return (object)[
                    'location' => [
                        'lat' => 47,
                        'lng' => -122
                    ],
                    'latitude' => 47,
                    'longitude' => -122,
                    'lat' => 47,
                    'lng' => -122
                ];
            }

            \Cache::put('ip.maxmind.'.$ip, $user_data, 60 * 24 * 7);
        }

        return new self(\Cache::get('ip.maxmind.'.$ip));
    }
}
