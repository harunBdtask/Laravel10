<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AuditFacade extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'audit';
    }
}
