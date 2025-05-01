<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Transaction;

class PaymentVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a direct access to the success page
        if ($request->path() === 'success' || $request->path() === '/success') {
            // Check for a valid token first
            if ($request->has('token')) {
                $token = $request->get('token');
                $storedToken = session('success_token');
                $expiryTime = session('success_token_expires', 0);

                // If token is valid and not expired
                if ($storedToken && $token === $storedToken && time() < $expiryTime) {
                    // Token is valid, proceed
                    return $next($request);
                }
            }

            // First check for reference data in session
            if (!session()->has('reference_data')) {
                // No session data, check if request has reference parameter
                if (!$request->has('reference')) {
                    // No reference provided, redirect to home with error
                    return redirect('/')
                        ->with('error', 'Access denied. Valid payment verification is required.');
                }

                // Look for transaction with this reference
                $reference = $request->get('reference');
                $transaction = Transaction::where('message', 'like', '%' . $reference . '%')
                    ->where('status', 'success')
                    ->first();

                if (!$transaction) {
                    // No transaction found with this reference
                    return redirect('/')
                        ->with('error', 'Invalid payment reference.');
                }

                // Valid reference found, proceed
            }
        }

        return $next($request);
    }
}
