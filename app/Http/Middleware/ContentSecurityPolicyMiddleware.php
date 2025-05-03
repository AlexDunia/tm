<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicyMiddleware
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
        $response = $next($request);

        // Define CSP directives to restrict content sources
        $cspDirectives = [
            "default-src" => "'self'",
            "script-src" => "'self' 'unsafe-inline' https://js.paystack.co https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.example.com",
            "style-src" => "'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com",
            "img-src" => "'self' data: https: http:",
            "font-src" => "'self' https://fonts.gstatic.com https://cdn.jsdelivr.net",
            "connect-src" => "'self' https://api.paystack.co",
            "frame-src" => "'self' https://js.paystack.co",
            "object-src" => "'none'",
            "base-uri" => "'self'",
            "form-action" => "'self'",
        ];

        // Build CSP header value
        $cspHeader = implode('; ', array_map(
            fn($key, $value) => "$key $value",
            array_keys($cspDirectives),
            $cspDirectives
        ));

        // Add CSP and other security headers
        $response->headers->set('Content-Security-Policy', $cspHeader);
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
