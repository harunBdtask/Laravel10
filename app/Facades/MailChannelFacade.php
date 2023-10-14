<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MailChannelFacade extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'mail-channel';
    }
}
