<?php

namespace App\Http\Controllers;

use App\Models\mctlists;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    /**
     * Display personalized recommendations for the current user
     */
    public function index(Request $request, RecommendationService $recommendationService)
    {
        // Get recommendations for the current user
        if (auth()->check()) {
            $recommendations = $recommendationService->getRecommendationsForUser(auth()->id());
            $source = 'user_history';
        } elseif ($request->session()->has('guest_email')) {
            // If we have a guest email in session
            $recommendations = $recommendationService->getRecommendationsForUser(
                null,
                $request->session()->get('guest_email')
            );
            $source = 'guest_history';
        } else {
            // Get trending recommendations for new users
            $recommendations = $recommendationService->getTrendingEvents();
            $source = 'trending';
        }

        return view('recommendations', [
            'recommendations' => $recommendations,
            'source' => $source
        ]);
    }

    /**
     * Display recommendations similar to a specific event
     */
    public function similarEvents(Request $request, $eventId, RecommendationService $recommendationService)
    {
        $event = mctlists::findOrFail($eventId);
        $similar = $recommendationService->getSimilarEvents($event);

        if ($request->wantsJson()) {
            return response()->json([
                'event' => $event->name,
                'similar_events' => $similar
            ]);
        }

        return view('similar_events', [
            'event' => $event,
            'similar_events' => $similar
        ]);
    }

    /**
     * Admin-only: Update transaction event IDs
     */
    public function updateTransactionIds(RecommendationService $recommendationService)
    {
        // Check admin permission
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $report = $recommendationService->updateTransactionEventIds();

        return view('admin.recommendation_report', [
            'report' => $report,
            'title' => 'Transaction Event ID Update'
        ]);
    }

    /**
     * Admin-only: View trending events
     */
    public function trending(RecommendationService $recommendationService)
    {
        // Check admin permission
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        $trending = $recommendationService->getTrendingEvents(20);

        return view('admin.trending_events', [
            'events' => $trending,
            'title' => 'Trending Events'
        ]);
    }
}
