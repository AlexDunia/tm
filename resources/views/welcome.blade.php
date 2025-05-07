@extends('layouts.app')

@section('content')
<div class="main-container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div id="newslide" class="slideshow-container">
            @foreach($welcome->take(3)->reverse() as $index => $onewelcome)
            <div class="newslideshow fade">
                <div class="slide-image" style="background-image: url('{{ str_starts_with($onewelcome->heroimage, 'http') ? $onewelcome->heroimage : asset('storage/' . $onewelcome->heroimage) }}')">
                    <div class="image-overlay"></div>
                </div>
                <a href="/events/{{$onewelcome->name}}" class="slide-content">{{$onewelcome->name}}</a>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Search Section -->
    <section class="search-section">
        <div class="searchbox">
            <div class="selectandsearch">
                <form class="example" type="get" action="{{url('/search')}}">
                    <input type="text" placeholder="Search.." name="name" class="custom-input">
                    <i class="fas fa-search search-icon"></i>
                </form>
            </div>
        </div>
    </section>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Events Section -->
        <section class="events-section">
            <div class="circularbg">
                <div class="view">
                    <div class="latest">
                        <h1>Trending Events</h1>
                    </div>

                    <div class="t_grids">
                        @foreach($welcome as $onewelcome)
                        <div class="one_e">
                            <ul>
                                <li class="mypimage"><img src="{{ str_starts_with($onewelcome->image, 'http') ? $onewelcome->image : asset('storage/' . $onewelcome->image) }}"></li>
                                <li class="noe">{{$onewelcome->name}}</li>
                                <div class="toe"><i class="fa-solid fa-location-dot"></i> {{$onewelcome->location}}</div>
                                <div class="toe"><i class="fa-solid fa-calendar-days"></i> {{ date('n/j/Y', strtotime($onewelcome->date)) }}</div>
                                <div class="toe"><i class="fa-solid fa-ticket"></i> Starting @5000</div>
                                <button class="b_t"><a href="/events/{{$onewelcome->name}}">View Event</a></button>
                            </ul>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- Pagination Section -->
        <section class="pagination-section">
            <div class="pagination-container">
                <div class="pagination-wrapper">
                    @include('_paginate')
                    {{ $welcome->links() }}
                </div>
            </div>
        </section>
    </div>

    <!-- Social Sharing Popup with Celebratory Design -->
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
            <h2>Share Your Event Experience!</h2>
            <p>Share with friends and invite them to join you at the next event!</p>

            <div class="ticket-preview">
                <div class="event-details">
                    <span class="event-name">Kaka Tickets</span>
                    <span class="ticket-count">Your VIP Access</span>
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
@endsection

@section('scripts')
<script>
var slideIndex = 0;
showSlides();

function showSlides() {
    var slides = document.getElementsByClassName("newslideshow");
    var contents = document.getElementsByClassName("slide-content");
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }
    for (var i = 0; i < slides.length; i++) {
        slides[i].style.opacity = 0;
    }
    slides[slideIndex - 1].style.opacity = 1;

    // Make sure the slide-content is visible in the active slide only
    document.querySelectorAll('.slide-content').forEach(function(content) {
        content.style.opacity = "0";
    });

    if (contents.length > 0 && slideIndex <= contents.length) {
        contents[slideIndex - 1].style.opacity = "1";
    }

    setTimeout(showSlides, 5000);
}

