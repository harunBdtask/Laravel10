<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\Validation;

class ValidationServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Library\Services\Validation', function ($app) {
            return new Validation();
          });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
