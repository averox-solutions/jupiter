<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomVerified
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
        if (getSetting('VERIFY_USERS') == 'enabled' && $request->user() && !$request->user()->hasVerifiedEmail()) {
            return redirect('/email/verify');
        }
        
        return $next($request);
    }
}
