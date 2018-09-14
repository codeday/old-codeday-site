<?php

namespace CodeDay\Http\Middleware;

use Closure;

class CacheControl
{
    public function handle($request, Closure $next)
    {
        $cacheLocal = 0;
        $cacheRemote = 0;
        $public = true;

        if ($request->is('splunk')) {
            $public = false;
            $cacheLocal = 0;
            $cacheRemote = 0;
        } elseif ($request->is('*/register')) {
            $cacheLocal = 0;
            $cacheRemote = 30;
        } elseif ($request->is('seattle')) {
            $cacheLocal = 300;
            $cacheRemote = 900;
        } elseif ($request->is('')) {
            $cacheLocal = 1800;
            $cacheRemote = 1800;
        }

        return $next($request)
            ->header('Cache-Control', ($public ? 'public' : 'private').', max-age='.$cacheLocal)
            ->header('Surrogate-Control', 'max-age='.$cacheRemote);
    }
}
