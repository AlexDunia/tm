@extends('layouts.app')

@section('content')
<div class="main-container">
    <!-- Notification Area -->
    @if(session('error') || session('warning') || session('success') || session('payment_warning') || isset($manual_verification))
    <div class="notification-area">
        @if(session('error'))
        <div class="notification error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <p>{{ session('error') }}</p>
        </div>
        @endif

        @if(session('warning') || session('payment_warning'))
        <div class="notification warning">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <p>{{ session('warning') ?? session('payment_warning') }}</p>
        </div>
        @endif

        @if(session('success'))
        <div class="notification success">
            <i class="fa-solid fa-circle-check"></i>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if(isset($manual_verification) && $manual_verification)
        <div class="notification warning">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <p>We couldn't verify all your payment details automatically. If you received a confirmation email from Paystack, your tickets are valid. Reference: {{ $reference ?? 'N/A' }}</p>
        </div>
        @endif
    </div>
    @endif

    <div class="success-wrapper">
        <div class="success-card">
            <div class="success-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <h1>Payment Successful!</h1>

            <p class="success-message">
                Your payment has been successfully processed. You will receive an email confirmation with your transaction details.
            </p>

            <div class="order-summary">
                <h2>Order Summary</h2>
                @if(isset($eventName) && !empty($eventName))
                <div class="summary-item">
                    <span class="summary-label">Event:</span>
                    <span class="summary-value">{{ $eventName }}</span>
                </div>
                @endif

                @if(isset($quantity) && !empty($quantity))
                <div class="summary-item">
                    <span class="summary-label">Quantity:</span>
                    <span class="summary-value">{{ $quantity }} ticket(s)</span>
                </div>
                @endif
            </div>

            @if(isset($ticketIds) && count($ticketIds) > 0)
                <div class="ticket-section">
                    <h2>Your Tickets</h2>
                    <p class="ticket-instruction">Please save or screenshot these ticket IDs - you'll need to present them at the event.</p>

                    <div class="ticket-list">
                        @foreach($ticketIds as $id)
                            <div class="ticket-item">
                                <div class="ticket-id">{{ $id }}</div>
                                <div class="ticket-qr">
                                    <i class="fa-solid fa-qrcode"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="success-actions">
                <a href="{{ route('home') }}" class="home-btn">
                    <i class="fa-solid fa-house"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .notification-area {
        width: 90%;
        max-width: 800px;
        margin: 20px auto;
    }

    .notification {
        display: flex;
        align-items: center;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .notification i {
        font-size: 24px;
        margin-right: 15px;
    }

    .notification p {
        margin: 0;
        flex: 1;
        font-size: 15px;
        line-height: 1.4;
    }

    .notification.error {
        background-color: rgba(220, 53, 69, 0.2);
        border: 1px solid rgba(220, 53, 69, 0.5);
        color: #f8d7da;
    }

    .notification.error i {
        color: #dc3545;
    }

    .notification.warning {
        background-color: rgba(255, 193, 7, 0.2);
        border: 1px solid rgba(255, 193, 7, 0.5);
        color: #fff3cd;
    }

    .notification.warning i {
        color: #ffc107;
    }

    .notification.success {
        background-color: rgba(40, 167, 69, 0.2);
        border: 1px solid rgba(40, 167, 69, 0.5);
        color: #d4edda;
    }

    .notification.success i {
        color: #28a745;
    }

    .success-wrapper {
        width: 90%;
        max-width: 800px;
        margin: 40px auto;
    }

    .success-card {
        background: rgba(37, 36, 50, 0.8);
        color: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        text-align: center;
        border: 1px solid rgba(192, 72, 136, 0.3);
    }

    .success-icon {
        font-size: 60px;
        color: #4CAF50;
        margin-bottom: 20px;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .success-card h1 {
        font-size: 32px;
        margin-bottom: 15px;
        color: #ffffff;
    }

    .success-message {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 30px;
        line-height: 1.5;
    }

    .order-summary {
        background: rgba(255, 255, 255, 0.05);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .order-summary h2 {
        font-size: 20px;
        margin-bottom: 15px;
        color: #C04888;
        text-align: left;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        text-align: left;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: rgba(255, 255, 255, 0.7);
    }

    .summary-value {
        font-weight: bold;
        color: white;
    }

    .ticket-section {
        margin: 30px 0;
        background: rgba(192, 72, 136, 0.1);
        padding: 25px;
        border-radius: 8px;
        border: 1px solid rgba(192, 72, 136, 0.2);
        text-align: left;
    }

    .ticket-section h2 {
        font-size: 22px;
        margin-bottom: 10px;
        color: #C04888;
    }

    .ticket-instruction {
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 20px;
        font-size: 14px;
    }

    .ticket-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 15px;
    }

    .ticket-item {
        background: rgba(40, 40, 55, 0.8);
        border-radius: 8px;
        padding: 15px;
        border: 1px solid rgba(192, 72, 136, 0.3);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .ticket-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        background: rgba(192, 72, 136, 0.2);
    }

    .ticket-id {
        font-family: 'Courier New', monospace;
        font-size: 16px;
        font-weight: bold;
        color: white;
        margin-bottom: 12px;
        word-break: break-all;
    }

    .ticket-qr {
        font-size: 40px;
        color: rgba(255, 255, 255, 0.9);
        margin-top: 5px;
    }

    .success-actions {
        margin-top: 30px;
    }

    .home-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 25px;
        background: #C04888;
        color: white !important;
        border-radius: 8px;
        font-weight: bold;
        text-decoration: none;
        font-size: 16px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .home-btn:hover {
        background: #d658a0;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(192, 72, 136, 0.3);
    }

    @media (max-width: 700px) {
        .success-wrapper {
            width: 95%;
            margin: 20px auto;
        }

        .success-card {
            padding: 25px 20px;
        }

        .success-icon {
            font-size: 50px;
        }

        .success-card h1 {
            font-size: 24px;
        }

        .ticket-list {
            grid-template-columns: 1fr;
        }
    }
</style>
