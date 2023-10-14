<?php

namespace App\Providers;

use App\MailChannels\MailChannel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use SkylarkSoft\GoRMG\SystemSettings\Models\MailConfiguration;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app->singleton('mail-channel', function () {
            return new MailChannel();
        });


        if (Schema::hasTable('mail_configurations')) {
            $mailChannel = Cache::rememberForever('mail_config', function () {
                return MailConfiguration::query()->first();
            });
            if ($mailChannel) {
                $config = [
                    'driver' => $mailChannel->driver,
                    'transport' => $mailChannel->driver,
                    'host' => $mailChannel->host,
                    'port' => $mailChannel->port,
                    'username' => $mailChannel->username,
                    'password' => $mailChannel->password,
                    'encryption' => $mailChannel->encryption ?? 'tls',
                    'sending_time' => $mailChannel->sending_time ?? '19:00',
                    'from' => [
                        'address' => $mailChannel->from_address,
                        'name' => $mailChannel->from_name
                    ],
                ];
                Config::set('mail.mailers.mail_channel', $config);
            }
        }
    }
}
