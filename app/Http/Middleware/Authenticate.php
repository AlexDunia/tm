<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Public routes that should bypass authentication
     */
    protected $publicRoutes = [
        'success',
        'verifypayment/*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        // Check if current route is in the public routes list
        $currentRoute = $request->route()->getName();

        // Also check URL path for wildcard matches
        $path = $request->path();

        foreach ($this->publicRoutes as $publicRoute) {
            // Direct route name match
            if ($currentRoute === $publicRoute) {
                return $next($request);
            }

            // Handle wildcards in routes (e.g., 'verifypayment/*')
            if (str_ends_with($publicRoute, '/*')) {
                $prefix = str_replace('/*', '', $publicRoute);
                if (str_starts_with($path, $prefix)) {
                    return $next($request);
                }
            }
        }

        // Continue with standard authentication
        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('logg');
    }
}
