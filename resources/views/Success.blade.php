@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="main-container" data-reference="{{ $reference ?? '' }}">
    <!-- Notification Area -->

    <div class="success-wrapper">
        <div class="success-card">
            <div class="success-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <h1>Payment Successful!</h1>

            <p class="success-message">
                Your payment has been successfully processed. You will receive an email confirmation with your transaction details.
            </p>

            <div class="success-actions">
                <a href="/" class="home-btn">
                    <i class="fa-solid fa-house"></i> Back to Home
                </a>
            </div>
        </div>
    </div>

    <!-- Recommendations Section (Added) -->
    @if(isset($recommendations) && $recommendations->isNotEmpty())
    <div class="recommendations-section">
        <h2>Events You Might Like</h2>
        <div class="recommendations-grid">
            @foreach($recommendations as $event)
            <div class="recommendation-card">
                <div class="recommendation-image">
                    <img src="{{ $event->image }}" alt="{{ $event->name }}">
                </div>
                <div class="recommendation-content">
                    <h3>{{ $event->name }}</h3>
                    <p class="recommendation-location">
                        <i class="fa-solid fa-location-dot"></i> {{ $event->location }}
                    </p>
                    <a href="/listone/{{ $event->id }}" class="view-event-btn">View Event</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Social Sharing Popup -->
    <div id="socialSharingPopup" class="social-sharing-popup" style="display:none;">
        <div class="social-sharing-content">
            <div class="close-popup">&times;</div>

            <div class="celebration-header">
                <div class="confetti-explosion">
                    <i class="fa-solid fa-gifts fa-beat-fade"></i>
                    <i class="fa-solid fa-drum fa-shake"></i>
                    <i class="fa-solid fa-champagne-glasses fa-flip"></i>
                </div>
                <h1 class="celebration-title">
                    <span class="celebration-icon"><i class="fa-solid fa-crown fa-spin-pulse"></i></span>
                    Ticket Purchase Successful!
                    <span class="celebration-icon"><i class="fa-solid fa-crown fa-spin-pulse"></i></span>
                </h1>
                <div class="fireworks">
                    <div class="firework-burst burst1"></div>
                    <div class="firework-burst burst2"></div>
                    <div class="firework-burst burst3"></div>
                </div>
            </div>

            <div class="celebration-animation">
                <i class="fa-solid fa-ticket fa-bounce"></i>
                <i class="fa-solid fa-star fa-spin"></i>
                <i class="fa-solid fa-music fa-beat"></i>
            </div>
            <h2>Share Your {{ $eventName ?? 'Ticket' }} Purchase!</h2>
            <p>You're going to {{ $eventName ?? 'an amazing event' }}! Share with friends and invite them to join you.</p>

            <div class="ticket-preview">
                <div class="event-details">
                    <span class="event-name">{{ $eventName ?? 'Your Event' }}</span>
                    <span class="ticket-count">{{ $quantity ?? '1' }} {{ $quantity > 1 ? 'Tickets' : 'Ticket' }}</span>
                </div>
            </div>

            <div class="social-buttons">
                <a href="#" class="social-btn facebook" onclick="shareOnFacebook(); return false;">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="#" class="social-btn twitter" onclick="shareOnTwitter(); return false;">
                    <i class="fa-brands fa-x-twitter"></i>
                </a>
                <a href="#" class="social-btn whatsapp" onclick="shareOnWhatsapp(); return false;">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mark that we're on the success page
        localStorage.setItem('on_success_page', 'true');

        // First ensure the popup is hidden by default
        const popup = document.getElementById('socialSharingPopup');
        if (popup) {
            popup.style.display = 'none';
            popup.classList.remove('active');
        }

        // Show social sharing popup after 3 seconds
        setTimeout(function() {
            const popup = document.getElementById('socialSharingPopup');
            if (popup) {
                popup.style.display = 'flex';
                setTimeout(() => {
                    popup.classList.add('active');
                }, 10);
            }
        }, 3000);

        // Close popup when clicking the X button
        const closeBtn = document.querySelector('.close-popup');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                const popup = document.getElementById('socialSharingPopup');
                if (popup) {
                    popup.classList.remove('active');
                    setTimeout(() => {
                        popup.style.display = 'none';
                    }, 500);
                    clearAllTransactionFlags();
                }
            });
        }
    });

    // When user navigates away from this page, clear all flags
    window.addEventListener('beforeunload', function() {
        clearAllTransactionFlags();
    });

    // Function to clear all transaction-related flags
    function clearAllTransactionFlags() {
        localStorage.removeItem('successful_purchase');
        localStorage.removeItem('purchase_time');
        localStorage.removeItem('show_sharing_popup');
        localStorage.removeItem('on_success_page');
        localStorage.removeItem('redirect_to_success');
        console.log('All transaction flags cleared');
    }

    // Social sharing functions with event details
    function shareOnFacebook() {
        // Get event details
        const eventName = "{{ $eventName ?? 'an amazing event' }}";
        const ticketQuantity = "{{ $quantity ?? '1' }}";

        // Craft personalized message
        const text = ticketQuantity > 1
            ? `🎉 Just scored ${ticketQuantity} tickets to ${eventName} on Kaka Ticketing! Who's joining me for this epic experience? #KakaEvents #LiveEntertainment`
            : `🎉 Just scored my ticket to ${eventName} on Kaka Ticketing! Who's joining me for this epic experience? #KakaEvents #LiveEntertainment`;

        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}&quote=${encodeURIComponent(text)}`, '_blank');
    }

    function shareOnTwitter() {
        // Get event details
        const eventName = "{{ $eventName ?? 'an amazing event' }}";
        const ticketQuantity = "{{ $quantity ?? '1' }}";
        const eventDate = "{{ date('F d', strtotime($date ?? now())) }}";

        // Craft personalized message
        const text = `🎟️ Just locked in ${ticketQuantity > 1 ? 'tickets' : 'my spot'} for ${eventName} on ${eventDate}! Find me in the crowd! Get your tickets on Kaka before they're gone! #EventLife`;

        window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(window.location.href)}`, '_blank');
    }

    function shareOnWhatsapp() {
        // Get event details
        const eventName = "{{ $eventName ?? 'an amazing event' }}";
        const ticketQuantity = "{{ $quantity ?? '1' }}";
        const amount = "{{ number_format($amount ?? 0) }}";

        // Craft personalized message
        const text = `Hey! 🎉 I just got ${ticketQuantity > 1 ? ticketQuantity + ' tickets' : 'a ticket'} to ${eventName} via Kaka Ticketing! Would be awesome if you could join me. Check it out and let's make some memories together! 🎵🎊`;

        window.open(`https://wa.me/?text=${encodeURIComponent(text + ' ' + window.location.href)}`, '_blank');
    }
