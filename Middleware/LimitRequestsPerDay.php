<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class LimitRequestsPerDay
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $dailyRequestLimit = 10;
        $cacheKey = 'request_count:' . date('Y-m-d') . ':' . $ip;
        $requestsCount = 1;
        if (!Cache::has($cacheKey)) {
            Cache::put($cacheKey, 1, 24 * 60);
            return $next($request);
        }
        else{
            $requestsCount = Cache::increment($cacheKey, 1);
        }

        if ($requestsCount > $dailyRequestLimit) {
            toastr()->error('Insertion journaliere atteint.');
            return redirect()->back();
        }

        return $next($request);
    }
}
