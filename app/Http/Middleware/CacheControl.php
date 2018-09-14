<?php

namespace CodeDay\Http\Middleware;

use Closure;

class CacheControl
{
    public function handle($request, Closure $next)
    {
        return $next($request)
            ->header('Cache-Control', 'max-age='.(60*2))
            ->header('Surrogate-Control', 'max-age='.(60*5));
    }
}
