<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PurchaseVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if this is an after_purchase access attempt
        if ($request->has('after_purchase')) {
            // Check for indicators of successful purchase
            $hasPurchaseSession = Session::has('successful_purchase') ||
                                Session::has('completed_order') ||
                                Session::has('paystack_successful');

            // Get purchase-related flags from frontend
            $frontendPurchaseFlag = $request->header('X-Purchase-Verified', 'false');
            $frontendPurchaseTime = $request->header('X-Purchase-Time', null);

            // Log the access attempt
            Log::info('After purchase access attempt', [
                'has_session_flags' => $hasPurchaseSession ? 'yes' : 'no',
                'frontend_flag' => $frontendPurchaseFlag,
                'frontend_time' => $frontendPurchaseTime,
                'user' => auth()->check() ? auth()->id() : 'guest',
                'ip' => $request->ip()
            ]);

            // If no purchase indicators found, redirect to home
            if (!$hasPurchaseSession && $frontendPurchaseFlag !== 'true') {
                Log::warning('Unauthorized after_purchase access attempt', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'user' => auth()->check() ? auth()->id() : 'guest'
                ]);

                return redirect()->route('home');
            }

            // Clear cart for all users on valid after_purchase access
            // This ensures cart is cleared for both auth and non-auth users
            $this->clearCart();
        }

        return $next($request);
    }

    /**
     * Clear the cart for all user types
     */
    private function clearCart()
    {
        // For authenticated users
        if (auth()->check()) {
            \App\Models\Cart::where('user_id', auth()->id())->delete();
            \Illuminate\Support\Facades\Cache::forget('cart_totals_' . auth()->id());

            Log::info('Cleared authenticated user cart after successful purchase', [
                'user_id' => auth()->id()
            ]);
        }

        // For all users (session-based)
        Session::forget([
            'cart_items',
            'tname',
            'tprice',
            'tquantity',
            'eventname',
            'totalprice',
            'timage',
            'pending_order',
            'transaction_id',
            'paystack_reference',
            'checkout_data'
        ]);

        // Set flags to indicate cart has been cleared
        Session::put('cart_cleared', true);
        Session::put('cart_cleared_time', now()->timestamp);

        Log::info('Cleared session cart data after successful purchase');
    }
}
