<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class PaymentVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access if any of the verification tokens exist
        if (
            // Allow if there's a success token in the request matching the one in session
            ($request->has('token') && session('success_token') && $request->get('token') === session('success_token')) ||

            // Allow if we have reference data stored in session
            session()->has('reference_data') ||

            // Allow if we have completed order data
            session()->has('completed_order') ||

            // Allow if there's a reference parameter (to be verified by the controller)
            $request->has('reference')
        ) {
            return $next($request);
        }

        // If user is authenticated, redirect to dashboard with error message
        if (auth()->check()) {
            \Illuminate\Support\Facades\Log::warning('Payment verification failed for authenticated user: ' . auth()->id(), [
                'session_data' => [
                    'has_success_token' => session()->has('success_token'),
                    'has_completed_order' => session()->has('completed_order'),
                    'has_reference_data' => session()->has('reference_data'),
                ],
                'request_params' => $request->all()
            ]);

            return redirect('/')->with('error', 'No payment verification found.');
        }

        // Otherwise redirect to cart
        return redirect()->route('cart')->with('error', 'Payment verification required.');
    }
}
