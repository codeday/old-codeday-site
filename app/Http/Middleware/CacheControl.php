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

        if ($request->is('/splunk')) {
            $public = false;
            $cacheLocal = 0;
            $cacheRemote = 0;
        } elseif ($request->is('*/register')) {
            $cacheLocal = 0;
            $cacheRemote = 30;
        } elseif ($request->path() === '/') {
            $cacheLocal = 3600;
            $cacheRemote = 3600;
        } elseif (preg_match('/^[a-zA-Z-_0-9]+$/', $request->path())) {
            $cacheLocal = 300;
            $cacheRemote = 900;
        }

        return $next($request)
            ->header('Cache-Control', ($public ? 'public' : 'private').', max-age='.$cacheLocal)
            ->header('Surrogate-Control', 'max-age='.$cacheRemote);
    }
}
