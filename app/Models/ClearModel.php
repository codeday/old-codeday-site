<?php
namespace CodeDay\Models;

abstract class ClearModel extends RemoteModel {

    protected static function clearGet($endpoint, $data = [])
    {
        $data['access_token'] = \Config::get('clear.access_token');
        $data['secret'] = \Config::get('clear.secret');

        $url = \Config::get('clear.api_base').$endpoint.'?'.http_build_query($data);

        if (!\Cache::has('clearmodel.urlcache.'.hash('md5', $url))) {
            $contents = file_get_contents($url);
            \Cache::put('clearmodel.urlcache.'.hash('md5', $url), $contents, 1);
        }
        return json_decode(\Cache::get('clearmodel.urlcache.'.hash('md5', $url)), true);
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