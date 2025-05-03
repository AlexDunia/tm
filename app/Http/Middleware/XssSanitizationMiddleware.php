<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XssSanitizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip sanitization for specific routes if needed
        $excludedRoutes = [
            // Add route names to exclude if needed
        ];

        // Check if route exists and has a name before checking exclusions
        if ($request->route() !== null && in_array($request->route()->getName(), $excludedRoutes)) {
            return $next($request);
        }

        // Clean XSS from input data
        $input = $request->all();

        // Define which fields should be excluded from sanitization
        $excludedFields = [
            'password',
            'password_confirmation',
            '_token',
            // Add other fields that should not be sanitized
        ];

        array_walk_recursive($input, function (&$item, $key) use ($excludedFields) {
            if (!in_array($key, $excludedFields) && is_string($item)) {
                // Remove script tags
                $item = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $item);

                // Remove on* attributes
                $item = preg_replace('/on\w+="[^"]*"/is', '', $item);
                $item = preg_replace('/on\w+=\'[^\']*\'/is', '', $item);

                // Filter HTML tags
                $item = filter_var($item, FILTER_SANITIZE_STRING);

                // Encode special characters
                $item = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
            }
        });

        // Replace the request input with sanitized data
        $request->merge($input);

        return $next($request);
    }
}
