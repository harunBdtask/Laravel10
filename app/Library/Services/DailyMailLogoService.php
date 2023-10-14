<?php

namespace App\Library\Services;

use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailConfiguration;

class DailyMailLogoService
{
    public static function getLogo()
    {
        $logo = Session::get('mail_logo_url');
        if (!$logo) {
            $logo = MailConfiguration::query()->first()->logo_url;
            Session::put('mail_logo_url', $logo);
        }
        return $logo;
    }
}
