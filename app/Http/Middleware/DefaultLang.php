<?php

namespace CodeDay\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DefaultLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (session('lang') && substr(request()->path(), 0, strlen(session('lang'))) !== session('lang')) {
            return redirect('/'.session('lang').'/'.request()->path());
        }

        return $next($request);
    }
}
