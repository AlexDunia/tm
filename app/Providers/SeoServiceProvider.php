<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load SEO helpers
        require_once app_path('Helpers/SeoHelper.php');
    }
}
