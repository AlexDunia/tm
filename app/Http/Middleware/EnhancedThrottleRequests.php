<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Middleware\ThrottleRequests;

class EnhancedThrottleRequests extends ThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|string  $maxAttempts
     * @param  float|int  $decayMinutes
     * @param  string  $prefix
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        // Enhanced security: also check X-Forwarded-For header
        $clientIp = $request->getClientIp();
        $xForwardedFor = $request->header('X-Forwarded-For');

        // Track failed attempts with details
        if ($this->shouldTrackAttempt($request)) {
            Log::info('Rate-limited request attempt', [
                'ip' => $clientIp,
                'x_forwarded_for' => $xForwardedFor,
                'path' => $request->path(),
                'user_agent' => $request->userAgent()
            ]);
        }

        // Use parent middleware implementation
        return parent::handle($request, $next, $maxAttempts, $decayMinutes, $prefix);
    }

    /**
     * Determine if the request should be tracked as a rate-limited attempt
     */
    protected function shouldTrackAttempt(Request $request): bool
    {
        // For login and registration pages
        if ($request->is('login') || $request->is('signup') ||
            $request->is('authenticated') || $request->is('createnewadmin')) {
            return true;
        }

        return false;
    }
}
