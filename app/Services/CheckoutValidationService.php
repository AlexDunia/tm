<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CheckoutValidationService
{
    /**
     * Validate checkout form data
     *
     * @param Request $request
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validateCheckoutData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|max:100|regex:/^[a-zA-Z0-9\s\-\'\.]+$/',
            'last_name' => 'required|string|max:100|regex:/^[a-zA-Z0-9\s\-\'\.]+$/',
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/'
            ],
        ], [
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'first_name.required' => 'First name is required',
            'first_name.regex' => 'First name contains invalid characters',
            'last_name.required' => 'Last name is required',
            'last_name.regex' => 'Last name contains invalid characters',
            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Please enter a valid phone number'
        ]);

        if ($validator->fails()) {
            return [
                'valid' => false,
                'errors' => $validator->errors()->toArray()
            ];
        }

        return [
            'valid' => true,
            'errors' => []
        ];
    }

    /**
     * Validate payment data
     *
     * @param Request $request
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validatePaymentData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reference' => 'required|string|max:100|regex:/^[A-Za-z0-9\-_]+$/',
            'amount' => 'required|numeric|min:1',
        ], [
            'reference.required' => 'Payment reference is required',
            'reference.regex' => 'Payment reference contains invalid characters',
            'amount.required' => 'Payment amount is required',
            'amount.numeric' => 'Payment amount must be a valid number',
            'amount.min' => 'Payment amount must be greater than zero'
        ]);

        if ($validator->fails()) {
            return [
                'valid' => false,
                'errors' => $validator->errors()->toArray()
            ];
        }

        return [
            'valid' => true,
            'errors' => []
        ];
    }
}
