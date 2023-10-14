<?php

namespace App\Providers;

use App\Helpers\Audit;
use App\Helpers\DecorateWithCache;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\Writer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
        Carbon::macro('greetings', function () {
            $hour = date('H');
            if ($hour < 12) {
                return "Good Morning, ";
            }
            if ($hour < 17) {
                return "Good Afternoon, ";
            }
            return "Good Evening, ";
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();



        $this->app->singleton('audit', function () {
            return new Audit();
        });

        $this->app->singleton('decorate-with-cache', function () {
            return new DecorateWithCache();
        });
    }
}
