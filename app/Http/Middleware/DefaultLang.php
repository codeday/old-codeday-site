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
        \session_start();
        if ($_SESSION['lang']) {
            return redirect('/'.$_SESSION['lang'].'/'.request()->path());
        }

        return $next($request);
    }
}
