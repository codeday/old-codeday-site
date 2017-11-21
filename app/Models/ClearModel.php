<?php
namespace CodeDay\Models;

abstract class ClearModel extends RemoteModel {
    protected static $cache = true;
    protected static function clearGet($endpoint, $data = [])
    {
        $data['access_token'] = \Config::get('clear.access_token');
        $data['secret'] = \Config::get('clear.secret');

        $url = \Config::get('clear.api_base').$endpoint.'?'.http_build_query($data);
        $cacheData = \Cache::get('clearmodel.urlcache.'.hash('md5', $url));

        if (!static::$cache || is_null(json_decode($cacheData)) || \config('app.debug')) {
            $cacheData = file_get_contents($url);
            \Cache::put('clearmodel.urlcache.'.hash('md5', $url), $cacheData, 3);
        }
        return json_decode($cacheData, true);
    }

    protected static function clearPost($endpoint, $data = [])
    {
        $data['access_token'] = \Config::get('clear.access_token');
        $data['secret'] = \Config::get('clear.secret');
        $url = \Config::get('clear.api_base').$endpoint;
        
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    public function __isset($key) {

        return method_exists($this, $key) || isset($this->remote_data[$key]);
    }

    public function __get($key)
    {
        if (method_exists($this, $key)) {
            return $this->$key();
        } else {
            return $this->remote_data[$key];
        }
    }
}
