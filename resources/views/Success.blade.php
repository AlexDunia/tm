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
        <div class="notification info">
            <i class="fa-solid fa-circle-info"></i>
            <p>Your payment has been processed. We have your reference ({{ $reference ?? 'N/A' }}) on file, and your tickets are valid. Enjoy the event!</p>
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

                @if(isset($amount) && !empty($amount))
                <div class="summary-item">
                    <span class="summary-label">Price:</span>
                    <span class="summary-value">₦{{ number_format($amount, 2) }}</span>
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
                <a href="/" class="home-btn">
                    <i class="fa-solid fa-house"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Manual Verification Form -->
    <div class="manual-verification-wrapper">
        <div class="manual-verification-card">
            <h2>Can't See Your Tickets?</h2>
            <p>If you've made a payment but don't see your tickets, enter your payment reference below:</p>

            <form id="verifyReferenceForm" class="verify-form">
                <div class="form-group">
                    <input type="text" id="referenceInput" name="reference" placeholder="Enter payment reference" required>
                </div>
                <button type="submit" class="verify-btn">
                    <i class="fa-solid fa-magnifying-glass"></i> Verify Payment
                </button>
            </form>

            <div id="verificationResult" class="verification-result" style="display: none;"></div>
        </div>
    </div>

    <!-- Social Sharing Popup -->
    <div id="socialSharingPopup" class="social-sharing-popup">
        <div class="social-sharing-content">
            <div class="close-popup">&times;</div>
            <div class="celebration-animation">
                <i class="fa-solid fa-ticket fa-bounce"></i>
                <i class="fa-solid fa-star fa-spin"></i>
                <i class="fa-solid fa-music fa-beat"></i>
            </div>
            <h2>Share Your Ticket Purchase!</h2>
            <p>Share your ticket purchase on social media platforms with a celebratory message.</p>

            <div class="social-buttons">
                <a href="#" class="social-btn facebook" onclick="shareOnFacebook()">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="#" class="social-btn twitter" onclick="shareOnTwitter()">
                    <i class="fa-brands fa-x-twitter"></i>
                </a>
                <a href="#" class="social-btn whatsapp" onclick="shareOnWhatsapp()">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show social sharing popup after 5-8 seconds
        setTimeout(function() {
            document.getElementById('socialSharingPopup').classList.add('active');
        }, Math.floor(Math.random() * (8000 - 5000 + 1)) + 5000);

        // Close popup when clicking the X button
        document.querySelector('.close-popup').addEventListener('click', function() {
            document.getElementById('socialSharingPopup').classList.remove('active');
        });

        // Handle manual verification form submission
        document.getElementById('verifyReferenceForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const reference = document.getElementById('referenceInput').value.trim();
            if (!reference) return;

            const resultDiv = document.getElementById('verificationResult');
            resultDiv.innerHTML = '<div class="verification-loading"><i class="fa-solid fa-circle-notch fa-spin"></i> Verifying payment...</div>';
            resultDiv.style.display = 'block';

            // AJAX call to verify the reference
            fetch('/verify-reference', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ reference: reference })
            })
            .then(response => response.json())
            .then(data => {
                if (data.verified) {
                    let html = '<div class="verification-success">';
                    html += '<i class="fa-solid fa-circle-check"></i>';
                    html += '<h3>Payment Verified!</h3>';

                    if (data.ticketIds && data.ticketIds.length > 0) {
                        html += '<div class="verified-tickets">';
                        html += '<h4>Your Tickets:</h4>';
                        html += '<ul>';
                        data.ticketIds.forEach(id => {
                            html += `<li>${id}</li>`;
                        });
                        html += '</ul></div>';
                    }

                    if (data.order) {
                        html += '<div class="verified-order">';
                        html += `<p><strong>Event:</strong> ${data.order.eventName || 'Not specified'}</p>`;
                        html += `<p><strong>Quantity:</strong> ${data.order.quantity || 1} ticket(s)</p>`;
                        html += `<p><strong>Amount:</strong> ₦${(data.order.amount || 0).toLocaleString()}</p>`;
                        html += '</div>';
                    }

                    html += '</div>';
                    resultDiv.innerHTML = html;
                } else {
                    // Clean the result div and hide it - remove error message
                    resultDiv.innerHTML = '';
                    resultDiv.style.display = 'none';

                    // Just reset the form instead of showing error
                    document.getElementById('referenceInput').value = '';
                }
            })
            .catch(error => {
                // Hide the result div on error too
                resultDiv.innerHTML = '';
                resultDiv.style.display = 'none';
                console.error('Verification error:', error);
            });
        });
    });

    // Social sharing functions
    function shareOnFacebook() {
        const eventName = "{{ $eventName ?? 'an awesome event' }}";
        const shareUrl = window.location.origin;
        const shareText = `I just purchased tickets for ${eventName}! 🎉 Celebrating my ticket purchase! Join me for this amazing event!`;

        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}&quote=${encodeURIComponent(shareText)}`, '_blank');
    }

    function shareOnTwitter() {
        const eventName = "{{ $eventName ?? 'an awesome event' }}";
        const shareUrl = window.location.origin;
        const shareText = `I just purchased tickets for ${eventName}! 🎉 Celebrating my ticket purchase! #EventTickets #LiveEvents`;

        window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(shareText)}&url=${encodeURIComponent(shareUrl)}`, '_blank');
    }

    function shareOnWhatsapp() {
        const eventName = "{{ $eventName ?? 'an awesome event' }}";
        const shareUrl = window.location.origin;
        const shareText = `I just purchased tickets for ${eventName}! 🎉 Celebrating my ticket purchase! Join me for this amazing event!`;

        window.open(`https://wa.me/?text=${encodeURIComponent(shareText + ' ' + shareUrl)}`, '_blank');
    }
