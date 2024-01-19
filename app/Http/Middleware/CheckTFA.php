<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckTFA
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
        if (auth()->user() && auth()->user()->tfa == 'active' && !Session::has('user_tfa')) {
            //prevent code from being sent multiple times
            if (!Session::has('user_tfa_sent')) {
                auth()->user()->generateCode();
                Session::put('user_tfa_sent', auth()->user()->id);
            }

            return redirect()->route('tfa.index');
        }
        
        return $next($request);
    }
}
