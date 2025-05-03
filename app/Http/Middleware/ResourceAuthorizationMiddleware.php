<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ResourceAuthorizationMiddleware
{
    /**
     * Handle an incoming request and check if the user is authorized to access the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $resourceType  The type of resource being accessed (e.g., 'cart', 'transaction')
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $resourceType = null): Response
    {
        // If no resource type is specified or user is not authenticated, abort
        if (!$resourceType || !Auth::check()) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Extract resource ID from route parameters
        if (!$request->route()) {
            return $next($request);
        }

        $resourceId = $request->route($resourceType . '_id') ?? $request->route('id');

        if (!$resourceId) {
            return $next($request);
        }

        $user = Auth::user();
        $authorized = false;

        // Check resource ownership based on resource type
        switch ($resourceType) {
            case 'cart':
                $authorized = $user->relatewithcart()->where('id', $resourceId)->exists();
                break;

            case 'transaction':
                $authorized = $user->relatewithtransactions()->where('id', $resourceId)->exists();
                break;

            case 'event':
                // If you have an event model with a user relationship
                // $authorized = \App\Models\mctlists::where('id', $resourceId)
                //                  ->where('user_id', $user->id)
                //                  ->exists();
                // For now, admin-only access
                $authorized = $user->is_admin;
                break;

            default:
                $authorized = false;
                break;
        }

        if (!$authorized) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You do not have permission to access this resource'], 403);
            }

            return redirect()->route('home')->with('error', 'You do not have permission to access this resource');
        }

        return $next($request);
    }
}
