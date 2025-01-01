<?php

namespace App\Core\Cache;

class Cache
{
    private $cachePath = __DIR__ . '/../../../cache/';

    public function has($key)
    {
        return file_exists($this->cachePath . $key);
    }

    public function get($key)
    {
        return json_decode(file_get_contents($this->cachePath . $key), true);
    }

    public function set($key, $data, $ttl = 3600)
    {
        file_put_contents($this->cachePath . $key, json_encode($data));
    }
}