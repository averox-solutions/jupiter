<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPaymentMode
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
        if (getSetting('AUTH_MODE') == 'enabled' && getSetting('PAYMENT_MODE') == 'enabled' && count(paymentGateways()) != 0) {
            return $next($request);
        }
        
        return redirect('/');
    }
}
