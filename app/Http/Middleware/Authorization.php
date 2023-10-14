<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\PageWiseViewPermission;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $roleCheck = in_array(getRole(), ['super-admin', 'admin']);
        $permissionCheck = session()->has($permission);
        $viewPermission = PageWiseViewPermission::query()->where('view_id', $permission)
            ->where('user_id', Auth::id())
            ->exists();
        if ($roleCheck || $permissionCheck || $viewPermission) {
            return $next($request);
        }
        return abort(403);
    }
}
