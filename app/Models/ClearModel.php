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

        if (!static::$cache || is_null(json_decode($cacheData))) {
            $cacheData = file_get_contents($url);
            \Cache::put('clearmodel.urlcache.'.hash('md5', $url), $cacheData, 1);
        }
        return json_decode($cacheData, true);
    }

    protected static function clearPost($endpoint, $data = [])
    {
        $data['access_token'] = \Config::get('clear.access_token');
        $data['secret'] = \Config::get('clear.secret');
        $url = \Config::get('clear.api_base').$endpoint;
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data)
            )
        );

        $context  = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return json_decode($result, true);
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
