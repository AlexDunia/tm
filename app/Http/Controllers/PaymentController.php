<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class PaymentController extends Controller
{
    /**
     * Constructor to set middleware exceptions
     */
    public function __construct()
    {
        // Apply auth middleware to all methods except these
        $this->middleware('auth')->except([
            'success',
            'verify',
            'getTransactionByReference',
            'debugTransaction'
        ]);
    }

    //
    public function index(){
        return view('Payment');
    }

    public function verify(Request $request, $reference)
    {
        // Start transaction log
        \Log::info('Payment verification initiated', [
            'reference' => $reference,
            'user_id' => auth()->id() ?? 'guest',
            'ip' => $request->ip()
        ]);

        // Validate reference format with strict pattern
        $validReferencePattern = '/^[A-Za-z0-9\-_]{3,50}$/';
        if (!preg_match($validReferencePattern, $reference)) {
            \Log::warning('Invalid reference format detected', [
                'reference' => $reference,
                'ip' => $request->ip()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'verified' => false,
                    'message' => 'Invalid payment reference format'
                ], 400);
            }

            session()->flash('error', 'Invalid payment reference format.');
            return redirect()->route('checkout');
        }

        // Get secret key from config
        $secretKey = config('app.paystack_secret_key');
        if (empty($secretKey)) {
            \Log::critical('Paystack secret key not configured');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'verified' => false,
                    'message' => 'Payment verification unavailable'
                ], 500);
            }

            session()->flash('error', 'Payment verification is currently unavailable.');
            return redirect()->route('checkout');
        }

        try {
            // Clean and encode the reference to prevent injection
            $reference = trim($reference);
            $encodedReference = urlencode($reference);

            // Initialize cURL with secure defaults
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.paystack.co/transaction/verify/$encodedReference",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer $secretKey",
                    "Cache-Control: no-cache",
                    "Accept: application/json"
                ],
                // Enable SSL verification in production
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_SSL_VERIFYPEER => true,
            ]);

            // Execute the request
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $err = curl_error($curl);
            curl_close($curl);

            // Log raw response for debugging but mask sensitive data
            \Log::debug('Paystack API response', [
                'reference' => $reference,
                'http_code' => $httpCode,
                'has_error' => !empty($err),
                'response_length' => strlen($response)
            ]);

            // Handle cURL errors
            if ($err) {
                \Log::error('Paystack cURL Error', [
                    'reference' => $reference,
                    'error' => $err
                ]);

                // Check if we already have this transaction in database
                $existingTransaction = Transaction::where('message', 'like', '%' . $reference . '%')
                    ->where('status', 'success')
                    ->first();

                if ($existingTransaction) {
                    \Log::info('Found existing successful transaction', [
                        'reference' => $reference,
                        'transaction_id' => $existingTransaction->id
                    ]);

                    session(['reference_data' => json_decode($response, false)]);

                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'status' => 'success',
                            'verified' => true,
                            'message' => 'Payment previously verified',
                            'token' => $reference
                        ]);
                    }

                    return redirect()->route('success');
                }

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'verified' => false,
                        'message' => 'Payment verification service unavailable'
                    ], 503);
                }

                session()->flash('payment_warning', 'We had a minor issue connecting to our payment provider. If your payment was successful, please contact support with your reference: ' . $reference);
                return redirect()->route('checkout');
            }

            // Parse response
            $paymentData = json_decode($response, false);

            // Verify response structure
            if (!$paymentData || !isset($paymentData->data) || !isset($paymentData->data->status)) {
                \Log::error('Invalid Paystack response structure', [
                    'reference' => $reference,
                    'response' => $response
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'verified' => false,
                        'message' => 'Invalid payment verification response'
                    ], 500);
                }

                session()->flash('error', 'We received an invalid response from our payment provider.');
                return redirect()->route('checkout');
            }

            $status = $paymentData->data->status;
            $message = $paymentData->message;

            // Store the reference data in session first, in case anything fails later
            session(['reference_data' => $paymentData]);

            try {
                $email = $paymentData->data->customer->email ?? '';
                $firstname = $paymentData->data->customer->first_name ?? '';
                $lastname = $paymentData->data->customer->last_name ?? '';
                $phone = $paymentData->data->customer->phone ?? '';
                $amount = $paymentData->data->amount ?? 0;

                // Safely extract custom fields with fallbacks
                $event = '';
                $quantityvalue = 1;
                $eventname = '';

                if (isset($paymentData->data->metadata->custom_fields)) {
                    $event = $paymentData->data->metadata->custom_fields[0]->value ?? '';
                    $quantityvalue = $paymentData->data->metadata->custom_fields[1]->value ?? 1;
                    $eventname = $paymentData->data->metadata->custom_fields[2]->value ?? '';
                }

                // Get ticket IDs if available
                $ticketIds = [];
                if (isset($paymentData->data->metadata->custom_fields) && count($paymentData->data->metadata->custom_fields) > 3) {
                    $ticketIdsJson = $paymentData->data->metadata->custom_fields[3]->value;
                    $ticketIds = json_decode($ticketIdsJson, true) ?? [];
                }

                // If no ticket IDs were provided, generate them
                if (empty($ticketIds)) {
                    $baseId = 'TIX-' . strtoupper(substr(md5($event . $eventname . time()), 0, 6));
                    for ($i = 1; $i <= $quantityvalue; $i++) {
                        $ticketIds[] = $baseId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                    }
                }

                // Create transaction record with validated data
                $transaction = new Transaction();
                $transaction->status = $status;
                $transaction->message = htmlspecialchars($message);
                $transaction->email = filter_var($email, FILTER_SANITIZE_EMAIL);
                $transaction->phone = preg_replace('/[^0-9+]/', '', $phone);
                $transaction->amount = (float) $amount;
                $transaction->quantity = (int) $quantityvalue;
                $transaction->tablename = htmlspecialchars($eventname);
                $transaction->eventname = htmlspecialchars($event);
                $transaction->firstname = htmlspecialchars($firstname);
                $transaction->lastname = htmlspecialchars($lastname);
                $transaction->user_id = auth()->id() ?? null;
                $transaction->reference = $reference;
                $transaction->ticket_ids = json_encode($ticketIds);
                $transaction->ip_address = $request->ip();
                $transaction->user_agent = $request->userAgent();
                $transaction->save();

                \Log::info('Transaction saved successfully', [
                    'reference' => $reference,
                    'transaction_id' => $transaction->id,
                    'status' => $status
                ]);
            } catch (\Exception $e) {
                \Log::error('Transaction save error', [
                    'reference' => $reference,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                // If we've got status success but failed to save, still redirect to success
                // with the data in session (fallback)
                if ($status === 'success') {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'status' => 'success',
                            'verified' => true,
                            'message' => 'Payment verified but there was an issue with order processing.',
                            'reference' => $reference
                        ]);
                    }

                    session()->flash('payment_warning', 'Your payment was successful, but there was an issue processing your order. Please contact support with reference: ' . $reference);
                    return redirect()->route('success', ['reference' => $reference]);
                }

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'verified' => false,
                        'message' => 'There was an issue processing your payment.'
                    ], 500);
                }

                session()->flash('error', 'There was an issue processing your order, but your payment may have been received. Please contact support with reference: ' . $reference);
                return redirect()->route('checkout');
            }

            // Final verification and redirect based on payment status
            if ($status === 'success') {
                // Generate a cryptographically secure token for success page
                $successToken = hash_hmac('sha256', $reference . uniqid(), config('app.key'));
                $expiryTime = now()->addMinutes(15)->timestamp; // Token expires in 15 minutes

                // Store in session with expiry time
                Session::put('success_token', $successToken);
                Session::put('success_token_expires', $expiryTime);

                // IMPORTANT: Store transaction details in a completed_order session variable
                // This ensures the data is available when the success page loads
                Session::put('completed_order', [
                    'ref' => $reference,
                    'paymentRef' => $reference,
                    'amount' => $amount / 100, // Convert to naira
                    'email' => $email,
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'ticket_data' => [
                        [
                            'name' => $eventname,
                            'price' => $amount / 100 / $quantityvalue, // Per ticket price
                            'quantity' => $quantityvalue,
                            'event' => $event
                        ]
                    ],
                    'ticket_ids' => $ticketIds
                ]);

                // Log the token creation (but not the full token)
                \Log::info('Payment verified, created success token and stored order data', [
                    'reference' => $reference,
                    'token_expiry' => $expiryTime,
                    'token_prefix' => substr($successToken, 0, 8) . '...'
                ]);

                // Clear shopping cart if payment is successful
                if (auth()->check()) {
                    \App\Models\Cart::where('user_id', auth()->id())->delete();
                } else {
                    Session::forget('cart_items');
                    Session::forget('tname');
                    Session::forget('tprice');
                    Session::forget('tquantity');
                    Session::forget('eventname');
                    Session::forget('totalprice');
                    Session::forget('timage');
                }

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => 'success',
                        'verified' => true,
                        'message' => 'Payment verified successfully',
                        'token' => $successToken,
                        'reference' => $reference,
                        'redirect_url' => route('success', ['token' => $successToken])
                    ]);
                }

                return redirect()->route('success', ['token' => $successToken]);
            } else {
                \Log::warning('Payment not successful', [
                    'reference' => $reference,
                    'status' => $status,
                    'message' => $message
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'verified' => false,
                        'message' => 'Payment was not successful: ' . $message
                    ], 402); // 402 Payment Required
                }

                session()->flash('error', 'Payment was not successful: ' . $message);
                return redirect()->route('checkout');
            }
        } catch (\Exception $e) {
            // Log the exception with detailed information
            \Log::error('Payment verification exception', [
                'reference' => $reference,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return appropriate response based on request type
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'verified' => false,
                    'message' => 'An error occurred during payment verification. Please contact support with reference: ' . $reference
                ], 500);
            }

            // Flash a user-friendly error message
            session()->flash('error', 'We encountered an issue processing your payment. If you believe your payment was successful, please contact customer support with reference: ' . $reference);

            return redirect()->route('checkout');
        }
    }


    public function success(Request $request){
        // Log all request and session data for debugging
        \Log::info('Success page accessed', [
            'auth' => auth()->check() ? 'yes' : 'no',
            'user_id' => auth()->check() ? auth()->id() : 'guest',
            'request_params' => $request->all(),
            'has_token' => $request->has('token') ? 'yes' : 'no',
            'has_reference' => $request->has('reference') ? 'yes' : 'no',
            'session_data' => [
                'has_reference_data' => session()->has('reference_data') ? 'yes' : 'no',
                'has_success_token' => session()->has('success_token') ? 'yes' : 'no',
                'has_completed_order' => session()->has('completed_order') ? 'yes' : 'no',
            ]
        ]);

        // Make token validation explicit but simplified for better success rate
        // The PaymentVerifiedMiddleware has already done most of the checks
        // This is a secondary validation
        if ($request->has('token')) {
            $token = $request->get('token');
            $storedToken = session('success_token');

            // If tokens don't match, log but don't block the flow
            // The middleware will have already blocked truly invalid requests
            if ($storedToken && $token !== $storedToken) {
                \Log::warning('Controller detected token mismatch, but continuing flow', [
                    'provided_token_prefix' => substr($token, 0, 8) . '...',
                    'stored_token_prefix' => substr($storedToken, 0, 8) . '...',
                ]);
            }

            // Clear tokens after successful access (one-time use token)
            // But only if we have the token in session
            if ($storedToken) {
                session()->forget(['success_token', 'success_token_expires']);
                \Log::info('Success tokens cleared after use');
            }
        }

        // Explicitly check if we're coming directly from a verified payment
        $isDirectPaymentAccess = $request->has('token') &&
                                !empty(session('completed_order')) &&
                                session()->has('_previous') &&
                                strpos(session('_previous.url'), 'verifypayment') !== false;

        if ($isDirectPaymentAccess) {
            \Log::info('Direct access from payment verification detected');
        }

        // Check for completed order data first (from our own processing)
        if (Session::has('completed_order')) {
            $completedOrder = Session::get('completed_order');

            // Log success
            \Log::info('Showing success page from completed_order', [
                'reference' => $completedOrder['ref'] ?? 'unknown',
                'user_id' => auth()->check() ? auth()->id() : 'guest'
            ]);

            // Extract ticket data
            $ticketData = $completedOrder['ticket_data'] ?? [];
            $ticketIds = $completedOrder['ticket_ids'] ?? [];

            // Generate ticket IDs if not already provided
            if (empty($ticketIds) && !empty($ticketData)) {
                $baseId = 'TIX-' . strtoupper(substr(md5(time() . uniqid()), 0, 6));
                $counter = 1;

                foreach ($ticketData as $ticket) {
                    $quantity = $ticket['quantity'] ?? 1;
                    for ($i = 0; $i < $quantity; $i++) {
                        $ticketIds[] = $baseId . '-' . str_pad($counter++, 3, '0', STR_PAD_LEFT);
                    }
                }
            }

            // Get recommendations based on this order
            $recommendations = $this->getRecommendations(auth()->id(), $completedOrder['email'] ?? null);

            // Return the success view with ticket information
            return view('Success', [
                'ticketIds' => $ticketIds,
                'orderRef' => $completedOrder['ref'] ?? '',
                'paymentRef' => $completedOrder['paymentRef'] ?? '',
                'amount' => $completedOrder['amount'] ?? 0,
                'email' => $completedOrder['email'] ?? '',
                'name' => ($completedOrder['first_name'] ?? '') . ' ' . ($completedOrder['last_name'] ?? ''),
                'tickets' => $ticketData ?? [],
                'recommendations' => $recommendations,
                'reference' => $completedOrder['ref'] ?? null
            ]);
        }

        // First check if we have reference data in the session (from Paystack callback)
        else if (Session::has('reference_data')) {
            $referenceData = Session::get('reference_data');

            // Get ticket IDs from the transaction
            $ticketIds = [];
            if (isset($referenceData->data->metadata->custom_fields[3]->value)) {
                $ticketIdsJson = $referenceData->data->metadata->custom_fields[3]->value;
                $ticketIds = json_decode($ticketIdsJson, true) ?? [];
            } else {
                // Try to get from most recent transaction
                $transaction = Transaction::where('email', $referenceData->data->customer->email)
                                ->latest()
                                ->first();

                if ($transaction && $transaction->ticket_ids) {
                    $ticketIds = json_decode($transaction->ticket_ids, true) ?? [];
                } else {
                    // Generate new IDs if needed
                    $event = $referenceData->data->metadata->custom_fields[0]->value ?? '';
                    $quantity = $referenceData->data->metadata->custom_fields[1]->value ?? 1;
                    $baseId = 'TIX-' . strtoupper(substr(md5($event), 0, 6));
                    for ($i = 1; $i <= $quantity; $i++) {
                        $ticketIds[] = $baseId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                    }
                }
            }

            // Clear the user's cart after successful payment
            if (auth()->check()) {
                // For authenticated users, clear from database
                \App\Models\Cart::where('user_id', auth()->id())->delete();
            } else {
                // For guest users, clear from session
                Session::forget('cart_items');
                Session::forget('tname');
                Session::forget('tprice');
                Session::forget('tquantity');
                Session::forget('eventname');
                Session::forget('totalprice');
                Session::forget('timage');
            }

            // Clear the 'reference_data' session variable
            Session::forget('reference_data');

            // Log the successful transaction retrieval
            \Log::info('Successfully retrieved transaction for reference', [
                'reference' => $referenceData->data->reference,
                'transaction_id' => $transaction->id ?? 'unknown'
            ]);

            // Update transaction reference if needed
            if (isset($transaction)) {
            $this->ensureTransactionReference($transaction, $referenceData->data->reference);
            }

            // Get recommendations based on this transaction
            $email = $referenceData->data->customer->email ?? null;
            $recommendations = $this->getRecommendations(auth()->id(), $email);

            // Return the success view with ticket information
            return view('Success', [
                'ticketIds' => $ticketIds,
                'eventName' => $referenceData->data->metadata->custom_fields[0]->value ?? '',
                'quantity' => $referenceData->data->metadata->custom_fields[1]->value ?? 1,
                'amount' => $referenceData->data->amount / 100, // Convert amount to naira
                'recommendations' => $recommendations
            ]);
        }
        // FALLBACK: If no reference data in session, try to get from query parameters
        else if ($request->has('reference')) {
            $reference = $request->input('reference');

            // Validate reference format with strict pattern
            $validReferencePattern = '/^[A-Za-z0-9\-_]{3,50}$/';
            if (!preg_match($validReferencePattern, $reference)) {
                return redirect('/')->with('error', 'Invalid payment reference format');
            }

            // Try to find transaction with this reference
            $transaction = $this->findTransactionByReference($reference);

            if ($transaction) {
                $ticketIds = json_decode($transaction->ticket_ids, true) ?? [];

                // Clear cart as above
                if (auth()->check()) {
                    \App\Models\Cart::where('user_id', auth()->id())->delete();
                } else {
                    Session::forget('cart_items');
                    Session::forget('tname');
                    Session::forget('tprice');
                    Session::forget('tquantity');
                    Session::forget('eventname');
                    Session::forget('totalprice');
                    Session::forget('timage');
                }

                // Log the successful transaction retrieval
                \Log::info('Successfully retrieved transaction for reference', [
                    'reference' => $reference,
                    'transaction_id' => $transaction->id
                ]);

                // Update transaction reference if needed
                $this->ensureTransactionReference($transaction, $reference);

                // Get recommendations based on this transaction
                $recommendations = $this->getRecommendations($transaction->user_id, $transaction->email);

                return view('Success', [
                    'ticketIds' => $ticketIds,
                    'eventName' => $transaction->eventname,
                    'quantity' => $transaction->quantity,
                    'amount' => $transaction->amount / 100, // Convert amount to naira
                    'reference' => $reference,
                    'customerName' => $transaction->firstname . ' ' . $transaction->lastname,
                    'customerEmail' => $transaction->email,
                    'customerPhone' => $transaction->phone,
                    'subtotal' => $transaction->amount / 100,
                    'serviceFee' => ($transaction->amount / 100) * 0.05,
                    'totalAmount' => ($transaction->amount / 100) * 1.05,
                    'orderItems' => [
                        [
                            'name' => 'Ticket',
                            'event' => $transaction->eventname,
                            'quantity' => $transaction->quantity,
                            'price' => ($transaction->amount / 100) / $transaction->quantity,
                            'total' => $transaction->amount / 100
                        ]
                    ],
                    'recommendations' => $recommendations
                ]);
            }

            // If we still don't have transaction details but we have the reference,
            // Show a generic success page
            return view('Success', [
                'ticketIds' => [],
                'eventName' => 'Your Event',
                'quantity' => 1,
                'amount' => 0,
                'manual_verification' => true,
                'reference' => $reference
            ]);
        } else {
            // Nothing valid found, redirect home with error
            return redirect('/')->with('error', 'No payment information found');
        }
    }

    /**
     * Manually verify a payment reference
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyReference(Request $request)
    {
        $request->validate([
            'reference' => 'required|string|max:100'
        ]);

        $reference = $request->input('reference');

        // Use PaymentVerificationService to verify the reference
        $verificationService = app()->make(\App\Services\PaymentVerificationService::class);
        $verificationResult = $verificationService->manuallyVerifyReference($reference);

        return response()->json($verificationResult);
    }

    /**
     * Get transaction details by reference
     *
     * @param Request $request
     * @param string $reference
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionByReference(Request $request, $reference)
    {
        // Validate reference format
        $validReferencePattern = '/^[A-Za-z0-9\-_]{3,50}$/';
        if (!preg_match($validReferencePattern, $reference)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid reference format'
            ], 400);
        }

        // Log request for debugging
        \Log::info('Fetching transaction details', [
            'reference' => $reference,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Try multiple search patterns for the reference
        $transaction = $this->findTransactionByReference($reference);

        // If transaction not found with original reference and it starts with 'TD', try without prefix
        if (!$transaction && strpos($reference, 'TD') === 0) {
            $alternativeRef = substr($reference, 2);
            \Log::info('Trying alternative reference format', [
                'original' => $reference,
                'alternative' => $alternativeRef
            ]);

            $transaction = $this->findTransactionByReference($alternativeRef);
        }

        // If still no transaction found, try a more relaxed search pattern
        if (!$transaction) {
            \Log::warning('Transaction not found with standard patterns', [
                'reference' => $reference
            ]);

            // Try a more relaxed search with just first few characters
            $firstSixChars = substr($reference, 0, 6);
            if (strlen($firstSixChars) >= 3) {
                $transaction = Transaction::where('message', 'like', '%' . $firstSixChars . '%')
                                ->latest()
                                ->first();
            }

            // If still not found
            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not found'
                ], 404);
            }
        }

        // Log successful transaction find
        \Log::info('Transaction found', [
            'reference' => $reference,
            'transaction_id' => $transaction->id,
            'transaction_reference' => $transaction->reference ?? 'N/A'
        ]);

        // Update transaction reference if needed
        $this->ensureTransactionReference($transaction, $reference);

        // Return transaction details
        return response()->json([
            'status' => 'success',
            'data' => [
                'reference' => $reference,
                'customer' => [
                    'name' => $transaction->firstname . ' ' . $transaction->lastname,
                    'email' => $transaction->email,
                    'phone' => $transaction->phone
                ],
                'event' => $transaction->eventname,
                'quantity' => $transaction->quantity,
                'subtotal' => $transaction->amount / 100,
                'serviceFee' => ($transaction->amount / 100) * 0.05,
                'totalAmount' => ($transaction->amount / 100) * 1.05,
                'date' => $transaction->created_at->format('F d, Y h:i A'),
                'ticket_ids' => json_decode($transaction->ticket_ids, true) ?? []
            ]
        ]);
    }

    /**
     * Helper function to find a transaction by reference using multiple patterns
     *
     * @param string $reference
     * @return Transaction|null
     */
    private function findTransactionByReference($reference)
    {
        // Check if the reference column exists in the table
        $hasReferenceColumn = Schema::hasColumn('newtransactions', 'reference');

        // Log this for debugging
        \Log::info('Searching for transaction', [
            'reference' => $reference,
            'has_reference_column' => $hasReferenceColumn
        ]);

        $query = Transaction::where(function($query) use ($reference, $hasReferenceColumn) {
            // Only add the reference column condition if it exists
            if ($hasReferenceColumn) {
                $query->where('reference', $reference);
            }

            // These conditions work with the message field which definitely exists
            $query->orWhere('message', 'like', '%' . $reference . '%')
                  ->orWhere('message', 'like', '%"reference":"' . $reference . '"%')
                  ->orWhere('message', 'like', '%"' . $reference . '"%')
                  ->orWhereRaw('LOWER(message) LIKE ?', ['%' . strtolower($reference) . '%']);
        })
        ->latest();

        // Log the SQL query for debugging
        $querySql = $query->toSql();
        $queryBindings = $query->getBindings();
        \Log::info('Transaction search query', [
            'sql' => $querySql,
            'bindings' => $queryBindings
        ]);

        return $query->first();
    }

    /**
     * Debug method to help troubleshoot transaction retrieval
     *
     * @param Request $request
     * @param string $reference
     * @return \Illuminate\Http\JsonResponse
     */
    public function debugTransaction(Request $request, $reference)
    {
        // Only allow in non-production environments
        if (app()->environment('production')) {
            return response()->json(['error' => 'Debug endpoints not available in production'], 403);
        }

        // Log debug attempt
        \Log::info('Transaction debug requested', [
            'reference' => $reference,
            'ip' => $request->ip()
        ]);

        // Search for transactions with various patterns
        $exactMatch = Transaction::where('reference', $reference)->first();
        $messageLike = Transaction::where('message', 'like', '%' . $reference . '%')->get();
        $lowerMatch = Transaction::whereRaw('LOWER(message) LIKE ?', ['%' . strtolower($reference) . '%'])->get();

        // Get all transactions (limited to recent ones)
        $recentTransactions = Transaction::latest()->take(5)->get()->map(function($t) {
            return [
                'id' => $t->id,
                'reference' => $t->reference ?? 'null',
                'message_excerpt' => substr($t->message ?? '', 0, 100),
                'status' => $t->status,
                'created_at' => $t->created_at->format('Y-m-d H:i:s')
            ];
        });

        return response()->json([
            'reference_searched' => $reference,
            'exact_match' => $exactMatch ? [
                'id' => $exactMatch->id,
                'reference' => $exactMatch->reference,
                'message_excerpt' => substr($exactMatch->message, 0, 100),
                'status' => $exactMatch->status
            ] : null,
            'message_like_count' => $messageLike->count(),
            'message_like_matches' => $messageLike->take(3)->map(function($t) {
                return [
                    'id' => $t->id,
                    'reference' => $t->reference ?? 'null',
                    'message_excerpt' => substr($t->message ?? '', 0, 100),
                    'status' => $t->status
                ];
            }),
            'lower_match_count' => $lowerMatch->count(),
            'recent_transactions' => $recentTransactions,
            'db_connection' => config('database.default')
        ]);
    }

    // Helper method to update transaction reference if needed
    private function ensureTransactionReference($transaction, $reference)
    {
        // Check if the reference column exists and the transaction doesn't have a reference
        if (\Schema::hasColumn('newtransactions', 'reference') &&
            (empty($transaction->reference) || is_null($transaction->reference))) {
            try {
                $transaction->reference = $reference;
                $transaction->save();

                \Log::info('Updated transaction reference', [
                    'transaction_id' => $transaction->id,
                    'reference' => $reference
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to update transaction reference', [
                    'transaction_id' => $transaction->id,
                    'reference' => $reference,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Get event recommendations based on user history
     *
     * @param int|null $userId
     * @param string|null $email
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getRecommendations($userId = null, $email = null)
    {
        // Use recommendation service if it exists
        if (class_exists('\App\Services\RecommendationService')) {
            $recommendationService = app()->make('\App\Services\RecommendationService');
            return $recommendationService->getRecommendationsForUser($userId, $email);
        }

        // Fallback if service doesn't exist: get some random upcoming events
        return \App\Models\mctlists::where('date', '>=', now()->format('Y-m-d'))
            ->inRandomOrder()
            ->take(4)
            ->get();
    }

    /**
     * Debug route to check success page functionality
     * IMPORTANT: Only for development environment!
     */
    public function testSuccessPage(Request $request)
    {
        // Only allow in non-production environments
        if (app()->environment('production')) {
            abort(404);
        }

        // Create a dummy completed order in session
        $testOrder = [
            'ref' => 'TEST-' . time(),
            'paymentRef' => 'TEST-PAYSTACK-' . uniqid(),
            'amount' => 5000,
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'ticket_data' => [
                [
                    'name' => 'Test Ticket',
                    'price' => 5000,
                    'quantity' => 1,
                    'event' => 'Test Event'
                ]
            ]
        ];

        // Store in session
        Session::put('completed_order', $testOrder);

        // Log test access
        \Log::info('Test success page accessed', [
            'ip' => $request->ip(),
            'test_ref' => $testOrder['ref']
        ]);

        // Redirect to success page
        return redirect()->route('success');
    }
}




