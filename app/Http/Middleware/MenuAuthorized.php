<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\ApplicationMenuActiveData;

class MenuAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $inactive_urls = getInactiveUrlsData() ?? null;
        if ($inactive_urls && \is_array($inactive_urls) && count($inactive_urls) && \in_array($request->path(), $inactive_urls)) {
            return \redirect('/dashboard');
        }
        return $next($request);
    }
}
