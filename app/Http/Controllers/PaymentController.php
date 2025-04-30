<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Constructor to set middleware exceptions
     */
    public function __construct()
    {
        // Apply auth middleware to all methods except success
        $this->middleware('auth')->except(['success', 'verify']);
    }

    //
    public function index(){
        return view('Payment');
    }

    public function verify($reference){
        $sec = config('app.paystack_secret_key');

        // Define a regular expression pattern for valid characters
        $validReferencePattern = '/^[A-Za-z0-9\-_]+$/';

        // Check if $reference contains only valid characters
        if (!preg_match($validReferencePattern, $reference)) {
            // Handle invalid input gracefully
            session()->flash('error', 'Invalid reference format');
            return redirect()->route('checkout');
        }

        try {
            // URL-encode the reference to prevent injection
            $reference = urlencode($reference);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.paystack.co/transaction/verify/$reference",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $sec",
                    "Cache-Control: no-cache",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                // Log the error but don't expose it to the user
                \Log::error('Paystack cURL Error: ' . $err);

                // Store error in session but proceed anyway if possible
                session()->flash('payment_warning', 'We had a minor issue connecting to payment provider, but your transaction may have completed successfully.');

                // Check if we already have a transaction in our database
                $existingTransaction = Transaction::where('message', 'like', '%' . $reference . '%')->first();
                if ($existingTransaction) {
                    // We already have this transaction, so proceed to success
                    session(['reference_data' => json_decode($response)]);
                    return redirect()->route('success');
                }

                return redirect()->route('checkout');
            }

            $newref = json_decode($response);

            // Check if response has expected structure
            if (!isset($newref->data) || !isset($newref->data->status)) {
                \Log::error('Invalid Paystack response: ' . $response);
                session()->flash('error', 'We received an invalid response from our payment provider.');
                return redirect()->route('checkout');
            }

            // Extract the fields from the $newref object
            $status = $newref->data->status;
            $message = $newref->message;

            // Store the reference data in session first, in case anything fails later
            session(['reference_data' => $newref]);

            try {
                $email = $newref->data->customer->email ?? '';
                $firstname = $newref->data->customer->first_name ?? '';
                $lastname = $newref->data->customer->last_name ?? '';
                $phone = $newref->data->customer->phone ?? '';
                $amount = $newref->data->amount ?? 0;

                // Safely extract custom fields with fallbacks
                $event = '';
                $quantityvalue = 1;
                $eventname = '';

                if (isset($newref->data->metadata->custom_fields)) {
                    $event = $newref->data->metadata->custom_fields[0]->value ?? '';
                    $quantityvalue = $newref->data->metadata->custom_fields[1]->value ?? 1;
                    $eventname = $newref->data->metadata->custom_fields[2]->value ?? '';
                }

                // Get ticket IDs if available
                $ticketIds = [];
                if (isset($newref->data->metadata->custom_fields) && count($newref->data->metadata->custom_fields) > 3) {
                    $ticketIdsJson = $newref->data->metadata->custom_fields[3]->value;
                    $ticketIds = json_decode($ticketIdsJson, true) ?? [];
                }

                // If no ticket IDs were provided, generate them
                if (empty($ticketIds)) {
                    $baseId = 'TIX-' . strtoupper(substr(md5($event . $eventname . time()), 0, 6));
                    for ($i = 1; $i <= $quantityvalue; $i++) {
                        $ticketIds[] = $baseId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                    }
                }

                // Store data in the database
                $transaction = new Transaction();
                $transaction->status = $status;
                $transaction->message = $message;
                $transaction->email = $email;
                $transaction->phone = $phone;
                $transaction->amount = $amount;
                $transaction->quantity = $quantityvalue;
                $transaction->tablename = $eventname;
                $transaction->eventname = $event;
                $transaction->firstname = $firstname;
                $transaction->lastname = $lastname;
                $transaction->user_id = auth()->id() ?? null;
                $transaction->ticket_ids = json_encode($ticketIds);
                $transaction->save();
            } catch (\Exception $e) {
                // Log the database error but don't expose to user
                \Log::error('Transaction save error: ' . $e->getMessage());

                // If we've got status success but failed to save, still redirect to success
                // The success page will handle displaying ticket info from session
                if ($status === 'success') {
                    return redirect()->route('success');
                }

                session()->flash('error', 'There was an issue processing your order, but your payment was received.');
                return redirect()->route('checkout');
            }

            // If status is success, redirect to success page
            if($status === 'success'){
                // Generate a temporary success token
                $successToken = hash('sha256', $reference . time() . Str::random(40));
                session(['success_token' => $successToken]);
                session(['success_token_expires' => now()->addMinutes(10)->timestamp]);

                return redirect()->route('success', ['token' => $successToken]);
            } else {
                session()->flash('error', 'Payment was not successful: ' . $message);
                return redirect()->route('checkout');
            }
        } catch (\Exception $e) {
            // Catch any other exceptions
            \Log::error('Payment verification error: ' . $e->getMessage());

            // Flash a user-friendly error message
            session()->flash('error', 'We encountered an issue processing your payment. If you believe your payment was successful, please contact customer support.');

            return redirect()->route('checkout');
        }
    }


    public function success(){
        // Security check at controller level as well
        if (request()->has('token')) {
            $token = request()->get('token');
            $storedToken = session('success_token');
            $expiryTime = session('success_token_expires', 0);

            if ($storedToken && $token === $storedToken && time() < $expiryTime) {
                // Clear tokens after use for security
                session()->forget(['success_token', 'success_token_expires']);
            } else if ($storedToken && $token !== $storedToken) {
                // Invalid token provided
                return redirect()->route('home')->with('error', 'Invalid payment session.');
            }
        } else if (!Session::has('reference_data') && !request()->has('reference')) {
            return redirect()->route('home')->with('error', 'Invalid access to payment success page.');
        }

        // First check if we have reference data in the session
        if (Session::has('reference_data')) {
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

            // Return the success view with ticket information
            return view('Success', [
                'ticketIds' => $ticketIds,
                'eventName' => $referenceData->data->metadata->custom_fields[0]->value ?? '',
                'quantity' => $referenceData->data->metadata->custom_fields[1]->value ?? 1
            ]);
        }
        // FALLBACK: If no reference data in session, try to get from query parameters
        else if (request()->has('reference')) {
            $reference = request()->get('reference');

            // Attempt to find a transaction with this reference
            $transaction = Transaction::where('message', 'like', '%' . $reference . '%')
                            ->where('status', 'success') // Ensure it's a successful transaction
                            ->latest()
                            ->first();

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

                return view('Success', [
                    'ticketIds' => $ticketIds,
                    'eventName' => $transaction->eventname,
                    'quantity' => $transaction->quantity
                ]);
            }

            // Log suspicious access attempts for security monitoring
            \Log::warning('Suspicious success page access attempt with reference: ' . $reference);

            // If still no transaction found but user has email confirmation, allow manual confirmation
            return view('Success', [
                'ticketIds' => [],
                'eventName' => 'Your Event',
                'quantity' => 1,
                'manual_verification' => true,
                'reference' => $reference
            ]);
        } else {
            // 'reference_data' doesn't exist in the session, redirect to home route
            return redirect()->route('home')->with('error', 'No payment information found');
        }
    }


//     if (Session::has('reference_data')) {
//         // Reference data exists, load the page
//         return redirect()->route('logg');// Replace 'your.page.name' with the actual view name
//     } else {
//         // Reference data doesn't exist, redirect to another page or display an error message
//         return redirect()->route('checkout'); // Replace 'another.route' with the actual route name
//         // You can also return a view with an error message if needed
//     }
//   }


}




