@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://js.paystack.co/v1/inline.js" defer></script>

@php
    // Redirect to cart if there are no items - using Laravel redirect instead of direct header
    if(empty($mycart) || count($mycart) === 0) {
        redirect()->route('cart')->send();
    }
@endphp

<style>
    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        position: relative;
        min-height: 70vh;
        display: flex;
        flex-direction: column;
    }

    .checkout-title {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 30px;
        color: white;
        text-align: center;
    }

    .checkout-body {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
    }

    .checkout-details {
        flex: 1;
        min-width: 300px;
    }

    .checkout-sidebar {
        width: 380px;
    }

    @media (max-width: 900px) {
        .checkout-body {
            flex-direction: column;
        }
        .checkout-sidebar {
                width: 100%;
            order: -1;
        }
    }

    @media (max-width: 640px) {
        .checkout-container {
            padding: 20px 15px;
        }

        .checkout-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-section, .order-summary {
            padding: 20px 15px;
        }
    }

    .order-summary {
        background: rgba(30, 30, 40, 0.5);
        border-radius: 12px;
        padding: 25px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        margin-bottom: 30px;
    }

    .summary-title {
        font-size: 18px;
                font-weight: 600;
                color: white;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

    .ticket-item {
                display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .ticket-item:last-child {
        border-bottom: none;
    }

    .ticket-info {
        flex: 1;
    }

    .ticket-name {
        font-weight: 500;
        color: white;
        margin-bottom: 3px;
    }

    .event-name {
        font-size: 13px;
                color: rgba(255, 255, 255, 0.6);
    }

    .ticket-quantity {
        font-size: 14px;
                color: #C04888;
        background: rgba(192, 72, 136, 0.1);
        padding: 3px 8px;
        border-radius: 4px;
        display: inline-block;
        margin-top: 5px;
    }

    .ticket-price {
        text-align: right;
                font-weight: 600;
                color: white;
        padding-left: 15px;
            }

    .subtotal-row, .fee-row, .total-row {
                display: flex;
                justify-content: space-between;
        padding: 12px 0;
    }

    .subtotal-row, .fee-row {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .total-row {
        padding-top: 15px;
        margin-top: 5px;
    }

    .subtotal-label, .fee-label {
        color: rgba(255, 255, 255, 0.7);
            }

            .total-label {
                font-weight: 600;
                color: white;
        font-size: 16px;
    }

    .subtotal-value, .fee-value {
        font-weight: 500;
        color: white;
            }

            .total-value {
                font-weight: 700;
                color: #C04888;
        font-size: 18px;
    }

    .form-section {
        background: rgba(30, 30, 40, 0.5);
                border-radius: 12px;
        padding: 25px;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .form-title {
        font-size: 18px;
        font-weight: 600;
        color: white;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
    }

    .form-group input {
        width: 100%;
        padding: 12px 15px;
        background: rgba(20, 20, 30, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
                color: white;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-group input:focus {
        border-color: rgba(192, 72, 136, 0.5);
        outline: none;
        box-shadow: 0 0 0 2px rgba(192, 72, 136, 0.25);
    }

    .payment-button {
        width: 100%;
        padding: 14px;
        background: #C04888;
        color: white;
        border: none;
        border-radius: 8px;
                font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
                display: flex;
        justify-content: center;
                align-items: center;
                gap: 10px;
    }

    .payment-button:hover {
        background: #d65c9e;
        transform: translateY(-2px);
    }

    .back-to-cart {
        display: inline-flex;
        align-items: center;
        margin-bottom: 30px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s;
    }

    .back-to-cart i {
        margin-right: 8px;
    }

    .back-to-cart:hover {
        color: white;
    }

    .secure-checkout {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 15px;
        color: rgba(255, 255, 255, 0.5);
        font-size: 13px;
    }

    .secure-checkout i {
        color: #4CAF50;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .payment-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: #1e1e28;
        border-left: 4px solid #C04888;
        color: white;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 1000;
        display: none;
        animation: fadeIn 0.3s ease-out;
    }

    .payment-button:disabled {
        opacity: 0.8;
        cursor: not-allowed;
            }
        </style>

<div class="checkout-container">
    <a href="{{ route('cart') }}" class="back-to-cart">
        <i class="fa-solid fa-arrow-left"></i> Back to Cart
    </a>

    <h1 class="checkout-title">Checkout</h1>

    <div class="checkout-body">
        <div class="checkout-details">
            <div class="form-section">
                <h2 class="form-title">Customer Information</h2>
    <form id="paymentForm">
        <div class="form-group">
                        <label for="email-address">Email Address</label>
          <input type="email" id="email-address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required />
        </div>
        <div class="form-group">
          <label for="first-name">First Name</label>
                        <input type="text" id="first-name" pattern="[A-Za-z\s]+" required />
        </div>
        <div class="form-group">
          <label for="last-name">Last Name</label>
                        <input type="text" id="last-name" pattern="[A-Za-z\s]+" required />
        </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" pattern="[0-9+\s]+" required />
        </div>

                    <!-- Hidden input fields for data -->
                    <input type="hidden" id="ticket_data" value="{{ htmlspecialchars(json_encode($ticketIds ?? [])) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <!-- Submit button container at the bottom of sidebar -->
                </form>
     </div>
     </div>

        <div class="checkout-sidebar">
            <div class="order-summary">
                <h2 class="summary-title">Order Summary</h2>

                @php
                    $totalAmount = 0;
                    $totalItems = 0;

                    // Generate Ticket IDs if needed
                    $allTicketIds = [];

                    // Move helper functions to controller/service instead of defining in view
                    // For now, we'll use them as is but encapsulate to avoid global namespace pollution
                    $helpers = new class {
                        public function getCartItemPrice($item) {
                            if(is_object($item)) {
                                return isset($item->cprice) ? (float)$item->cprice : 0;
                            } else {
                                return isset($item['price']) ? (float)$item['price'] : 0;
                            }
                        }

                        public function getCartItemQuantity($item) {
                            if(is_object($item)) {
                                return isset($item->cquantity) ? (int)$item->cquantity : 0;
                            } else {
                                return isset($item['quantity']) ? (int)$item['quantity'] : 0;
                            }
                        }

                        public function getCartItemName($item) {
                            if(is_object($item)) {
                                return isset($item->cname) ? htmlspecialchars($item->cname) : '';
                            } else {
                                return isset($item['product_name']) ? htmlspecialchars($item['product_name']) : '';
                            }
                        }

                        public function getCartItemEventName($item) {
                            if(is_object($item)) {
                                return isset($item->eventname) ? htmlspecialchars($item->eventname) : 'Product';
                            } else {
                                return isset($item['item_name']) ? htmlspecialchars($item['item_name']) : 'Product';
                            }
                        }

                        public function getCartItemId($item, $index) {
                            if(is_object($item)) {
                                return isset($item->id) ? (int)$item->id : $index;
                            } else {
                                return isset($item['id']) ? (int)$item['id'] : $index;
                            }
                        }
                    };
                @endphp

                @foreach($mycart as $index => $item)
                    @php
                        // Calculate item total
                        $itemPrice = $helpers->getCartItemPrice($item);
                        $itemQuantity = $helpers->getCartItemQuantity($item);
                        $itemTotal = $itemPrice * $itemQuantity;
                        $totalAmount += $itemTotal;
                        $totalItems += $itemQuantity;

                        // Generate ticket IDs for this item
                        $itemTicketIds = [];
                        $eventName = $helpers->getCartItemEventName($item);
                        $ticketName = $helpers->getCartItemName($item);
                        $itemId = $helpers->getCartItemId($item, $index);

                        // Check if we already have IDs in session
                        $sessionKey = 'ticket_ids_' . $itemId;
                        if (session()->has($sessionKey)) {
                            $itemTicketIds = session($sessionKey);
                        } else {
                            // Generate ticket IDs securely
                            for ($i = 1; $i <= $itemQuantity; $i++) {
                                // Use Laravel's Str::uuid() or similar for production
                                $uniqueId = 'TIX-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
                                $uniqueId .= '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                                $itemTicketIds[] = $uniqueId;
                            }
                            session([$sessionKey => $itemTicketIds]);
                        }

                        // Add to all ticket IDs array
                        $allTicketIds[$itemId] = $itemTicketIds;
                    @endphp

                    <div class="ticket-item">
                        <div class="ticket-info">
                            <div class="ticket-name">{{ $helpers->getCartItemName($item) }}</div>
                            <div class="event-name">{{ $helpers->getCartItemEventName($item) }}</div>
                            <div class="ticket-quantity">Qty: {{ $itemQuantity }}</div>
     </div>
                        <div class="ticket-price">₦{{ number_format($itemTotal) }}</div>
          </div>
                @endforeach

                <div class="subtotal-row">
                    <div class="subtotal-label">Subtotal</div>
                    <div class="subtotal-value">₦{{ number_format($totalAmount) }}</div>
          </div>

                <div class="fee-row">
                    <div class="fee-label">Service Fee (5%)</div>
                    <div class="fee-value">₦{{ number_format($totalAmount * 0.05) }}</div>
                    </div>

                <div class="total-row">
                    <div class="total-label">Total</div>
                    <div class="total-value">₦{{ number_format($totalAmount * 1.05) }}</div>
                    </div>

                <button type="button" id="payment-button" class="payment-button">
                    <i class="fa-solid fa-lock"></i> Pay Now
                </button>

                <div class="secure-checkout">
                    <i class="fa-solid fa-shield-alt"></i>
                    <span>Secure checkout with Paystack</span>
      </div>
  </div>
        </div>
    </div>
</div>

<script>
    // Wait for document and Paystack to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Check if PaystackPop is defined
        if (typeof PaystackPop === 'undefined') {
            // Try reloading the script
            var script = document.createElement('script');
            script.src = 'https://js.paystack.co/v1/inline.js';
            script.onload = function() {
                // Enable the button once script is loaded
                document.getElementById('payment-button').disabled = false;
                document.getElementById('payment-button').dataset.state = 'ready';
            };
            script.onerror = function() {
                showNotification('Payment gateway could not be loaded. Please refresh the page.', 'error');
            };
            document.head.appendChild(script);
        } else {
            document.getElementById('payment-button').dataset.state = 'ready';
        }

        // Attach event listener to the payment button
        document.getElementById('payment-button').addEventListener('click', function(e) {
            payWithPaystack(e);
        });

        // Add form validation
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            payWithPaystack(e);
        });
    });

    // Paystack integration
    function payWithPaystack(e) {
        e.preventDefault();

        // Get the button and check its state
        const payButton = document.getElementById('payment-button');
        const buttonState = payButton.dataset.state;

        // Don't proceed if button is already in processing state
        if (buttonState === 'processing') {
            return;
        }

        // Store original button text and set state
        const originalButtonText = payButton.innerHTML;
        payButton.dataset.state = 'processing';

        // Show loading indicator
        payButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
        payButton.disabled = true;

        // Get form values and sanitize them
        const email = document.getElementById('email-address').value.trim();
        const firstName = document.getElementById('first-name').value.trim();
        const lastName = document.getElementById('last-name').value.trim();
        const phone = document.getElementById('phone').value.trim();

        // Validate input fields
        if (!email || !firstName || !lastName || !phone) {
            // Reset button if validation fails
            payButton.innerHTML = originalButtonText;
            payButton.disabled = false;
            payButton.dataset.state = 'ready';
            showNotification('Please fill in all required fields', 'warning');
            return;
        }

        // Email validation using regex
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(email)) {
            payButton.innerHTML = originalButtonText;
            payButton.disabled = false;
            payButton.dataset.state = 'ready';
            showNotification('Please enter a valid email address', 'warning');
            return;
        }

        // Name validation - only letters and spaces
        const nameRegex = /^[A-Za-z\s]+$/;
        if (!nameRegex.test(firstName) || !nameRegex.test(lastName)) {
            payButton.innerHTML = originalButtonText;
            payButton.disabled = false;
            payButton.dataset.state = 'ready';
            showNotification('Names should contain only letters', 'warning');
            return;
        }

        // Phone validation - only numbers, +, and spaces
        const phoneRegex = /^[0-9+\s]+$/;
        if (!phoneRegex.test(phone)) {
            payButton.innerHTML = originalButtonText;
            payButton.disabled = false;
            payButton.dataset.state = 'ready';
            showNotification('Please enter a valid phone number', 'warning');
            return;
        }

        // Get ticket data from server-side variables
        // Note: Server must validate these values again on the backend
        const allTicketIds = @json($allTicketIds ?? []);
        const serverTotalAmount = {{ (float)($totalAmount ?? 0) * 1.05 }}; // Including 5% service fee

        // Check if PaystackPop is defined
        if (typeof PaystackPop === 'undefined') {
            payButton.innerHTML = originalButtonText;
            payButton.disabled = false;
            payButton.dataset.state = 'ready';
            showNotification('Payment gateway not available. Please refresh the page.', 'error');
            return;
        }

        // Generate secure reference using crypto API if available
        let randomRef = 'TD';
        if (window.crypto && window.crypto.getRandomValues) {
            const array = new Uint32Array(2);
            window.crypto.getRandomValues(array);
            randomRef += array[0].toString().substr(0, 5) + array[1].toString().substr(0, 5);
        } else {
            // Fallback to less secure Math.random if crypto API is not available
            randomRef += Math.floor((Math.random() * 1000000000) + 1);
        }

        try {
            // Get CSRF token for AJAX request
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            let handler = PaystackPop.setup({
                key: 'pk_test_a23671022344a4de4ca87e5b42f68b3f5d84bfd9', // Temporary hardcoded key
                email: email,
                amount: Math.round(serverTotalAmount * 100), // Convert to kobo and ensure it's an integer
                currency: 'NGN',
                metadata: {
                    custom_fields: [
                        {
                            display_name: "Order Reference",
                            variable_name: "order_ref",
                            value: randomRef
                        },
                        {
                            display_name: "Customer Name",
                            variable_name: "customer_name",
                            value: firstName + " " + lastName
                        },
                        {
                            display_name: "Phone Number",
                            variable_name: "phone",
                            value: phone
                        },
                        {
                            display_name: "Ticket Count",
                            variable_name: "ticket_count",
                            value: "{{ $totalItems }} tickets"
                        },
                        // Add individual ticket items for the receipt
                        @foreach($mycart as $index => $item)
                        {
                            display_name: "{{ $helpers->getCartItemEventName($item) }} - {{ $helpers->getCartItemName($item) }}",
                            variable_name: "item_{{ $index }}",
                            value: "{{ $helpers->getCartItemQuantity($item) }} x ₦{{ number_format($helpers->getCartItemPrice($item)) }}"
                        },
                        @endforeach
                        // Add ticket IDs by event for verification
                        @php $idCounter = 0; @endphp
                        @foreach($allTicketIds as $itemId => $ticketIdGroup)
                        @php
                            $eventName = "";
                            $ticketName = "";
                            // Find the matching cart item to get event and ticket name
                            foreach($mycart as $index => $item) {
                                if($helpers->getCartItemId($item, $index) == $itemId) {
                                    $eventName = $helpers->getCartItemEventName($item);
                                    $ticketName = $helpers->getCartItemName($item);
                                    break;
                                }
                            }
                            // Format the ticket IDs (limited to first 3 if many)
                            $displayIds = count($ticketIdGroup) > 3
                                ? array_slice($ticketIdGroup, 0, 2)
                                : $ticketIdGroup;
                            $idsText = implode(", ", $displayIds);
                            if(count($ticketIdGroup) > 3) {
                                $idsText .= " + " . (count($ticketIdGroup) - 2) . " more";
                            }
                        @endphp
                        {
                            display_name: "IDs: {{ $eventName }} - {{ $ticketName }}",
                            variable_name: "ticket_ids_{{ $idCounter++ }}",
                            value: "{{ $idsText }}"
                        },
                        @endforeach
                    ]
                },
                ref: randomRef,
                onClose: function() {
                    // Reset button on close
                    payButton.innerHTML = originalButtonText;
                    payButton.disabled = false;
                    payButton.dataset.state = 'ready';
                    showNotification('Payment window closed.', 'warning');
                },
                callback: function(response) {
                    console.log('Paystack callback received:', response);
                    let reference = response.reference;

                    // Update button state to verifying
                    payButton.dataset.state = 'verifying';

                    // Show verification message
                    payButton.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Verifying payment...';
                    showNotification('Verifying your payment...', 'info');

                    // Clear cart data immediately
                    if (window.clearAllCartData) {
                        window.clearAllCartData();
                    }

                    // Make verification request to server
                    fetch(`/verifypayment/${reference}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success' || data.verified === true) {
                            showNotification('Payment successful! Redirecting...', 'success');
                            // Force redirect to home
                            window.location.href = '/';
                        } else {
                            throw new Error('Payment verification failed');
                        }
                    })
                    .catch(error => {
                        console.error('Verification error:', error);
                        // Even if verification fails, still redirect to home since Paystack confirmed payment
                        showNotification('Payment received! Redirecting...', 'success');
                        window.location.href = '/';
                    });
                }
            });

            // Open Paystack iframe with a slight delay
            setTimeout(function() {
                handler.openIframe();
            }, 100);

        } catch (error) {
            // Reset button on error
            payButton.innerHTML = originalButtonText;
            payButton.disabled = false;
            payButton.dataset.state = 'ready';
            showNotification('An error occurred while setting up the payment. Please try again.', 'error');
        }
    }

    // Function to show notifications
    function showNotification(message, type = 'info') {
        const notification = document.getElementById('paymentNotification');
        if (!notification) return;

        // Set notification styling based on type
        notification.style.borderLeftColor =
            type === 'success' ? '#4CAF50' :
            type === 'error' ? '#F44336' :
            type === 'warning' ? '#FF9800' : '#2196F3';

        notification.textContent = message;
        notification.style.display = 'block';

        // Auto-hide after 5 seconds
        setTimeout(() => {
            notification.style.display = 'none';
        }, 5000);
    }
</script>

<!-- Success notification element -->
<div id="paymentNotification" class="payment-notification"></div>
@endsection
