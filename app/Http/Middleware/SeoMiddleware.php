<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Helpers\SeoHelper;
use Symfony\Component\HttpFoundation\Response;

class SeoMiddleware
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
        // Get the route name and path
        $routeName = $request->route() ? $request->route()->getName() : null;
        $path = $request->path();

        // Default meta information
        $meta = [];

        // Determine what page we're on and set appropriate meta info
        if ($path === '/' || $path === '') {
            // Home page
            $meta = SeoHelper::prepareMeta([
                'pageTitle' => 'Event Tickets',
                'description' => 'Find and book tickets for events, concerts, and more. KakaWorld is your premier destination for event tickets in Nigeria.',
            ]);
        } elseif (strpos($path, 'events') === 0) {
            // Event listing pages
            $location = $request->segment(2) ?? null;

            if ($location) {
                $meta = SeoHelper::prepareMeta([
                    'pageTitle' => 'Events',
                    'location' => $location,
                    'description' => "Discover upcoming events in {$location}. Find and book event tickets on KakaWorld.",
                ]);
            } else {
                $meta = SeoHelper::prepareMeta([
                    'pageTitle' => 'All Events',
                    'description' => 'Browse all upcoming events. Find and book your tickets on KakaWorld.',
                ]);
            }
        } elseif (strpos($path, 'event/') === 0) {
            // Individual event page
            // For this route, we'll allow the controller to handle meta tags
            // since we need specific event data
        } elseif (strpos($path, 'login') === 0) {
            $meta = SeoHelper::prepareMeta([
                'pageTitle' => 'Login',
                'description' => 'Login to your KakaWorld account to manage your event tickets and bookings.',
            ]);
        } elseif (strpos($path, 'register') === 0) {
            $meta = SeoHelper::prepareMeta([
                'pageTitle' => 'Register',
                'description' => 'Create a KakaWorld account to easily book and manage your event tickets.',
            ]);
        } elseif (strpos($path, 'checkout') === 0) {
            $meta = SeoHelper::prepareMeta([
                'pageTitle' => 'Checkout',
                'description' => 'Secure checkout for your event tickets on KakaWorld.',
                'type' => 'website',
            ]);
        } elseif (strpos($path, 'contact') === 0) {
            $meta = SeoHelper::prepareMeta([
                'pageTitle' => 'Contact Us',
                'description' => 'Get in touch with KakaWorld customer support for any questions about event tickets.',
            ]);
        } elseif (strpos($path, 'about') === 0) {
            $meta = SeoHelper::prepareMeta([
                'pageTitle' => 'About Us',
                'description' => 'Learn about KakaWorld, Nigeria\'s leading event ticket platform.',
            ]);
        } else {
            // Default for all other pages - will use the layout default
            $meta = SeoHelper::prepareMeta([
                'pageTitle' => ucfirst($path),
            ]);
        }

        // Share meta data with all views
        View::share($meta);

        return $next($request);
    }
}
