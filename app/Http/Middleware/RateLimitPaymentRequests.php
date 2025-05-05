<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class RateLimitPaymentRequests
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
        // Get a unique key for this IP address
        $key = 'payment_verify_' . md5($request->ip());

        // Check for failed attempts and implement exponential backoff
        $failedAttempts = Cache::get($key . '_failures', 0);
        if ($failedAttempts > 5) {
            // Calculate exponential backoff time (2^n seconds, max 30 minutes)
            $backoffTime = min(pow(2, $failedAttempts - 5), 1800); // in seconds

            // Get last failure timestamp
            $lastFailureTimestamp = Cache::get($key . '_last_failure', 0);
            $timeSinceFailure = time() - $lastFailureTimestamp;

            if ($timeSinceFailure < $backoffTime) {
                // Too soon since last failure, apply backoff
                $remainingTime = $backoffTime - $timeSinceFailure;

                Log::warning('Payment verification rate limited due to exponential backoff', [
                    'ip' => $request->ip(),
                    'failed_attempts' => $failedAttempts,
                    'backoff_time' => $backoffTime,
                    'remaining_time' => $remainingTime
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Too many attempts. Please try again later.',
                        'retry_after' => $remainingTime
                    ], 429);
                }

                return redirect()->back()->with('error', 'Too many payment attempts. Please try again after ' . ceil($remainingTime / 60) . ' minutes.');
            }
        }

        // Use Laravel's rate limiter - 10 attempts per minute
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);

            Log::warning('Payment verification rate limited', [
                'ip' => $request->ip(),
                'retry_after' => $seconds
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many payment attempts. Please try again later.',
                    'retry_after' => $seconds
                ], 429);
            }

            return redirect()->back()->with('error', 'Too many payment attempts. Please try again after ' . ceil($seconds / 60) . ' minutes.');
        }

        // Increment the rate limiter
        RateLimiter::hit($key, 60); // Key expires after 60 seconds

        // Process the request
        $response = $next($request);

        // Check if request was successful or not
        if ($response->getStatusCode() >= 400) {
            // Record failed attempt for exponential backoff
            $failedAttempts = Cache::increment($key . '_failures', 1);
            Cache::put($key . '_last_failure', time(), 3600); // Store failure time for 1 hour

            // Log failed attempt
            Log::info('Payment verification failed attempt', [
                'ip' => $request->ip(),
                'failed_attempts' => $failedAttempts
            ]);
        } else {
            // On success, reset failure counter
            Cache::forget($key . '_failures');
            Cache::forget($key . '_last_failure');
        }

        return $response;
    }
}
