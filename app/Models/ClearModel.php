<?php
namespace CodeDay\Models;

abstract class ClearModel extends RemoteModel {

    protected static function clearGet($endpoint, $data = [])
    {
        $data['access_token'] = \Config::get('clear.access_token');
        $data['secret'] = \Config::get('clear.secret');

        $url = \Config::get('clear.api_base').$endpoint.'?'.http_build_query($data);

        if (!\Cache::has('clearmodel.urlcache.'.base64_encode($url))) {
            $contents = file_get_contents($url);
            \Cache::put('clearmodel.urlcache.'.base64_encode($url), $contents, 3);
        }
        return json_decode(\Cache::get('clearmodel.urlcache.'.base64_encode($url)), true);
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