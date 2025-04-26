@extends('layouts.app')

@section('content')
<style>
    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        padding-bottom: 120px; /* Add space for sticky checkout button */
        position: relative;
        min-height: 70vh;
    }

    .cart-header {
        text-align: left;
        margin-bottom: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 0;
        color: white;
    }

    .order-id {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
        margin-left: 10px;
    }

    .items-count {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 5px;
    }

    .cart-subtitle {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.7);
    }

    .cart-total-display {
        background: rgba(30, 30, 40, 0.8);
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .order-total {
        font-size: 24px;
        font-weight: 700;
        color: white;
    }

    .currency-code {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
        margin-left: 5px;
    }

    .order-info {
        display: flex;
        gap: 20px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
    }

    .order-info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: 5px;
    }

    .delivery-estimate {
        background: rgba(30, 30, 40, 0.8);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 14px;
    }

    .satisfaction-prompt {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(40, 40, 55, 0.5);
        border-radius: 8px;
        padding: 15px 20px;
        margin-bottom: 30px;
        color: rgba(255, 255, 255, 0.8);
    }

    .satisfaction-text {
        display: flex;
        align-items: center;
    }

    .satisfaction-text i {
        color: #f0b90b;
        margin-right: 10px;
    }

    .leave-review-btn {
        background: #C04888;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }

    .leave-review-btn:hover {
        background: #d65c9e;
    }

    .cart-items {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 30px;
    }

    .cart-item {
        background: rgba(30, 30, 40, 0.8);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
        position: relative;
    }

    .cart-item:hover {
        border-color: rgba(255, 255, 255, 0.2);
    }

    .item-image {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
        background-color: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-image i {
        font-size: 30px;
        color: rgba(255, 255, 255, 0.5);
    }

    .item-details {
        flex-grow: 1;
        padding-right: 20px;
    }

    .item-event {
        font-size: 12px;
        color: #C04888;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .item-name {
        font-size: 18px;
        font-weight: 600;
        color: white;
        margin-bottom: 5px;
    }

    .item-price {
        font-size: 20px;
        font-weight: 700;
        color: white;
        position: absolute;
        right: 20px;
        top: 20px;
    }

    .item-status {
        position: absolute;
        right: 20px;
        top: 55px;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-shipped {
        background-color: rgba(39, 174, 96, 0.2);
        color: #27ae60;
    }

    .status-pending {
        background-color: rgba(241, 196, 15, 0.2);
        color: #f1c40f;
    }

    .status-delivering {
        background-color: rgba(52, 152, 219, 0.2);
        color: #3498db;
    }

    .item-meta {
        display: flex;
        align-items: center;
        margin-top: 10px;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
    }

    .item-meta-divider {
        margin: 0 10px;
        color: rgba(255, 255, 255, 0.3);
    }

    .item-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .action-btn {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        color: white;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
    }

    .action-btn i {
        margin-right: 5px;
    }

    .action-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .action-menu-btn {
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        font-size: 18px;
        padding: 5px;
    }

    .quantity-display {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 10px;
    }

    .quantity-label {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
    }

    .quantity-badge {
        background: rgba(192, 72, 136, 0.2);
        color: #C04888;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    .arrival-estimate {
        display: flex;
        align-items: center;
        margin-top: 5px;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
    }

    .arrival-estimate i {
        margin-right: 5px;
        color: #3498db;
    }

    .transit-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        background-color: #e67e22;
        color: white;
        margin-top: 10px;
    }

    .shipping-info {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 5px;
    }

    .ticket-id-preview {
        display: inline-block;
        background: rgba(192, 72, 136, 0.2);
        border-radius: 4px;
        padding: 2px 5px;
        margin: 2px;
        font-size: 10px;
        color: #C04888;
    }

    .show-all-ids {
        font-size: 10px;
        color: #C04888;
        text-decoration: underline;
        cursor: pointer;
        margin-left: 5px;
    }

    .item-quantity {
        display: flex;
        flex-direction: column;
        margin: 15px 0;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 6px;
        padding: 4px;
        width: fit-content;
    }

    .quantity-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.3);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .quantity-btn:hover {
        background: #C04888;
    }

    .quantity-input {
        width: 40px;
        text-align: center;
        background: transparent;
        border: none;
        color: white;
        font-size: 16px;
        font-weight: 600;
    }

    .item-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .item-subtotal {
        text-align: right;
    }

    .subtotal-label {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 5px;
    }

    .subtotal-value {
        font-size: 18px;
        font-weight: 600;
        color: #C04888;
    }

    .item-remove {
        margin-left: auto;
    }

    .remove-btn {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: rgba(255, 255, 255, 0.5);
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s;
        padding: 8px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .remove-btn:hover {
        color: #ff6b6b;
        background: rgba(255, 255, 255, 0.2);
    }

    .cart-summary {
        background: rgba(40, 40, 55, 0.8);
        border-radius: 12px;
        padding: 25px;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .summary-header {
        font-size: 20px;
        font-weight: 600;
        color: white;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .summary-label {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.7);
    }

    .summary-value {
        font-size: 15px;
        font-weight: 600;
        color: white;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        padding-top: 15px;
        margin-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .total-label {
        font-size: 18px;
        font-weight: 600;
        color: white;
    }

    .total-value {
        font-size: 24px;
        font-weight: 700;
        color: #C04888;
    }

    .cart-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .continue-shopping {
        display: inline-flex;
        align-items: center;
        color: white;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .continue-shopping i {
        margin-right: 8px;
    }

    .continue-shopping:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }

    .checkout-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #C04888;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .checkout-btn i {
        margin-left: 8px;
    }

    .checkout-btn:hover {
        background: #d65c9e;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(192, 72, 136, 0.3);
    }

    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        background: rgba(40, 40, 55, 0.8);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .empty-cart-icon {
        font-size: 60px;
        color: rgba(255, 255, 255, 0.2);
        margin-bottom: 20px;
    }

    .empty-cart-title {
        font-size: 24px;
        font-weight: 600;
        color: white;
        margin-bottom: 10px;
    }

    .empty-cart-text {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 30px;
    }

    .browse-events-btn {
        display: inline-flex;
        align-items: center;
        background: #C04888;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .browse-events-btn i {
        margin-right: 8px;
    }

    .browse-events-btn:hover {
        background: #d65c9e;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(192, 72, 136, 0.3);
    }

    /* Sticky checkout button */
    .sticky-checkout {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(30, 30, 40, 0.95);
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        z-index: 1000;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        transform: translateY(100%);
        transition: transform 0.3s ease-out;
    }

    .sticky-checkout.visible {
        transform: translateY(0);
    }

    .sticky-checkout-total {
        font-size: 18px;
        font-weight: 600;
        color: white;
    }

    .sticky-checkout-total .amount {
        color: #C04888;
        font-size: 20px;
        margin-left: 10px;
    }

    .ticket-ids-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .ticket-ids-content {
        background: rgba(30, 30, 40, 1);
        border-radius: 12px;
        width: 90%;
        max-width: 700px;
        max-height: 85vh;
        padding: 0;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .ticket-ids-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        background: rgba(20, 20, 30, 0.9);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .ticket-ids-title {
        font-size: 22px;
        font-weight: 600;
        color: white;
    }

    .close-modal {
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.6);
        font-size: 24px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .close-modal:hover {
        color: white;
    }

    .ticket-tracking-content {
        padding: 25px;
        overflow-y: auto;
        max-height: calc(85vh - 70px);
    }

    .tracking-status {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        background: rgba(39, 174, 96, 0.1);
        padding: 15px 20px;
        border-radius: 8px;
        border-left: 4px solid #27ae60;
    }

    .tracking-icon {
        font-size: 30px;
        color: #27ae60;
        margin-right: 15px;
    }

    .tracking-info {
        flex-grow: 1;
    }

    .tracking-title {
        font-size: 18px;
        font-weight: 600;
        color: white;
        margin-bottom: 5px;
    }

    .tracking-date {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
    }

    .tracking-progress {
        margin: 40px 0;
    }

    .progress-bar {
        height: 6px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
        position: relative;
        margin-bottom: 15px;
    }

    .progress-fill {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 50%; /* Adjust based on progress */
        background: linear-gradient(to right, #27ae60, #2ecc71);
        border-radius: 3px;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 25%;
        text-align: center;
    }

    .step-dot {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        margin-bottom: 8px;
    }

    .progress-step.active .step-dot {
        background: #27ae60;
        box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.3);
    }

    .step-label {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.6);
    }

    .progress-step.active .step-label {
        color: white;
        font-weight: 600;
    }

    .ticket-id-container {
        margin-top: 30px;
        background: rgba(30, 30, 45, 0.8);
        padding: 20px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .ticket-id-container h3 {
        font-size: 18px;
        color: white;
        margin-bottom: 10px;
    }

    .ticket-id-note {
        color: rgba(255, 255, 255, 0.6);
        font-size: 14px;
        margin-bottom: 20px;
    }

    .ticket-id-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .ticket-id-item {
        background: rgba(40, 40, 55, 0.8);
        border-radius: 8px;
        padding: 12px 15px;
        color: white;
        font-size: 14px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        font-family: monospace;
        letter-spacing: 1px;
        transition: all 0.2s;
    }

    .ticket-id-item:hover {
        background: rgba(192, 72, 136, 0.2);
        border-color: rgba(192, 72, 136, 0.3);
        transform: translateY(-2px);
    }

    .ticket-actions {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 30px;
    }

    .ticket-action-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
    }

    .ticket-action-btn i {
        margin-right: 8px;
    }

    .ticket-action-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .update-quantity-form {
        display: flex;
        align-items: center;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .cart-items {
            grid-template-columns: 1fr;
        }

        .cart-actions {
            flex-direction: column;
            gap: 15px;
        }

        .continue-shopping, .checkout-btn {
            width: 100%;
            justify-content: center;
        }

        .sticky-checkout {
            flex-direction: column;
            gap: 10px;
            padding: 15px;
        }

        .sticky-checkout-btn {
            width: 100%;
        }
    }
</style>

<div class="main-container">
    <div class="content-wrapper">
        <div class="cart-container">
            <div class="cart-header">
                <div>
                    <h1 class="cart-title">Your Order <span class="order-id">#ORD{{ rand(100000, 999999) }}</span></h1>
                    <div class="items-count">{{ count($mycart) }} items</div>
                </div>
                <div class="review-prompt">
                    <a href="{{ route('home') }}" class="continue-shopping">
                        <i class="fa-solid fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>

            @if(count($mycart) > 0)
                @php
                    $totalAmount = 0;
                    $totalItems = 0;
                @endphp

                <div class="cart-total-display">
                    <div class="order-total">
                        @foreach($mycart as $item)
                            @php
                                $totalAmount += $item->cprice * $item->cquantity;
                                $totalItems += $item->cquantity;
                            @endphp
                        @endforeach
                        ₦{{ number_format($totalAmount) }}
                        <span class="currency-code">NGN</span>
                    </div>
                    <div class="order-info">
                        <div class="order-info-item">
                            <span class="info-label">Order Placed</span>
                            <span>{{ date('d M, Y') }}</span>
                        </div>
                        <div class="order-info-item">
                            <span class="info-label">Shipping To</span>
                            <span>{{ Auth::check() ? Auth::user()->name : 'Guest User' }}</span>
                        </div>
                        <div class="order-info-item">
                            <span class="info-label">Status</span>
                            <span>Processing</span>
                        </div>
                        <div class="order-info-item">
                            <span class="info-label">Tracking</span>
                            <span>TRK{{ rand(1000000, 9999999) }}</span>
                        </div>
                    </div>
                </div>

                <div class="delivery-estimate">
                    <i class="fa-solid fa-calendar-alt"></i> Expected Delivery: {{ date('M d', strtotime('+3 days')) }} - {{ date('M d', strtotime('+7 days')) }}
                </div>

                <div class="satisfaction-prompt">
                    <div class="satisfaction-text">
                        <i class="fa-solid fa-star"></i> Please rate your experience after you receive your tickets
                    </div>
                    <button class="leave-review-btn">Leave Review</button>
                </div>

                <div class="cart-items">
                    @foreach($mycart as $item)
                        @php
                            $subtotal = $item->cprice * $item->cquantity;

                            // Generate unique ticket IDs based on item properties
                            $ticketIds = [];
                            $baseId = 'TIX-' . strtoupper(substr(md5($item->eventname . $item->cname), 0, 6));
                            for ($i = 1; $i <= $item->cquantity; $i++) {
                                $ticketIds[] = $baseId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                            }

                            // Store the ticket IDs in session
                            session(['ticket_ids_' . $item->id => $ticketIds]);

                            // Determine a random status for visual appeal
                            $statuses = ['shipped', 'pending', 'delivering'];
                            $randomStatus = $statuses[array_rand($statuses)];
                        @endphp
                        <div class="cart-item">
                            <div class="item-image">
                                @if($item->cdescription && !is_null($item->cdescription))
                                    <img src="{{ asset('storage/' . $item->cdescription) }}" alt="{{ $item->eventname }}">
                                @else
                                    <i class="fa-solid fa-ticket"></i>
                                @endif
                            </div>

                            <div class="item-details">
                                <div class="item-event">{{ $item->eventname }}</div>
                                <div class="item-name">{{ $item->cname }}</div>

                                <div class="quantity-display">
                                    <span class="quantity-label">Quantity:</span>
                                    <span class="quantity-badge">{{ $item->cquantity }}</span>
                                </div>

                                <div class="arrival-estimate">
                                    <i class="fa-solid fa-truck"></i> Arriving by {{ date('M d', strtotime('+5 days')) }}
                                </div>

                                @if($randomStatus == 'shipped')
                                <div class="transit-badge">In Transit</div>
                                @endif

                                <div class="shipping-info">
                                    Item is eligible for returns up to 30 days from purchase
                                </div>

                                <div class="item-meta">
                                    <span class="ticket-ids">
                                        Ticket IDs:
                                        @foreach(array_slice($ticketIds, 0, 1) as $id)
                                            <span class="ticket-id-preview">{{ $id }}</span>
                                        @endforeach
                                        @if(count($ticketIds) > 1)
                                            <span class="show-all-ids" onclick="showTicketIds('{{ $item->id }}', {{ json_encode($ticketIds) }})">
                                                +{{ count($ticketIds) - 1 }} more
                                            </span>
                                        @endif
                                    </span>
                                </div>

                                <div class="item-actions">
                                    <form action="{{ url('/update-cart') }}" method="post" class="update-quantity-form" style="display:none;">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <input type="hidden" name="quantity" class="quantity-input" value="{{ $item->cquantity }}" data-id="{{ $item->id }}" data-price="{{ $item->cprice }}">
                                    </form>

                                    <button class="action-btn" onclick="document.getElementById('buy-again-form-{{ $item->id }}').submit()">
                                        <i class="fa-solid fa-redo"></i> Buy Again
                                    </button>

                                    <button class="action-btn" onclick="showTicketIds('{{ $item->id }}', {{ json_encode($ticketIds) }})">
                                        <i class="fa-solid fa-ticket-alt"></i> Track Item
                                    </button>

                                    <div class="action-menu">
                                        <button class="action-menu-btn">
                                            <i class="fa-solid fa-ellipsis-v"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="item-price">₦{{ number_format($item->cprice) }}</div>

                            <div class="item-status status-{{ $randomStatus }}">
                                {{ ucfirst($randomStatus) }}
                            </div>

                            <a href="{{ url('/delete', $item->id) }}" class="remove-btn" title="Remove item" style="position: absolute; top: 20px; right: -40px;">
                                <i class="fa-solid fa-times"></i>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="cart-summary">
                    <div class="summary-header">Order Summary</div>

                    <div class="summary-row">
                        <div class="summary-label">Items ({{ $totalItems }})</div>
                        <div class="summary-value">₦{{ number_format($totalAmount) }}</div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-label">Service Fee</div>
                        <div class="summary-value">₦{{ number_format($totalAmount * 0.05) }}</div>
                    </div>

                    <div class="summary-total">
                        <div class="total-label">Total</div>
                        <div class="total-value" id="cart-total-amount">₦{{ number_format($totalAmount * 1.05) }}</div>
                    </div>

                    <div class="cart-actions">
                        <a href="{{ route('checkout') }}" class="checkout-btn">
                            Proceed to Checkout <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Sticky checkout button -->
                <div class="sticky-checkout" id="stickyCheckout">
                    <div class="sticky-checkout-total">
                        Total: <span class="amount">₦{{ number_format($totalAmount * 1.05) }}</span>
                    </div>
                    <a href="{{ route('checkout') }}" class="checkout-btn sticky-checkout-btn">
                        Proceed to Checkout <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Ticket IDs Modal -->
                <div class="ticket-ids-modal" id="ticketIdsModal">
                    <div class="ticket-ids-content">
                        <div class="ticket-ids-header">
                            <div class="ticket-ids-title">Ticket Tracking Information</div>
                            <button class="close-modal" onclick="closeTicketModal()">×</button>
                        </div>
                        <div class="ticket-tracking-content">
                            <div class="tracking-status">
                                <div class="tracking-icon">
                                    <i class="fa-solid fa-check-circle"></i>
                                </div>
                                <div class="tracking-info">
                                    <div class="tracking-title">Tickets Successfully Generated</div>
                                    <div class="tracking-date">{{ date('d M Y, h:i A') }}</div>
                                </div>
                            </div>

                            <div class="tracking-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill"></div>
                                </div>
                                <div class="progress-steps">
                                    <div class="progress-step active">
                                        <div class="step-dot"></div>
                                        <div class="step-label">Generated</div>
                                    </div>
                                    <div class="progress-step active">
                                        <div class="step-dot"></div>
                                        <div class="step-label">Processing</div>
                                    </div>
                                    <div class="progress-step">
                                        <div class="step-dot"></div>
                                        <div class="step-label">Available</div>
                                    </div>
                                    <div class="progress-step">
                                        <div class="step-dot"></div>
                                        <div class="step-label">Delivered</div>
                                    </div>
                                </div>
                            </div>

                            <div class="ticket-id-container">
                                <h3>Your Ticket IDs</h3>
                                <p class="ticket-id-note">Please keep these IDs for reference at the event</p>
                                <div class="ticket-id-list" id="ticketIdList">
                                    <!-- Ticket IDs will be inserted here by JavaScript -->
                                </div>
                            </div>

                            <div class="ticket-actions">
                                <button class="ticket-action-btn" onclick="window.print()">
                                    <i class="fa-solid fa-print"></i> Print Tickets
                                </button>
                                <button class="ticket-action-btn">
                                    <i class="fa-solid fa-envelope"></i> Email Tickets
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fa-solid fa-shopping-cart"></i>
                    </div>
                    <h2 class="empty-cart-title">Your cart is empty</h2>
                    <p class="empty-cart-text">Looks like you haven't added any tickets to your cart yet.</p>
                    <a href="{{ route('home') }}" class="browse-events-btn">
                        <i class="fa-solid fa-calendar-alt"></i> Browse Events
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show sticky checkout when scrolling
        const stickyCheckout = document.getElementById('stickyCheckout');
        if (stickyCheckout) {
            window.addEventListener('scroll', function() {
                const summaryRect = document.querySelector('.cart-summary').getBoundingClientRect();
                if (summaryRect.bottom < 0) {
                    stickyCheckout.classList.add('visible');
                } else {
                    stickyCheckout.classList.remove('visible');
                }
            });
        }

        // Initialize any buy again forms
        document.querySelectorAll('[id^="buy-again-form-"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Item added to cart again!');
            });
        });
    });

    // Show ticket IDs modal
    function showTicketIds(itemId, ticketIds) {
        const modal = document.getElementById('ticketIdsModal');
        const list = document.getElementById('ticketIdList');
        const currentDate = new Date();

        // Format date for display
        const formattedDate = currentDate.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        // Update tracking date
        if (document.querySelector('.tracking-date')) {
            document.querySelector('.tracking-date').textContent = formattedDate;
        }

        // Clear previous content
        list.innerHTML = '';

        // Add each ticket ID to the list
        ticketIds.forEach(id => {
            const div = document.createElement('div');
            div.className = 'ticket-id-item';
            div.textContent = id;
            list.appendChild(div);
        });

        // Show the modal
        modal.style.display = 'flex';

        // Animation for progress bar
        setTimeout(() => {
            const progressFill = document.querySelector('.progress-fill');
            if (progressFill) {
                progressFill.style.transition = 'width 1s ease-in-out';
                progressFill.style.width = '50%';
            }
        }, 100);
    }

    // Close ticket IDs modal
    function closeTicketModal() {
        document.getElementById('ticketIdsModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('ticketIdsModal');
        if (event.target === modal) {
            closeTicketModal();
        }
    }
</script>
@endsection
