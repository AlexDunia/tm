<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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
          $status = $newref->status;
          $message = $newref->message;
          $email = $newref->data->customer->email;
          $firstname = $newref->data->customer->first_name;
          $lastname = $newref->data->customer->last_name;
          $phone = $newref->data->customer->phone;
          $amount = $newref->data->amount;
          $event = $newref->data->metadata->custom_fields[0]->value;
          $quantityvalue = $newref->data->metadata->custom_fields[1]->value;
          $eventname = $newref->data->metadata->custom_fields[2]->value;

          // Store data in the database using prepared statements
          $transaction = new Transaction();
          $transaction->status = $status;
          $transaction->message = $message;
          $transaction->email = $email;
          $transaction->phone = $phone;
          $transaction->amount = $amount;
          $transaction->quantity = $quantityvalue;
          $transaction->tablename = $event;
          $transaction->eventname = $eventname;
          $transaction->firstname = $firstname;
          $transaction->lastname = $lastname;
          $transaction->user_id = auth()->id();
          $transaction->save();

          return [$newref];
      }
  }

  public function success(){
    return view('success');
}

}




