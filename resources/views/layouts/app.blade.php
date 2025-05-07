<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($metaTitle) ? $metaTitle : config('app.name', 'Laravel') }}</title>
    <meta name="description" content="{{ isset($metaDescription) ? $metaDescription : 'Find and book tickets for events, concerts, theater, and more.' }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ isset($metaType) ? $metaType : 'website' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ isset($metaTitle) ? $metaTitle : config('app.name', 'Laravel') }}">
    <meta property="og:description" content="{{ isset($metaDescription) ? $metaDescription : 'Find and book tickets for events, concerts, theater, and more.' }}">
    <meta property="og:image" content="{{ isset($metaImage) ? $metaImage : asset('images/default-share.jpg') }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ isset($metaTitle) ? $metaTitle : config('app.name', 'Laravel') }}">
    <meta name="twitter:description" content="{{ isset($metaDescription) ? $metaDescription : 'Find and book tickets for events, concerts, theater, and more.' }}">
    <meta name="twitter:image" content="{{ isset($metaImage) ? $metaImage : asset('images/default-share.jpg') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="\\css\\admin.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
    <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"></script>

    <!-- Additional Libraries -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
        integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

    <!-- Scripts -->

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/session-manager.js') }}"></script>

    <style>
        body {
            background-color: #13121a;
            color: white;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
        }

        .site-main {
            min-height: calc(100vh - 200px);
            padding-bottom: 50px;
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

        <main class="site-main">
            @yield('content')
        </main>

        <!-- Global Footer -->
        <footer class="site-footer">
            @include('_footer')
        </footer>
    </div>

    <!-- Bootstrap JS Bundle (includes Popper) CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    @stack('scripts')

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
        // Check if we're on the home page and there was a successful purchase
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.pathname === '/' && localStorage.getItem('successful_purchase')) {
                // Check if the purchase was recent (within the last 5 minutes)
                const purchaseTime = parseInt(localStorage.getItem('purchase_time') || '0');
                const currentTime = Date.now();
                const timeElapsed = currentTime - purchaseTime;

                // If purchase was within last 5 minutes (300000 ms)
                if (timeElapsed < 300000) {
                    // Try to find the sharing popup
                    const sharingPopup = document.querySelector('.social-sharing-popup');

                    if (sharingPopup) {
                        // Show the popup after 5-8 seconds
                        setTimeout(function() {
                            sharingPopup.classList.add('active');
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

                            // Add event listener for the close button
                            const closeButton = document.querySelector('.close-popup');
                            if (closeButton) {
                                closeButton.addEventListener('click', function() {
                                    const popup = document.getElementById('socialSharingPopup');
                                    if (popup) {
                                        popup.classList.remove('active');
                                        // Remove the purchase flag after closing
                                        localStorage.removeItem('successful_purchase');
                                        localStorage.removeItem('purchase_time');
                                    }
                                });
                            }
                        }, Math.floor(Math.random() * (8000 - 5000 + 1)) + 5000);
                    }
                } else {
                    // Purchase is too old, clear the flags
                    localStorage.removeItem('successful_purchase');
                    localStorage.removeItem('purchase_time');
                }
            }
        });

        // Social sharing functions (for dynamically created popup)
        function shareOnFacebook() {
            const text = "I just purchased tickets using the Kaka Ticketing Platform! Can't wait for the event.";
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}&quote=${encodeURIComponent(text)}`, '_blank');
            localStorage.removeItem('successful_purchase');
            localStorage.removeItem('purchase_time');
        }

        function shareOnTwitter() {
            const text = "I just purchased tickets using the Kaka Ticketing Platform! Can't wait for the event.";
            window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(window.location.href)}`, '_blank');
            localStorage.removeItem('successful_purchase');
            localStorage.removeItem('purchase_time');
        }

        function shareOnWhatsapp() {
            const text = "I just purchased tickets using the Kaka Ticketing Platform! Can't wait for the event.";
            window.open(`https://wa.me/?text=${encodeURIComponent(text + ' ' + window.location.href)}`, '_blank');
            localStorage.removeItem('successful_purchase');
            localStorage.removeItem('purchase_time');
        }
    </script>
</body>
</html>
