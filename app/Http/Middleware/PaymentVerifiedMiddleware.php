<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Transaction;
use Symfony\Component\HttpFoundation\Response;

class PaymentVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log middleware execution for debugging
        Log::info('Payment verification middleware running', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'has_reference' => $request->has('reference'),
            'has_token' => $request->has('token'),
            'has_session_reference_data' => Session::has('reference_data'),
            'has_completed_order' => Session::has('completed_order'),
            'request_path' => $request->path(),
            'referrer' => $request->header('referer') ?? 'none',
            'session_keys' => array_keys(Session::all())
        ]);

        $isVerifiedPayment = false;

        // Check for a valid security token from our payment verification process
        if ($request->has('token')) {
            $token = $request->get('token');
            $storedToken = Session::get('success_token');
            $expiryTime = Session::get('success_token_expires', 0);

            if ($storedToken && $token === $storedToken && time() < $expiryTime) {
                // Token is valid - DON'T clear tokens here, that's the controller's job
                // This ensures the token is available to the controller
                $isVerifiedPayment = true;

                Log::info('Success token validated in middleware', [
                    'token_prefix' => substr($token, 0, 8) . '...',
                    'expires_in' => $expiryTime - time() . ' seconds'
                ]);
            } else {
                Log::warning('Invalid payment token used', [
                    'ip' => $request->ip(),
                    'provided_token' => substr($token, 0, 8) . '...',
                    'token_match' => ($storedToken && $token === $storedToken) ? 'yes' : 'no',
                    'token_expired' => ($storedToken && time() >= $expiryTime) ? 'yes' : 'no',
                    'has_stored_token' => !empty($storedToken) ? 'yes' : 'no'
                ]);
            }
        }

        // Check if we have verified transaction data from Paystack callback
        elseif (Session::has('reference_data')) {
            $referenceData = Session::get('reference_data');
            if (isset($referenceData->data->status) && $referenceData->data->status === 'success') {
                $isVerifiedPayment = true;
                Log::info('Payment verified through reference_data in session');
            }
        }

        // Check for completed order data (from our own processing)
        elseif (Session::has('completed_order')) {
            $isVerifiedPayment = true;
            Log::info('Payment verified through completed_order in session');
        }

        // Check if there's a valid transaction reference and it exists in the database
        elseif ($request->has('reference')) {
            $reference = $request->get('reference');

            // Validate reference format
            $validReferencePattern = '/^[A-Za-z0-9\-_]{3,50}$/';
            if (preg_match($validReferencePattern, $reference)) {
                // Check if the transaction exists in our database
                $transaction = Transaction::where(function($query) use ($reference) {
                    $query->where('reference', $reference)
                        ->orWhere('message', 'like', '%' . $reference . '%')
                        ->orWhere('message', 'like', '%"reference":"' . $reference . '"%');
                })
                ->where('status', 'success')
                ->first();

                if ($transaction) {
                    $isVerifiedPayment = true;
                    Log::info('Payment verified through database transaction', [
                        'reference' => $reference,
                        'transaction_id' => $transaction->id
                    ]);
                } else {
                    Log::warning('Transaction reference provided but not found in database', [
                        'reference' => $reference,
                        'ip' => $request->ip()
                    ]);
                }
            } else {
                Log::warning('Invalid reference format provided', [
                    'reference' => $reference,
                    'ip' => $request->ip()
                ]);
            }
        }

        // Only check if payment_success parameter is present
        if ($request->has('payment_success')) {
            $reference = $request->get('reference');
            
            // Validate reference format
            $validReferencePattern = '/^[A-Za-z0-9\-_]{3,50}$/';
            if (!preg_match($validReferencePattern, $reference)) {
                Log::warning('Invalid reference format in payment success', [
                    'reference' => $reference,
                    'ip' => $request->ip()
                ]);
                return redirect()->route('home');
            }

            // Check if we have success data in session
            if (!session()->has('success_data')) {
                // Look for transaction in database
                $transaction = Transaction::where(function($query) use ($reference) {
                    $query->where('reference', $reference)
                        ->orWhere('message', 'like', '%' . $reference . '%')
                        ->orWhere('message', 'like', '%"reference":"' . $reference . '"%');
                })
                ->where('status', 'success')
                ->first();

                if (!$transaction) {
                    Log::warning('No transaction found for payment success', [
                        'reference' => $reference,
                        'ip' => $request->ip()
                    ]);
                    return redirect()->route('home');
                }

                // Store transaction data in session for the modal
                session()->flash('success_data', [
                    'message' => 'Payment successful!',
                    'reference' => $reference,
                    'amount' => $transaction->amount / 100,
                    'email' => $transaction->email,
                    'ticket_data' => [
                        'name' => $transaction->tablename,
                        'quantity' => $transaction->quantity,
                        'event' => $transaction->eventname
                    ]
                ]);
            }
        }

        // If payment is verified, proceed to success page
        if ($isVerifiedPayment) {
            Log::info('Payment verified, allowing access to success page');
            return $next($request);
        }

        // If no verification method passed, redirect to home without error message
        Log::warning('Unauthorized access attempt to success page', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'auth' => auth()->check() ? 'yes' : 'no',
            'user_id' => auth()->check() ? auth()->id() : 'guest'
        ]);

        // Silent redirect without error message
        return redirect()->route('home');
    }
}
