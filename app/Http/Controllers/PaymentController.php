<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
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
            // Handle invalid input gracefully, e.g., return an error response
            return ['error' => 'Invalid reference format'];
        }

        // URL-encode the reference to preven
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
            // Handle cURL error gracefully
            return ['error' => 'cURL Error: ' . $err];
        } else {
            $newref = json_decode($response);

            // Extract the fields from the $newref object
            $status = $newref->data->status;
            $message = $newref->message;
            $email = $newref->data->customer->email;
            $firstname = $newref->data->customer->first_name;
            $lastname = $newref->data->customer->last_name;
            $phone = $newref->data->customer->phone;
            $amount = $newref->data->amount;
            $event = $newref->data->metadata->custom_fields[0]->value;
            $quantityvalue = $newref->data->metadata->custom_fields[1]->value;
            $eventname = $newref->data->metadata->custom_fields[2]->value;

            // Get ticket IDs if available
            $ticketIds = [];
            if (count($newref->data->metadata->custom_fields) > 3) {
                $ticketIdsJson = $newref->data->metadata->custom_fields[3]->value;
                $ticketIds = json_decode($ticketIdsJson, true) ?? [];
            }

            // If no ticket IDs were provided, generate them
            if (empty($ticketIds)) {
                $baseId = 'TIX-' . strtoupper(substr(md5($event . $eventname), 0, 6));
                for ($i = 1; $i <= $quantityvalue; $i++) {
                    $ticketIds[] = $baseId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                }
            }

            // Store data in the database using prepared statements
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
            $transaction->user_id = auth()->id();
            $transaction->ticket_ids = json_encode($ticketIds);
            $transaction->save();
            session(['reference_data' => $newref]);
            // return ['data' => $newref, 'redirect' => redirect()->route('logg')];
            if($status === 'success'){
                return[$newref];
            }else{
                return redirect()->route('logg');
            }

         // Redirect to the 'logg' route
        //  dd("Redirecting")
        //  return redirect()->route('logg');
      //    dd($response);

            // ... (other fields)

          //   $transaction->save();


        }
    }


    public function success(){
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

            // Clear the 'reference_data' session variable
            Session::forget('reference_data');

            // Redirect to 'logg' route
            return view('Success', [
                'ticketIds' => $ticketIds,
                'eventName' => $referenceData->data->metadata->custom_fields[0]->value ?? '',
                'quantity' => $referenceData->data->metadata->custom_fields[1]->value ?? 1
            ]);
        } else {
            // 'reference_data' doesn't exist in the session, redirect to 'checkout' route
            return redirect()->route('logg');
            // You can also return a view with an error message if needed
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