</script>

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

    .notification.info {
        background-color: rgba(13, 202, 240, 0.2);
        border: 1px solid rgba(13, 202, 240, 0.5);
        color: #d1f2fa;
    }

    .notification.info i {
        color: #0dcaf0;
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
        background: rgba(37, 36, 50, 0.7);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        border: 1px solid rgba(192, 72, 136, 0.3);
    }

    .order-summary h2 {
        font-size: 22px;
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

    /* Manual Verification Styles */
    .manual-verification-wrapper {
        width: 90%;
        max-width: 800px;
        margin: 30px auto;
    }

    .manual-verification-card {
        background: rgba(37, 36, 50, 0.8);
        color: #ffffff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        text-align: center;
        border: 1px solid rgba(192, 72, 136, 0.3);
    }

    .manual-verification-card h2 {
        font-size: 24px;
        margin-bottom: 10px;
        color: #C04888;
    }

    .manual-verification-card p {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 20px;
    }

    .verify-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 20px;
    }

    .form-group {
        width: 100%;
    }

    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        border-color: #C04888;
        box-shadow: 0 0 0 2px rgba(192, 72, 136, 0.3);
    }

    .verify-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 25px;
        background: #C04888;
        color: white;
        border-radius: 8px;
        font-weight: bold;
        text-decoration: none;
        font-size: 16px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        width: 100%;
    }

    .verify-btn:hover {
        background: #d658a0;
        transform: translateY(-2px);
    }

    .verification-result {
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
        text-align: left;
    }

    .verification-loading {
        display: flex;
        align-items: center;
        gap: 10px;
        color: rgba(255, 255, 255, 0.8);
    }

    .verification-success {
        background: rgba(40, 167, 69, 0.2);
        border: 1px solid rgba(40, 167, 69, 0.3);
        padding: 20px;
        border-radius: 8px;
    }

    .verification-success i {
        font-size: 30px;
        color: #28a745;
        margin-bottom: 10px;
        display: block;
    }

    .verified-tickets {
        margin-top: 15px;
    }

    .verified-tickets ul {
        list-style: none;
        padding: 0;
        margin: 10px 0;
    }

    .verified-tickets li {
        font-family: 'Courier New', monospace;
        padding: 8px;
        background: rgba(255, 255, 255, 0.1);
        margin-bottom: 5px;
        border-radius: 4px;
        font-weight: bold;
    }

    .verified-order {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Social Sharing Popup Styles */
    .social-sharing-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.5s ease;
    }

    .social-sharing-popup.active {
        opacity: 1;
        visibility: visible;
    }

    .social-sharing-content {
        background: rgba(37, 36, 50, 0.95);
        color: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        text-align: center;
        border: 2px solid #C04888;
        width: 90%;
        max-width: 500px;
        position: relative;
        animation: popIn 0.5s ease forwards;
    }

    @keyframes popIn {
        0% { transform: scale(0.8); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .close-popup {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 24px;
        cursor: pointer;
        color: rgba(255, 255, 255, 0.7);
        transition: all 0.3s ease;
    }

    .close-popup:hover {
        color: white;
        transform: scale(1.2);
    }

    .celebration-animation {
        margin: 20px 0;
        font-size: 40px;
        color: #C04888;
        display: flex;
        justify-content: center;
        gap: 30px;
    }

    .fa-bounce {
        animation-duration: 2s;
    }

    .fa-spin {
        animation-duration: 4s;
    }

    .fa-beat {
        animation-duration: 1.5s;
    }

    .social-sharing-content h2 {
        font-size: 28px;
        margin-bottom: 10px;
        color: white;
    }

    .social-sharing-content p {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 25px;
    }

    .social-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .social-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        transition: all 0.3s ease;
    }

    .social-btn:hover {
        transform: translateY(-5px);
    }

    .facebook {
        background: #3b5998;
    }

    .twitter {
        background: #000000;
    }

    .whatsapp {
        background: #25D366;
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

        .social-sharing-content {
            padding: 30px 20px;
        }

        .celebration-animation {
            font-size: 30px;
            gap: 20px;
        }

        .social-btn {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
    }
</style>
