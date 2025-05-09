/**
 * Handle payment verification and process order
 */
public function verify($reference)
{
    // Existing verification code...
    // ... existing code ...

    // After successful verification, set flags for cart clearing
    session()->put('successful_purchase', true);
    session()->put('completed_order', true);
    session()->put('paystack_successful', true);

    // Redirect to home page with after_purchase parameter
    return redirect()->route('home', ['after_purchase' => 1])->with('payment_success', true);
}