// Check for successful purchase and redirect to success page
document.addEventListener('DOMContentLoaded', function() {
    const successfulPurchase = localStorage.getItem('successful_purchase');
    const purchaseTime = localStorage.getItem('purchase_time');
    const showSharingPopup = localStorage.getItem('show_sharing_popup');

    // First ensure the popup is hidden by default
    const popup = document.getElementById('socialSharingPopup');
    if (popup) {
        popup.style.display = 'none';
        popup.classList.remove('active');
    }

    // Only show if purchase was within last 5 minutes and flags are set
    if (successfulPurchase === 'true' && showSharingPopup === 'true') {
        const purchaseTimestamp = parseInt(purchaseTime || '0');
        const currentTime = Date.now();
        const timeElapsed = currentTime - purchaseTimestamp;

        if (timeElapsed < 300000) { // 5 minutes in ms
            // Show popup after delay
            setTimeout(function() {
                if (popup) {
                    popup.style.display = 'flex';
                    popup.style.zIndex = '99999';
                    document.body.classList.add('modal-blur-active');
                    setTimeout(() => {
                        popup.classList.add('active');
                    }, 10);

                    // Auto-close after 8 seconds
                    setTimeout(function() {
                        popup.classList.remove('active');
                        setTimeout(() => {
                            popup.style.display = 'none';
                            document.body.classList.remove('modal-blur-active');
                        }, 500);
                        clearAllTransactionFlags();
                    }, 8000);
                }
            }, 3000);
        }
        // Always clear flags after checking, so it doesn't show again
        clearAllTransactionFlags();
    }

    // Close popup when clicking the X button
    const closeBtn = document.querySelector('.close-popup');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            if (popup) {
                popup.classList.remove('active');
                setTimeout(() => {
                    popup.style.display = 'none';
                    document.body.classList.remove('modal-blur-active');
                }, 500);
                clearAllTransactionFlags();
            }
        });
    }
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

// Social sharing functions with personalized messages
function shareOnFacebook() {
    // Craft personalized message
    const text = "🎉 Just scored amazing tickets on Kaka Ticketing! Who's joining me for this epic experience? #KakaEvents #LiveEntertainment";
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}&quote=${encodeURIComponent(text)}`, '_blank');
}

function shareOnTwitter() {
    // Craft personalized message
    const text = `🎟️ Just locked in my spot for an incredible event! Find me in the crowd! Get your tickets on Kaka before they're gone! #EventLife`;
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(window.location.href)}`, '_blank');
}

function shareOnWhatsapp() {
    // Craft personalized message
    const text = `Hey! 🎉 I just got tickets to an amazing event via Kaka Ticketing! Would be awesome if you could join me. Check it out and let's make some memories together! 🎵🎊`;
    window.open(`https://wa.me/?text=${encodeURIComponent(text + ' ' + window.location.href)}`, '_blank');
}
</script>

<style>
/* Inline styles to ensure they take effect immediately */
.hero-section {
    position: relative;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1;
}

.slide-content {
    position: absolute;
    left: 8%;
    transform: none;
    color: #ffffff !important;
    text-align: left;
    font-size: 3rem;
    font-weight: 700;
    text-decoration: none;
    text-shadow: none !important;
    z-index: 100;
    width: 80%;
}

.search-section {
    z-index: 100;
    position: relative;
}

/* Enhanced pagination styles */
.pagination-container {
    display: flex;
    justify-content: center;
    margin: 40px auto;
    width: 100%;
}

.pagination-wrapper {
    width: 90%;
    max-width: 720px;
    background: rgba(37, 36, 50, 0.7);
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    justify-content: center;
    gap: 8px;
}

.pagination .page-item .page-link {
    background-color: #252432;
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #ffffff;
    padding: 10px 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
    text-decoration: none;
    font-weight: 500;
}

.pagination .page-item .page-link:hover {
    background-color: rgba(192, 72, 136, 0.3);
    border-color: rgba(192, 72, 136, 0.5);
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background-color: #c04888;
    border-color: #c04888;
    color: white;
}

.pagination .page-item.disabled .page-link {
    background-color: rgba(37, 36, 50, 0.5);
    color: rgba(255, 255, 255, 0.4);
    border-color: transparent;
    cursor: not-allowed;
}

/* Small screen responsiveness */
@media (max-width: 640px) {
    .pagination-wrapper {
        width: 95%;
        padding: 10px;
    }

    .pagination .page-item .page-link {
        padding: 8px 12px;
        font-size: 14px;
    }
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

@keyframes popIn {
    0% { transform: scale(0.8); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

/* Small screen responsiveness */
@media (max-width: 700px) {
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

/* Blur effect for modal */
body.modal-blur-active .main-container,
body.modal-blur-active .navbar,
body.modal-blur-active .header,
body.modal-blur-active .footer {
    filter: blur(6px) grayscale(0.2) brightness(0.8);
    pointer-events: none;
    user-select: none;
}

.social-sharing-popup {
    z-index: 99999 !important;
}
</style>
@endsection

