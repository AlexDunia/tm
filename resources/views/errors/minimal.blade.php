<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="X-Frame-Options" content="DENY">
<meta http-equiv="X-XSS-Protection" content="1; mode=block">
<meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' https://unpkg.com https://kit.fontawesome.com 'unsafe-inline'; style-src 'self' https://fonts.googleapis.com 'unsafe-inline'; font-src 'self' https://fonts.gstatic.com; img-src 'self' https://res.cloudinary.com data:; connect-src 'self'; frame-src 'none'; object-src 'none';">
<meta name="referrer" content="strict-origin-when-cross-origin">
<title>@yield('title', 'Error') | Kaka</title>
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/css/admin.css">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="assets/img/favicon.ico" rel="icon">
<script src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
<script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"></script>
</head>
<style>
    body {
        padding: 0;
        margin: 0;
        color: white;
        background: #13121a;
        font-family: 'Inter', sans-serif;
    }

    .site-header {
        background-color: #0d0c11;
        position: sticky;
        top: 0;
        z-index: 1000;
        width: 100%;
        border-bottom: 1px solid #2d2d42;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 80px;
    }

    .header-logo {
        flex: 0 0 auto;
        margin-right: 30px;
    }

    .logo-link {
        display: block;
    }

    .logo-image {
        width: auto;
        height: 40px;
        transform: scale(1.1);
    }

    .error-container {
        max-width: 650px;
        margin: 80px auto;
        padding: 40px;
        background: rgba(38, 37, 54, 0.5);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        animation: fadeIn 0.8s ease-out;
        text-align: center;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .error-icon {
        font-size: 5rem;
        margin-bottom: 20px;
        color: #C04888;
    }

    .error-code {
        display: inline-block;
        background: linear-gradient(to right, #C04888, #a73672);
        color: white;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 30px;
        margin-bottom: 20px;
    }

    .error-title {
        font-size: 2.5rem;
        margin: 0 0 20px 0;
        color: white;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .error-message {
        font-size: 1.1rem;
        margin-bottom: 30px;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
    }

    .timer {
        font-size: 2rem;
        font-weight: 700;
        color: #C04888;
        margin: 20px 0;
    }

    .action-button {
        display: inline-block;
        padding: 14px 28px;
        background: linear-gradient(to right, #C04888, #a73672);
        color: white;
        font-size: 1rem;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        margin: 5px;
    }

    .action-button.secondary {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(192, 72, 136, 0.4);
    }

    .action-button.secondary:hover {
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 6px 20px rgba(255, 255, 255, 0.1);
    }

    .steps {
        max-width: 450px;
        margin: 30px auto;
        text-align: left;
    }

    .steps-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: white;
    }

    .step {
        display: flex;
        margin-bottom: 15px;
        align-items: flex-start;
    }

    .step-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        background: rgba(192, 72, 136, 0.2);
        border-radius: 50%;
        color: #C04888;
        font-weight: 600;
        font-size: 0.9rem;
        margin-right: 15px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .step-text {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .error-container {
            margin: 40px 20px;
            padding: 30px 20px;
        }

        .error-title {
            font-size: 2rem;
        }

        .error-icon {
            font-size: 4rem;
        }
    }
</style>
<body>
    <header class="site-header">
        <div class="header-container">
            <div class="header-content">
                <!-- Logo -->
                <div class="header-logo">
                    <a href="/" class="logo-link">
                        <img src="https://res.cloudinary.com/dnuhjsckk/image/upload/v1746062122/tdlogo_bmlpd8.png" alt="Kaka Logo" class="logo-image">
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="error-container">
        <i class="@yield('icon', 'fas fa-exclamation-circle') error-icon"></i>
        <div class="error-code">Error @yield('code')</div>
        <h1 class="error-title">@yield('title', 'Error')</h1>
        <p class="error-message">@yield('message', 'Something went wrong.')</p>

        @yield('content')

        <div class="action-buttons">
            @yield('buttons', '')
            @if(!trim(View::yieldContent('buttons')))
                <a href="/" class="action-button">Go Home</a>
                <a href="javascript:history.back()" class="action-button secondary">Go Back</a>
            @endif
        </div>
    </div>
</body>
</html>
