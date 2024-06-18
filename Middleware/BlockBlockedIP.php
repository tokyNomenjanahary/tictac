<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class BlockBlockedIP
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
      
        if (Cache::has('blocked_ip:' . $ip)) {
            // return response()->json(['message' => 'Your IP is blocked. Please try again later.'], 403);
            throw new ThrottleRequestsException(
                'Too Many Requests.',
                null,
                Response::HTTP_TOO_MANY_REQUESTS,
                ['Retry-After' => 1440 * 60] // Set the Retry-After header to 24 hours (1440 minutes)
            );
        }

        return $next($request);
    }
}
