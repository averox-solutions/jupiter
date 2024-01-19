<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class Localization
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
            $locale = session('locale') ?? getDefaultLanguage()->code;
            App::setLocale($locale);
        }
        
        return $next($request);
    }
}
