<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Cache\RateLimiting\Limit;
use Symfony\Component\HttpFoundation\Response;

class EnhancedRateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $maxAttempts
     * @param  int  $decayMinutes
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 5, $decayMinutes = 1): Response
    {
        // Create a unique key for each IP and route combination
        $routeName = ($request->route()) ? $request->route()->getName() : null;
        $key = 'limit:' . $request->ip() . ':' . ($routeName ?? $request->path());

        // If authenticated, include user ID in key to have per-user limits
        if (Auth::check()) {
            $key .= ':' . Auth::id();
        }

        // Check if the request should be rate limited
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            // Calculate time until next attempt is allowed
            $seconds = RateLimiter::availableIn($key);

            // Return 429 Too Many Requests response
            return response()->json([
                'error' => 'Too many requests. Please try again in ' . $seconds . ' seconds.',
                'retry_after' => $seconds
            ], 429)->header('Retry-After', $seconds);
        }

        // Increment attempt counter
        RateLimiter::hit($key, $decayMinutes * 60);

        // Proceed with the request
        $response = $next($request);

        // Add headers to response showing rate limit information
        $response->headers->set('X-RateLimit-Limit', $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', $maxAttempts - RateLimiter::attempts($key));

        return $response;
    }
}
