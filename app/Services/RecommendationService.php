<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\mctlists;
use Illuminate\Support\Facades\Cache;

class RecommendationService
{
    /**
     * Get event recommendations for a user based on their transaction history
     *
     * @param int|null $userId User ID for registered users, null for guests
     * @param string|null $email Email for guest users
     * @param int $limit Number of recommendations to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecommendationsForUser($userId = null, $email = null, $limit = 6)
    {
        // Use cache for performance if user is registered
        if ($userId) {
            return Cache::remember("user_{$userId}_recommendations", 60 * 24, function () use ($userId, $limit) {
                return $this->generateRecommendations($userId, null, $limit);
            });
        } elseif ($email) {
            // For guest users with email
            return $this->generateRecommendations(null, $email, $limit);
        } else {
            // Default to trending events for completely new users
            return $this->getTrendingEvents($limit);
        }
    }

    /**
     * Generate recommendations based on user's transaction history
     *
     * @param int|null $userId
     * @param string|null $email
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function generateRecommendations($userId = null, $email = null, $limit = 6)
    {
        // Get user's transaction history
        $query = Transaction::where('status', 'success');

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($email) {
            $query->where('email', $email);
        } else {
            return $this->getTrendingEvents($limit);
        }

        $userTransactions = $query->get();

        // If no transactions, return trending events
        if ($userTransactions->isEmpty()) {
            return $this->getTrendingEvents($limit);
        }

        // Get event names from transactions
        $purchasedEventNames = $userTransactions->pluck('eventname')->toArray();

        // Get the event records with categories
        $purchasedEvents = mctlists::whereIn('name', $purchasedEventNames)->get();

        // If no events found with matching names, return trending events
        if ($purchasedEvents->isEmpty()) {
            return $this->getTrendingEvents($limit);
        }

        // Get categories with weights based on purchase frequency
        $categoryWeights = [];
        foreach ($purchasedEvents as $event) {
            if (!$event->category) continue;

            if (!isset($categoryWeights[$event->category])) {
                $categoryWeights[$event->category] = 0;
            }
            $categoryWeights[$event->category]++;
        }

        // If no valid categories found, return trending events
        if (empty($categoryWeights)) {
            return $this->getTrendingEvents($limit);
        }

        // Sort by weight to prioritize frequently purchased categories
        arsort($categoryWeights);

        // Get recommendations with limit per category based on weights
        $recommendations = collect();
        $totalRecommendations = 0;

        foreach ($categoryWeights as $category => $weight) {
            // Calculate how many recommendations to get from this category
            $categoryLimit = min(ceil($limit * ($weight / array_sum($categoryWeights))), $limit - $totalRecommendations);

            if ($categoryLimit <= 0) continue;

            $eventsInCategory = mctlists::where('category', $category)
                ->whereNotIn('name', $purchasedEventNames)
                ->where('date', '>=', now()->format('Y-m-d')) // Only future events
                ->orderBy('date', 'asc')
                ->take($categoryLimit)
                ->get();

            $recommendations = $recommendations->merge($eventsInCategory);
            $totalRecommendations += $eventsInCategory->count();

            // Break if we have enough recommendations
            if ($totalRecommendations >= $limit) break;
        }

        // If we still need more recommendations, get some from other categories
        if ($totalRecommendations < $limit) {
            $additionalRecommendations = mctlists::whereNotIn('category', array_keys($categoryWeights))
                ->whereNotIn('name', $purchasedEventNames)
                ->where('date', '>=', now()->format('Y-m-d'))
                ->orderBy('date', 'asc')
                ->take($limit - $totalRecommendations)
                ->get();

            $recommendations = $recommendations->merge($additionalRecommendations);
        }

        return $recommendations->take($limit);
    }

    /**
     * Get trending events based on recent transactions
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTrendingEvents($limit = 6)
    {
        return Cache::remember('trending_events', 60 * 12, function () use ($limit) {
            // Get frequently purchased events in the last 30 days
            $popularEventNames = Transaction::where('status', 'success')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('eventname')
                ->selectRaw('eventname, COUNT(*) as purchase_count')
                ->orderByRaw('COUNT(*) DESC')
                ->limit($limit * 2) // Get more than needed in case some events are not found
                ->pluck('eventname')
                ->toArray();

            $trendingEvents = mctlists::whereIn('name', $popularEventNames)
                ->where('date', '>=', now()->format('Y-m-d'))
                ->orderBy('date', 'asc')
                ->take($limit)
                ->get();

            // If not enough trending events, get some upcoming events
            if ($trendingEvents->count() < $limit) {
                $additionalEvents = mctlists::whereNotIn('name', $popularEventNames)
                    ->where('date', '>=', now()->format('Y-m-d'))
                    ->orderBy('date', 'asc')
                    ->take($limit - $trendingEvents->count())
                    ->get();

                $trendingEvents = $trendingEvents->merge($additionalEvents);
            }

            return $trendingEvents;
        });
    }

    /**
     * Get recommendations for event detail page based on current event
     *
     * @param mctlists $event Current event
     * @param int $limit Number of recommendations to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSimilarEvents(mctlists $event, $limit = 4)
    {
        return Cache::remember("similar_events_{$event->id}", 60 * 24, function () use ($event, $limit) {
            // Find events in the same category
            $sameCategory = mctlists::where('id', '!=', $event->id)
                ->where('category', $event->category)
                ->where('date', '>=', now()->format('Y-m-d'))
                ->orderBy('date', 'asc')
                ->take($limit)
                ->get();

            // If not enough events in the same category, add some from other categories
            if ($sameCategory->count() < $limit) {
                $additionalEvents = mctlists::where('id', '!=', $event->id)
                    ->where('category', '!=', $event->category)
                    ->where('date', '>=', now()->format('Y-m-d'))
                    ->orderBy('date', 'asc')
                    ->take($limit - $sameCategory->count())
                    ->get();

                $sameCategory = $sameCategory->merge($additionalEvents);
            }

            return $sameCategory;
        });
    }

    /**
     * Update transaction table with event_id field
     * This is a maintenance task to improve recommendation performance
     *
     * @return array Report of updates
     */
    public function updateTransactionEventIds()
    {
        $report = [
            'total' => 0,
            'updated' => 0,
            'failed' => 0,
            'already_set' => 0
        ];

        // Make sure the event_id column exists
        if (!\Schema::hasColumn('newtransactions', 'event_id')) {
            return [
                'error' => 'event_id column does not exist in transactions table',
                'tip' => 'Run migration to add event_id column first'
            ];
        }

        // Get transactions without event_id but with eventname
        $transactions = Transaction::whereNull('event_id')
            ->whereNotNull('eventname')
            ->get();

        $report['total'] = $transactions->count();

        foreach ($transactions as $transaction) {
            // Find the event by name
            $event = mctlists::where('name', $transaction->eventname)->first();

            if ($event) {
                try {
                    $transaction->event_id = $event->id;
                    $transaction->save();
                    $report['updated']++;
                } catch (\Exception $e) {
                    $report['failed']++;
                }
            } else {
                $report['failed']++;
            }
        }

        // Count transactions that already have event_id
        $report['already_set'] = Transaction::whereNotNull('event_id')->count();

        return $report;
    }
}
