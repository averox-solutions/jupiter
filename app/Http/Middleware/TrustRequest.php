<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrustRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Str::contains($request->path(), ['install'])) {
            $license_notifications_array = aplVerifyLicense();

            if ($license_notifications_array['notification_case'] != "notification_license_ok") {
                abort(403);
            }
        }

        return $next($request);
    }
}
