<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UtitlityController;
use Closure;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class DashboardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        switch (getDashboardVersion()) {
            case User::MC_INVENTORY_DASHBOARD:
                return redirect('/mc-inventory/machine-dashboard');
                break;
            case User::USER_DASHBOARD:
                return redirect('/welcome');
                break;
            case User::APPROVED_DASHBOARD:
                return redirect('/dashboard-approved');
            case User::GARMENTS_DASHBOARD:
                return $next($request);
                break;
            default:
                return $next($request);
                break;
        }
        return $next($request);
    }
}
