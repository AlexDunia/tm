<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($metaTitle) ? $metaTitle : config('app.name', 'KakaWorld') }}</title>
    <meta name="description" content="{{ isset($metaDescription) ? $metaDescription : 'Find and book tickets for events, concerts, theater, and more in Nigeria.' }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ isset($canonicalUrl) ? $canonicalUrl : url()->current() }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ isset($metaType) ? $metaType : 'website' }}">
    <meta property="og:url" content="{{ isset($canonicalUrl) ? $canonicalUrl : url()->current() }}">
    <meta property="og:title" content="{{ isset($metaTitle) ? $metaTitle : config('app.name', 'KakaWorld') }}">
    <meta property="og:description" content="{{ isset($metaDescription) ? $metaDescription : 'Find and book tickets for events, concerts, theater, and more in Nigeria.' }}">
    <meta property="og:image" content="{{ isset($metaImage) ? $metaImage : asset('images/default-share.jpg') }}">
    <meta property="og:site_name" content="KakaWorld">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ isset($canonicalUrl) ? $canonicalUrl : url()->current() }}">
    <meta name="twitter:title" content="{{ isset($metaTitle) ? $metaTitle : config('app.name', 'KakaWorld') }}">
    <meta name="twitter:description" content="{{ isset($metaDescription) ? $metaDescription : 'Find and book tickets for events, concerts, theater, and more in Nigeria.' }}">
    <meta name="twitter:image" content="{{ isset($metaImage) ? $metaImage : asset('images/default-share.jpg') }}">

    <!-- Additional SEO Meta Tags -->
    <meta name="keywords" content="events, tickets, concerts, theater, Nigeria, kaka, kakaworld, event tickets, {{ isset($metaKeywords) ? $metaKeywords : '' }}">
    <meta name="author" content="KakaWorld">
    <meta name="robots" content="index, follow">

    <!-- Structured Data for Events -->
    @if(isset($listonee) && isset($metaType) && $metaType == 'event')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Event",
        "name": "{{ $listonee->name }}",
        "description": "{{ isset($listonee->description) ? str_replace('"', '\"', $listonee->description) : 'Event details' }}",
        "image": "{{ isset($metaImage) ? $metaImage : asset('images/default-share.jpg') }}",
        "startDate": "{{ $listonee->date ?? 'TBD' }}",
        "endDate": "{{ $listonee->end_date ?? $listonee->date ?? 'TBD' }}",
        "location": {
            "@type": "Place",
            "name": "{{ $listonee->venue ?? $listonee->location ?? 'TBD' }}",
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "{{ $listonee->city ?? $listonee->location ?? 'Nigeria' }}"
            }
        },
        "offers": {
            "@type": "AggregateOffer",
            "priceCurrency": "NGN",
            "url": "{{ url()->current() }}",
            "availability": "https://schema.org/InStock"
        },
        "organizer": {
            "@type": "Organization",
            "name": "KakaWorld",
            "url": "{{ url('/') }}"
        }
    }
    </script>
    @else
    <!-- Structured Data for Organization -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "KakaWorld",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+234-XXX-XXX-XXXX",
            "contactType": "customer service"
        },
        "sameAs": [
            "https://www.facebook.com/kakaworld",
            "https://www.twitter.com/kakaworld",
            "https://www.instagram.com/kakaworld"
        ]
    }
    </script>
    @endif

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="\css\admin.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
    <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"></script>

    <!-- Additional Libraries -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
        integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/session-manager.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/cart-handler.js') }}" defer></script>

    <style>
        body {
            background-color: #13121a;
            color: white;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
        }

        .site-footer {
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #a0aec0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Critical fix to ensure cards are visible */
        .one_e {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        /* Global Notification System */
        .global-notifications {
            position: fixed;
            top: 80px;
            right: 20px;
            width: 350px;
            max-width: 90%;
            z-index: 9999;
        }

        .global-notification {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            animation: slideIn 0.5s ease-in-out;
            transition: all 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .global-notification:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .notification-content {
            display: flex;
            align-items: center;
            flex: 1;
        }

        .notification-content i {
            font-size: 24px;
            margin-right: 15px;
        }

        .notification-content p {
            margin: 0;
            font-size: 15px;
            line-height: 1.4;
        }

        .close-notification {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: flex-start;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .close-notification:hover {
            opacity: 1;
        }

        .global-notification.error {
            background-color: rgba(220, 53, 69, 0.95);
            border-left: 5px solid #dc3545;
            color: white;
        }

        .global-notification.warning {
            background-color: rgba(255, 193, 7, 0.95);
            border-left: 5px solid #ffc107;
            color: #212529;
        }

        .global-notification.success {
            background-color: rgba(40, 167, 69, 0.95);
            border-left: 5px solid #28a745;
            color: white;
        }

        @media (max-width: 576px) {
            .global-notifications {
                top: 70px;
                right: 10px;
                left: 10px;
                width: auto;
            }
        }

        .close-popup {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 28px;
            cursor: pointer;
            color: #ffffff;
            background: rgba(192, 72, 136, 0.8);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 10;
        }

        .close-popup:hover {
            color: white;
            transform: scale(1.2);
            background: rgba(192, 72, 136, 1);
        }
    </style>
</head>
<body class="{{ Auth::check() ? 'user-authenticated' : 'guest' }}">
    <div id="app">
        <!-- Global Header -->
        @include('_header')

        <!-- Global Notification System -->
        @if(session('error') || session('warning') || session('success') || session('payment_warning'))
        <div class="global-notifications">
            @if(session('error'))
            <div class="global-notification error">
                <div class="notification-content">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <p>{{ session('error') }}</p>
                </div>
                <button class="close-notification" onclick="this.parentElement.style.display='none';">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @if(session('warning') || session('payment_warning'))
            <div class="global-notification warning">
                <div class="notification-content">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <p>{{ session('warning') ?? session('payment_warning') }}</p>
                </div>
                <button class="close-notification" onclick="this.parentElement.style.display='none';">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @if(session('success'))
            <div class="global-notification success">
                <div class="notification-content">
                    <i class="fa-solid fa-circle-check"></i>
                    <p>{{ session('success') }}</p>
                </div>
                <button class="close-notification" onclick="this.parentElement.style.display='none';">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif
        </div>
        @endif

        @yield('content')

        <!-- Global Footer -->
        <footer class="site-footer">
            @include('_footer')
        </footer>
    </div>

    @yield('scripts')

    <!-- Notification Auto-Hide Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide notifications after 5 seconds
            const notifications = document.querySelectorAll('.global-notification');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(50px)';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 500);
                }, 5000);
            });
        });
    </script>

    <!-- Social sharing popup will appear on home page after successful purchase -->

    <!-- Show social sharing popup on home page after successful transaction -->
    <script>
        // Ensure all modals can be closed with a global function
        function clearAllModalData() {
            // Clear all transaction-related flags
            localStorage.removeItem('successful_purchase');
            localStorage.removeItem('purchase_time');
            localStorage.removeItem('show_sharing_popup');
            localStorage.removeItem('on_success_page');
            localStorage.removeItem('redirect_to_success');
            console.log('All transaction flags cleared');

            // Find and remove any sharing popup in the DOM
            const popup = document.getElementById('socialSharingPopup');
            if (popup) {
                popup.classList.remove('active');
                setTimeout(() => {
                    popup.style.display = 'none';
                    popup.remove();
                }, 500);
            }
        }

        // Global document event listener for any close button clicks
        document.addEventListener('click', function(event) {
            // Handle clicking the X button
            if (event.target.classList.contains('close-popup') ||
                event.target.closest('.close-popup')) {
                console.log('Close button clicked');
                clearAllModalData();
                return;
            }

            // Handle clicking outside the modal
            const popup = document.getElementById('socialSharingPopup');
            if (popup && event.target === popup) {
                console.log('Clicked outside modal content, closing popup');
                clearAllModalData();
            }
        });

        // Check if we're on the home page and there was a successful purchase
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Current path:', window.location.pathname);
            console.log('Purchase flag:', localStorage.getItem('successful_purchase'));

            if (window.location.pathname === '/' && localStorage.getItem('successful_purchase')) {
                // Check if the purchase was recent (within the last 5 minutes)
                const purchaseTime = parseInt(localStorage.getItem('purchase_time') || '0');
                const currentTime = Date.now();
                const timeElapsed = currentTime - purchaseTime;
                console.log('Time elapsed since purchase:', timeElapsed);

                // If purchase was within last 5 minutes (300000 ms)
                if (timeElapsed < 300000) {
                    // Try to find the sharing popup
                    const sharingPopup = document.querySelector('.social-sharing-popup');
                    console.log('Found existing popup:', !!sharingPopup);

                    if (sharingPopup) {
                        // Show the popup after 5-8 seconds
                        setTimeout(function() {
                            sharingPopup.classList.add('active');
                            console.log('Showing existing popup');
                        }, Math.floor(Math.random() * (8000 - 5000 + 1)) + 5000);
                    } else {
                        // If the popup doesn't exist in the DOM, create it dynamically
                        const popupHTML = `
                        <div id="socialSharingPopup" class="social-sharing-popup active">
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
                        </div>`;

                        // Add the popup styles if they don't exist
                        if (!document.querySelector('style#socialSharingStyles')) {
                            const popupStyles = `
                            <style id="socialSharingStyles">
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
                                    font-size: 28px;
                                    cursor: pointer;
                                    color: #ffffff;
                                    background: rgba(192, 72, 136, 0.8);
                                    width: 40px;
                                    height: 40px;
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                                    transition: all 0.3s ease;
                                    z-index: 10;
                                }

                                .close-popup:hover {
                                    color: white;
                                    transform: scale(1.2);
                                    background: rgba(192, 72, 136, 1);
                                }

                                .celebration-animation {
                                    margin: 20px 0;
                                    font-size: 40px;
                                    color: #C04888;
                                    display: flex;
                                    justify-content: center;
                                    gap: 30px;
                                }

                                .fa-bounce { animation-duration: 2s; }
                                .fa-spin { animation-duration: 4s; }
                                .fa-beat { animation-duration: 1.5s; }

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

                                .facebook { background: #3b5998; }
                                .twitter { background: #000000; }
                                .whatsapp { background: #25D366; }
                            </style>`;

                            document.head.insertAdjacentHTML('beforeend', popupStyles);
                        }

                        // Add the popup to the body after a delay
                        setTimeout(function() {
                            document.body.insertAdjacentHTML('beforeend', popupHTML);
                            console.log('Created and added dynamic popup');

                            // Close popup when clicking outside the modal content
                            const popup = document.getElementById('socialSharingPopup');
                            if (popup) {
                                popup.addEventListener('click', function(event) {
                                    // If the clicked element is the popup background (not its children)
                                    if (event.target === popup) {
                                        console.log('Background clicked, closing popup');
                                        clearAllModalData();
                                    }
                                });

                                // Make sure the popup is actually visible with the right styling
                                setTimeout(() => {
                                    popup.style.display = 'flex';
                                    popup.style.opacity = '1';
                                    popup.style.visibility = 'visible';
                                }, 100);
                            }
                        }, Math.floor(Math.random() * (3000 - 1000 + 1)) + 1000);
                    }
                } else {
                    // Purchase is too old, clear the flags
                    console.log('Purchase is too old, clearing flags');
                    localStorage.removeItem('successful_purchase');
                    localStorage.removeItem('purchase_time');
                }
            }
        });

        // Social sharing functions (for dynamically created popup)
        function shareOnFacebook() {
            const text = "I just purchased tickets using the Kaka Ticketing Platform! Can't wait for the event.";
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}&quote=${encodeURIComponent(text)}`, '_blank');
            clearAllModalData();
        }

        function shareOnTwitter() {
            const text = "I just purchased tickets using the Kaka Ticketing Platform! Can't wait for the event.";
            window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(window.location.href)}`, '_blank');
            clearAllModalData();
        }

        function shareOnWhatsapp() {
            const text = "I just purchased tickets using the Kaka Ticketing Platform! Can't wait for the event.";
            window.open(`https://wa.me/?text=${encodeURIComponent(text + ' ' + window.location.href)}`, '_blank');
            clearAllModalData();
        }
    </script>
</body>
</html>
