<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class ContextAwareSessionMiddleware
{
    /**
     * Map of route patterns to custom session timeout values (in minutes)
     */
    protected $contextTimeouts = [
        // Standard browsing - longer timeout
        'home' => 120,
        '/' => 120,
        'events/*' => 120,
        'category/*' => 120,
        'search' => 120,

        // Account management - medium timeout
        'login' => 60,
        'signup' => 60,
        'forgotpassword' => 60,
        'resetpassword/*' => 60,

        // Sensitive areas - shorter timeout
        'checkout' => 15,
        'cart' => 30,
        'payment' => 15,
        'success' => 15,
        'verify-reference' => 15,
        'verifypayment/*' => 15,
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Update the last activity timestamp on each request
        Session::put('last_activity_time', time());

        // Set context-aware timeout based on the current route
        $path = $request->path();
        $timeout = $this->getTimeoutForPath($path);

        // Store the context timeout in the session
        Session::put('context_timeout', $timeout);
        Session::put('context_timeout_expires', time() + ($timeout * 60));

        $response = $next($request);

        // If this is an AJAX request to keep the session alive, respond accordingly
        if ($request->ajax() && $request->has('session_refresh')) {
            return response()->json([
                'success' => true,
                'timeout' => $timeout,
                'expires' => Session::get('context_timeout_expires'),
                'remaining' => Session::get('context_timeout_expires') - time()
            ]);
        }

        return $response;
    }

    /**
     * Get the appropriate timeout for the current path
     */
    protected function getTimeoutForPath(string $path): int
    {
        // Default to the system's session lifetime
        $defaultTimeout = config('session.lifetime', 120);

        foreach ($this->contextTimeouts as $pattern => $timeout) {
            // Direct path match
            if ($path === $pattern) {
                return $timeout;
            }

            // Wildcard matching (e.g., 'events/*')
            if (str_ends_with($pattern, '/*')) {
                $prefix = str_replace('/*', '', $pattern);
                if (str_starts_with($path, $prefix)) {
                    return $timeout;
                }
            }
        }

        return $defaultTimeout;
    }
}
