<?php
namespace CodeDay\Models;

abstract class RemoteModel {
    protected $remote_data;

    protected function __construct($remote_data)
    {
        $this->remote_data = $remote_data;
    }

    public function __isset($key) {
        return isset($this->remote_data[$key]);
    }

    public function __get($key)
    {
        return $this->remote_data[$key];
    }
} 