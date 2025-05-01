<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentVerificationService
{
    /**
     * Manually verify a payment by reference
     *
     * @param string $reference The payment reference to verify
     * @return array The verification result
     */
    public function manuallyVerifyReference($reference)
    {
        // Log verification attempt
        Log::info('Manual payment verification attempt', ['reference' => $reference]);

        // First, check our database for this reference
        $transaction = Transaction::where('message', 'like', '%' . $reference . '%')
                        ->orWhere('message', 'like', '%' . strtolower($reference) . '%')
                        ->orWhere('message', 'like', '%' . strtoupper($reference) . '%')
                        ->first();

        if ($transaction) {
            return [
                'verified' => true,
                'source' => 'database',
                'transaction' => $transaction,
                'ticketIds' => json_decode($transaction->ticket_ids ?? '[]', true),
                'customer' => [
                    'name' => $transaction->firstname . ' ' . $transaction->lastname,
                    'email' => $transaction->email,
                    'phone' => $transaction->phone
                ],
                'order' => [
                    'eventName' => $transaction->eventname,
                    'quantity' => $transaction->quantity,
                    'amount' => $transaction->amount,
                ]
            ];
        }

        // If not in database, try Paystack API
        try {
            $sec = config('app.paystack_secret_key');

            // Secure the reference
            $secureReference = preg_replace('/[^A-Za-z0-9\-_]/', '', $reference);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $sec,
                'Cache-Control' => 'no-cache',
            ])->get("https://api.paystack.co/transaction/verify/{$secureReference}");

            if ($response->successful() && $response['status'] === true) {
                $data = $response['data'];

                // Extract data from response
                $customFields = $data['metadata']['custom_fields'] ?? [];
                $eventName = '';
                $quantity = 1;
                $ticketIds = [];

                // Get customer info from metadata
                foreach ($customFields as $field) {
                    if ($field['variable_name'] === 'order_ref') {
                        $orderRef = $field['value'];
                    } elseif ($field['variable_name'] === 'customer_name') {
                        $customerName = $field['value'];
                    } elseif ($field['variable_name'] === 'event_name') {
                        $eventName = $field['value'];
                    } elseif ($field['variable_name'] === 'ticket_count') {
                        $quantity = (int) $field['value'];
                    } elseif (strpos($field['variable_name'], 'ticket_ids') === 0) {
                        // Parse ticket IDs
                        $idsText = $field['value'];
                        if (strpos($idsText, '+') !== false) {
                            // Only partial IDs shown in metadata
                            $parts = explode('+', $idsText);
                            $explicitIds = explode(', ', trim($parts[0]));
                            $ticketIds = array_merge($ticketIds, $explicitIds);
                            // Generate additional IDs if needed
                        } else {
                            // All IDs shown in metadata
                            $explicitIds = explode(', ', $idsText);
                            $ticketIds = array_merge($ticketIds, $explicitIds);
                        }
                    }
                }

                // If we still don't have enough ticket IDs, generate placeholder ones
                if (count($ticketIds) < $quantity) {
                    $baseId = 'TIX-' . strtoupper(substr(md5($reference . time()), 0, 6));
                    for ($i = count($ticketIds) + 1; $i <= $quantity; $i++) {
                        $ticketIds[] = $baseId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                    }
                }

                return [
                    'verified' => true,
                    'source' => 'paystack',
                    'transaction' => null,
                    'ticketIds' => $ticketIds,
                    'customer' => [
                        'name' => $customerName ?? ($data['customer']['first_name'] . ' ' . $data['customer']['last_name']),
                        'email' => $data['customer']['email'],
                        'phone' => $data['customer']['phone'] ?? 'Not provided'
                    ],
                    'order' => [
                        'eventName' => $eventName,
                        'quantity' => $quantity,
                        'amount' => $data['amount'] / 100,
                        'reference' => $data['reference']
                    ]
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error verifying payment with Paystack', [
                'reference' => $reference,
                'error' => $e->getMessage()
            ]);
        }

        // If we get here, verification failed
        return [
            'verified' => false,
            'source' => null,
            'error' => 'Could not verify payment reference'
        ];
    }
}
