<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class SessionController extends Controller
{
    /**
     * Handle session refresh requests
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        // Update last activity time
        Session::put('last_activity_time', time());

        // Get context-aware timeout or use default
        $timeout = Session::get('context_timeout', config('session.lifetime', 120));

        // Calculate expiry time
        $expiryTime = time() + ($timeout * 60);
        Session::put('context_timeout_expires', $expiryTime);

        return response()->json([
            'success' => true,
            'timeout' => $timeout,
            'expires' => $expiryTime,
            'remaining' => $expiryTime - time()
        ]);
    }

    /**
     * Handle silent re-authentication for "Remember Me" users
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function silentAuth(Request $request)
    {
        // If already authenticated, just refresh the session
        if (Auth::check()) {
            Session::regenerate();
            return response()->json(['success' => true]);
        }

        // Try to authenticate from remember token
        $rememberToken = $request->cookie(Auth::getRecallerName());

        if (!$rememberToken) {
            return response()->json(['success' => false, 'reason' => 'no_remember_token']);
        }

        // Parse the recaller cookie
        $parts = explode('|', $rememberToken);

        if (count($parts) !== 3) {
            return response()->json(['success' => false, 'reason' => 'invalid_token_format']);
        }

        list($id, $token) = $parts;

        // Find the user with this remember token
        $user = User::where('id', $id)
            ->where('remember_token', hash('sha256', $token))
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'reason' => 'user_not_found']);
        }

        // Log the user in
        Auth::login($user, true);
        Session::regenerate();

        return response()->json(['success' => true]);
    }

    /**
     * Handle normal session timeout - override the normal expiration
     * to provide a more user-friendly experience
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function expired(Request $request)
    {
        return response()->view('session-expired');
    }
}
