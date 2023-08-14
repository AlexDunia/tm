<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
    public function index(){
        return view('Payment');
    }

    public function verify($reference){
        // dd($referrence);
        $sec = "sk_test_e0de6eb7250d13310b94b334d584698d0671c97d";
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
        $newref = json_decode($response);


         // Extract the fields from the $newref object
    $status = $newref->status;
    $message = $newref->message;
    $email = $newref->data->customer->email;
    $phone = $newref->data->customer->phone;
    $amount = $newref->data->amount;

    // Store data in the database
    $transaction = new Transaction();
    $transaction->status = $status;
    $transaction->message = $message;
    $transaction->email = $email;
    $transaction->phone = $phone;
    $transaction->amount = $amount;
    $transaction->save();
    return [$newref];
    }
}

