<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DecorateWithCacheFacade extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'decorate-with-cache';
    }
}
