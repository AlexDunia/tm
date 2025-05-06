<?php

namespace App\Helpers;

class SeoHelper
{
    /**
     * Generate SEO-friendly title for a page
     *
     * @param string|null $pageTitle The specific page title
     * @param string|null $eventName Event name if applicable
     * @param string|null $location Location if applicable
     * @param bool $includeCompanyName Whether to include company name
     * @return string
     */
    public static function generateTitle($pageTitle = null, $eventName = null, $location = null, $includeCompanyName = true)
    {
        $title = [];

        // Add specific page title if available
        if ($pageTitle) {
            $title[] = $pageTitle;
        }

        // Add event name if available
        if ($eventName) {
            $title[] = $eventName;
        }

        // Add location if available
        if ($location) {
            $title[] = "in {$location}";
        }

        // Add company name
        if ($includeCompanyName) {
            $title[] = "| KakaWorld";
        }

        // Combine with separators
        return implode(' ', $title);
    }

    /**
     * Generate meta description
     *
     * @param string|null $eventName Event name
     * @param string|null $location Location
     * @param string|null $date Date
     * @param string|null $description Custom description
     * @return string
     */
    public static function generateDescription($eventName = null, $location = null, $date = null, $description = null)
    {
        if ($description) {
            return $description;
        }

        $metaDescription = "Find tickets for events";

        if ($eventName) {
            $metaDescription = "Get tickets for {$eventName}";

            if ($location) {
                $metaDescription .= " in {$location}";
            }

            if ($date) {
                $metaDescription .= " on {$date}";
            }

            $metaDescription .= ". Book your tickets on KakaWorld - the best platform for event tickets.";
        } else {
            $metaDescription .= ", concerts, and more on KakaWorld. Find and book your next unforgettable experience today.";
        }

        return $metaDescription;
    }

    /**
     * Prepare an array of all SEO meta tags
     *
     * @param array $params Associative array of parameters
     * @return array
     */
    public static function prepareMeta(array $params = [])
    {
        // Default values
        $defaults = [
            'pageTitle' => null,
            'eventName' => null,
            'location' => null,
            'date' => null,
            'description' => null,
            'imageUrl' => null,
            'includeCompanyName' => true,
            'canonicalUrl' => null,
            'type' => 'website'
        ];

        // Merge provided params with defaults
        $params = array_merge($defaults, $params);

        // Generate title and description
        $title = self::generateTitle(
            $params['pageTitle'],
            $params['eventName'],
            $params['location'],
            $params['includeCompanyName']
        );

        $description = self::generateDescription(
            $params['eventName'],
            $params['location'],
            $params['date'],
            $params['description']
        );

        // Prepare meta array
        return [
            'metaTitle' => $title,
            'metaDescription' => $description,
            'metaImage' => $params['imageUrl'] ?? asset('images/default-share.jpg'),
            'metaType' => $params['type'],
            'canonicalUrl' => $params['canonicalUrl'] ?? url()->current(),
        ];
    }
}
