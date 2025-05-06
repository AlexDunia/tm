<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.xml file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        // Add static URLs
        $staticUrls = [
            '/',
            '/login',
            '/register',
            '/contact',
            '/about',
            '/checkout',
            '/events',
        ];

        foreach ($staticUrls as $url) {
            $sitemap .= $this->formatUrl(url($url), '1.0', 'daily');
        }

        // Add location-based event URLs
        $locations = [
            'lagos',
            'abuja',
            'port-harcourt',
            'ibadan',
            'enugu',
            'calabar',
            'kaduna',
            'kano',
            'warri',
            'benin',
        ];

        foreach ($locations as $location) {
            $sitemap .= $this->formatUrl(url('/events/' . $location), '0.8', 'daily');
        }

        // Add event category URLs
        $categories = [
            'music',
            'business',
            'arts',
            'sports',
            'entertainment',
            'workshops',
            'tech',
            'food',
            'nightlife',
            'community',
        ];

        foreach ($categories as $category) {
            $sitemap .= $this->formatUrl(url('/category/' . $category), '0.8', 'daily');
        }

        // Add dynamic event URLs from database
        try {
            $events = DB::table('mctlists')
                ->select('id', 'name as title', 'updated_at')
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($events as $event) {
                $lastmod = Carbon::parse($event->updated_at)->toDateString();
                $slug = Str::slug($event->title);
                $sitemap .= $this->formatUrl(
                    url('/event/' . ($slug ?? $event->id)),
                    '0.9',
                    'weekly',
                    $lastmod
                );
            }
        } catch (\Exception $e) {
            $this->error('Error querying events: ' . $e->getMessage());
            // Continue with the rest of the sitemap
        }

        $sitemap .= '</urlset>';

        // Save the sitemap
        File::put(public_path('sitemap.xml'), $sitemap);

        $this->info('Sitemap generated successfully at public/sitemap.xml');
    }

    /**
     * Format a URL entry for the sitemap
     *
     * @param string $url The URL
     * @param string $priority The priority (0.0 to 1.0)
     * @param string $changefreq The change frequency
     * @param string|null $lastmod The last modification date (YYYY-MM-DD)
     * @return string
     */
    private function formatUrl($url, $priority = '0.5', $changefreq = 'weekly', $lastmod = null)
    {
        $entry = '  <url>' . PHP_EOL;
        $entry .= '    <loc>' . $url . '</loc>' . PHP_EOL;

        if ($lastmod) {
            $entry .= '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
        } else {
            $entry .= '    <lastmod>' . Carbon::now()->toDateString() . '</lastmod>' . PHP_EOL;
        }

        $entry .= '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
        $entry .= '    <priority>' . $priority . '</priority>' . PHP_EOL;
        $entry .= '  </url>' . PHP_EOL;

        return $entry;
    }
}
