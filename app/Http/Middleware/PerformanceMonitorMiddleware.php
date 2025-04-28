<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitorMiddleware
{
    /**
     * Handle an incoming request and monitor its performance.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Record start time
        $startTime = microtime(true);
        
        // Process the request
        $response = $next($request);
        
        // Record end time and calculate execution time
        $executionTime = microtime(true) - $startTime;
        
        // Log if execution takes longer than 1 second
        if ($executionTime > 1.0) {
            $currentRoute = Route::current();
            $routeName = $currentRoute ? $currentRoute->getName() : 'unknown';
            
            Log::channel('performance')->warning("Slow request detected", [
                'route' => $routeName,
                'path' => $request->path(),
                'method' => $request->method(),
                'execution_time' => round($executionTime, 4) . 's',
                'user_id' => $request->user() ? $request->user()->id : 'guest',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            // Add execution time header if in debug mode
            if (config('app.debug')) {
                $response->headers->set('X-Execution-Time', round($executionTime, 4) . 's');
            }
        }
        
        return $response;
    }
}
