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
<body>
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
</body>
</html>
