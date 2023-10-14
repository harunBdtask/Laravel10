<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class DecorateWithCache
{
    private $key;
    private $value;

    public function as($key): DecorateWithCache
    {
        $this->key = $key;
        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function set(callable $callback)
    {
        $this->value = Cache::rememberForever($this->getKey(), $callback);
        return $this->value;
    }

    public function invalidate()
    {
        Cache::forget($this->getKey());
    }
}