</script>

@endsection

<style>
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

    /* Recommendations Section Styles */
    .recommendations-section {
        width: 90%;
        max-width: 1000px;
        margin: 60px auto;
        background: rgba(37, 36, 50, 0.8);
        border-radius: 12px;
        padding: 30px;
        border: 1px solid rgba(192, 72, 136, 0.3);
    }

    .recommendations-section h2 {
        font-size: 24px;
        color: #ffffff;
        margin-bottom: 20px;
        text-align: center;
    }

    .recommendations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }

    .recommendation-card {
        background: rgba(50, 49, 66, 0.6);
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(192, 72, 136, 0.2);
    }

    .recommendation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        border-color: rgba(192, 72, 136, 0.5);
    }

    .recommendation-image {
        height: 120px;
        overflow: hidden;
    }

    .recommendation-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .recommendation-card:hover .recommendation-image img {
        transform: scale(1.1);
    }

    .recommendation-content {
        padding: 15px;
    }

    .recommendation-content h3 {
        font-size: 16px;
        color: #ffffff;
        margin-bottom: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .recommendation-location {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .view-event-btn {
        display: block;
        background: #C04888;
        color: white;
        text-align: center;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .view-event-btn:hover {
        background: #d658a0;
    }

    /* Social Sharing Popup Styles */
    .social-sharing-popup {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        transition: opacity 0.5s ease;
        opacity: 0;
    }

    .social-sharing-popup.active {
        display: flex !important;
        opacity: 1;
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
        overflow: hidden;
    }

    /* Celebration header styles */
    .celebration-header {
        position: relative;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .celebration-title {
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        text-shadow: 0 0 10px rgba(192, 72, 136, 0.7);
        margin: 0 0 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: linear-gradient(90deg, #ff6b6b, #C04888, #4ecdc4);
        background-size: 200% auto;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: gradientText 3s ease infinite;
    }

    @keyframes gradientText {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .celebration-icon {
        font-size: 20px;
        color: #FFD700;
    }

    /* Confetti animation */
    .confetti-explosion {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-bottom: 15px;
        font-size: 28px;
    }

    .confetti-explosion i {
        color: #FFD700;
    }

    .confetti-explosion i:nth-child(2) {
        color: #FF6B6B;
    }

    .confetti-explosion i:nth-child(3) {
        color: #4ECDC4;
    }

    .fa-beat-fade {
        animation-duration: 2s;
    }

    .fa-shake {
        animation-duration: 1.5s;
    }

    .fa-flip {
        animation-duration: 2.5s;
    }

    /* Fireworks */
    .fireworks {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .firework-burst {
        position: absolute;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        box-shadow:
            0 0 0 4px rgba(255, 200, 0, 0.1),
            0 0 0 8px rgba(255, 0, 100, 0.1),
            0 0 0 12px rgba(0, 200, 255, 0.1),
            0 0 20px rgba(255, 200, 0, 0.5),
            0 0 40px rgba(255, 0, 100, 0.5),
            0 0 60px rgba(0, 200, 255, 0.5);
        transform-origin: center;
    }

    .burst1 {
        top: 20%;
        left: 20%;
        animation: fireworkEffect 2s ease-out infinite;
        animation-delay: 0.3s;
    }

    .burst2 {
        top: 30%;
        right: 20%;
        animation: fireworkEffect 2.5s ease-out infinite;
        animation-delay: 0.7s;
    }

    .burst3 {
        bottom: 30%;
        left: 50%;
        animation: fireworkEffect 1.8s ease-out infinite;
        animation-delay: 1.2s;
    }

    @keyframes fireworkEffect {
        0% {
            transform: scale(0.1);
            opacity: 0;
        }
        20% {
            opacity: 1;
        }
        80% {
            transform: scale(1.8);
            opacity: 0;
        }
        100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    /* Ticket preview styles */
    .ticket-preview {
        background: rgba(45, 44, 60, 0.6);
        border-radius: 8px;
        border: 1px dashed rgba(255, 255, 255, 0.2);
        padding: 15px;
        margin: 15px 0 25px;
        position: relative;
        overflow: hidden;
    }

    .ticket-preview:before {
        content: '';
        position: absolute;
        top: -10px;
        right: -10px;
        width: 40px;
        height: 40px;
        background: #C04888;
        transform: rotate(45deg);
        z-index: 0;
    }

    .event-details {
        display: flex;
        flex-direction: column;
        gap: 8px;
        position: relative;
        z-index: 1;
    }

    .event-name {
        font-weight: bold;
        font-size: 18px;
        color: #fff;
    }

    .ticket-count {
        display: inline-block;
        background: rgba(192, 72, 136, 0.2);
        color: #C04888;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
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

        .recommendations-section {
            padding: 20px 15px;
        }

        .recommendations-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .recommendations-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
