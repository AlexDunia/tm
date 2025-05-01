<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Log in | Kaka</title>
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/css/admin.css">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="assets/img/favicon.ico" rel="icon">
<script src="//unpkg.com/alpinejs" defer></script>
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
        background-color: #1c1c28;
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

    .main-nav {
        flex: 1 1 auto;
    }

    .nav-list {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-item {
        margin: 0 10px;
    }

    .nav-link {
        color: #fff;
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        padding: 10px 0;
        position: relative;
        transition: all 0.3s ease;
    }

    .nav-link:hover, .nav-item.active .nav-link {
        color: #C04888;
    }

    .auth-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .login-btn, .signup-btn {
        font-weight: 600;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 4px;
        transition: all 0.3s ease;
        display: inline-block;
        text-align: center;
        font-size: 14px;
    }

    .login-btn {
        color: white;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .login-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .signup-btn {
        background-color: #C04888;
        color: white;
        border: none;
    }

    .signup-btn:hover {
        background-color: #a73672;
    }

    /* Mobile menu toggle */
    .mobile-menu-toggle {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 20px;
        position: relative;
        margin-left: 20px;
    }

    .menu-bar {
        display: block;
        width: 100%;
        height: 2px;
        background-color: white;
        position: absolute;
        left: 0;
        transition: all 0.3s ease;
    }

    .menu-bar:nth-child(1) {
        top: 0;
    }

    .menu-bar:nth-child(2) {
        top: 50%;
        transform: translateY(-50%);
    }

    .menu-bar:nth-child(3) {
        bottom: 0;
    }

    /* Login Form Styles */
    .site-main {
        min-height: calc(100vh - 80px);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 20px;
    }

    .tyaround {
        width: 90%;
        max-width: 450px;
        margin: 30px auto;
        padding: 40px;
        background: rgba(38, 37, 54, 0.5);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        animation: fadeIn 0.8s ease-out;
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

    .tyaround h1 {
        font-size: 2.5rem;
        margin: 0 0 15px 0;
        color: white;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .tyaround p {
        font-size: 1rem;
        margin-bottom: 30px;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 22px;
        position: relative;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: white;
        font-size: 0.95rem;
    }

    .form-input {
        width: 100%;
        padding: 16px;
        background: rgba(37, 36, 50, 0.5);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        font-size: 16px;
        color: white;
        transition: all 0.3s ease;
        box-sizing: border-box;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) inset;
    }

    .form-input:focus {
        border-color: #C04888;
        outline: none;
        box-shadow: 0 0 0 3px rgba(192, 72, 136, 0.3);
    }

    .form-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    .input-icon {
        position: absolute;
        right: 16px;
        top: 40px;
        color: rgba(255, 255, 255, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .input-icon:hover {
        color: #C04888;
    }

    .forgot-password {
        color: #C04888;
        font-weight: 500;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-block;
        margin-top: 8px;
        transition: all 0.2s ease;
    }

    .forgot-password:hover {
        color: #a73672;
        text-decoration: underline;
    }

    .login-button {
        width: 100%;
        padding: 16px;
        background: linear-gradient(to right, #C04888, #a73672);
        color: white;
        font-size: 1rem;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(192, 72, 136, 0.4);
    }

    .login-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: all 0.6s ease;
    }

    .login-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(192, 72, 136, 0.5);
    }

    .login-button:hover::before {
        left: 100%;
    }

    .login-button:active {
        transform: translateY(1px);
    }

    .create-account {
        margin-top: 30px;
        text-align: center;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.95rem;
    }

    .create-account a {
        color: #C04888;
        font-weight: 600;
        text-decoration: none;
        margin-left: 5px;
        transition: all 0.2s ease;
    }

    .create-account a:hover {
        text-decoration: underline;
        color: #a73672;
    }

    .social-login {
        margin-top: 25px;
        position: relative;
        text-align: center;
    }

    .social-login::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
    }

    .social-login-text {
        position: relative;
        display: inline-block;
        padding: 0 15px;
        background: #262536;
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
        z-index: 1;
    }

    .social-buttons {
        display: flex;
        justify-content: center;
        margin-top: 15px;
        gap: 12px;
    }

    .social-button {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(37, 36, 50, 0.7);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 1.2rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .social-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .social-button.google:hover {
        background: #DB4437;
        border-color: #DB4437;
    }

    .social-button.facebook:hover {
        background: #4267B2;
        border-color: #4267B2;
    }

    .social-button.twitter:hover {
        background: #1DA1F2;
        border-color: #1DA1F2;
    }

    .fstyle .inputerror {
        color: #ff6b6b;
        font-weight: 500;
        margin-top: 8px;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
    }

    .fstyle .inputerror::before {
        content: '\f06a';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        margin-right: 6px;
        font-size: 0.9rem;
    }

    .qerror {
        background: linear-gradient(to right, #C04888, #a73672);
        padding: 16px 20px;
        color: white;
        font-weight: 500;
        font-size: 0.95rem;
        position: fixed;
        top: 100px;
        z-index: 99;
        right: 20px;
        max-width: 400px;
        border-radius: 8px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.5s ease-out forwards;
        display: flex;
        align-items: center;
    }

    .qerror::before {
        content: '\f05a';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        margin-right: 10px;
        font-size: 1.1rem;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .account-not-found-alert {
        margin-top: 12px;
        padding: 15px;
        background: rgba(255, 107, 107, 0.1);
        border: 1px solid rgba(255, 107, 107, 0.3);
        border-left: 4px solid #ff6b6b;
        border-radius: 6px;
        display: flex;
        align-items: flex-start;
        animation: fadeIn 0.5s ease;
    }

    .account-not-found-alert .alert-icon {
        margin-right: 12px;
        color: #ff6b6b;
        font-size: 1.2rem;
    }

    .account-not-found-alert .alert-content {
        flex: 1;
    }

    .account-not-found-alert .alert-content p {
        margin: 0 0 10px 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .account-not-found-alert .alert-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }

    .account-not-found-alert .alert-actions a {
        display: inline-block;
        padding: 8px 12px;
        background-color: rgba(192, 72, 136, 0.2);
        color: #C04888;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        border-radius: 4px;
        transition: all 0.2s ease;
        border: 1px solid rgba(192, 72, 136, 0.3);
    }

    .account-not-found-alert .alert-actions a:hover {
        background-color: rgba(192, 72, 136, 0.3);
    }

    .account-not-found-alert .alert-or {
        margin: 0 5px;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.85rem;
    }

    /* Remember me checkbox */
    .remember-me {
        display: flex;
        align-items: center;
        margin: 15px 0;
    }

    .remember-checkbox {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkbox-label {
        position: relative;
        cursor: pointer;
        padding-left: 30px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
        user-select: none;
    }

    .checkbox-label::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .checkbox-label::after {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        left: 4px;
        top: 50%;
        transform: translateY(-50%);
        color: #C04888;
        font-size: 12px;
        opacity: 0;
        transition: all 0.2s ease;
    }

    .remember-checkbox:checked ~ .checkbox-label::before {
        border-color: #C04888;
        background-color: rgba(192, 72, 136, 0.1);
    }

    .remember-checkbox:checked ~ .checkbox-label::after {
        opacity: 1;
    }

    .remember-checkbox:focus ~ .checkbox-label::before {
        box-shadow: 0 0 0 3px rgba(192, 72, 136, 0.2);
    }

    @media (max-width: 991px) {
        .nav-list {
            display: none;
        }

        .mobile-menu-toggle {
            display: block;
        }

        .nav-list.active {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: 80px;
            left: 0;
            width: 100%;
            background-color: #1c1c28;
            padding: 20px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        .nav-list.active .nav-item {
            margin: 10px 0;
        }
    }

    @media (max-width: 700px) {
        .tyaround {
            width: 100%;
            padding: 30px 20px;
            margin: 10px auto;
            box-shadow: none;
            border-radius: 0;
            background: transparent;
            backdrop-filter: none;
            border: none;
        }

        .tyaround h1 {
            font-size: 2rem;
        }

        .qerror {
            width: 90%;
            right: 5%;
            font-size: 14px;
            max-width: 90%;
            top: 80px;
        }

        .header-content {
            height: 70px;
        }

        .logo-image {
            height: 35px;
        }

        .social-buttons {
            flex-wrap: wrap;
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

                <!-- Navigation -->
                <nav class="main-nav">
                    <ul class="nav-list">
                        <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                            <a href="/" class="nav-link">All</a>
                        </li>
                        <li class="nav-item {{ Request::is('category/music*') ? 'active' : '' }}">
                            <a href="/category/music" class="nav-link">Music</a>
                        </li>
                        <li class="nav-item {{ Request::is('category/movies*') ? 'active' : '' }}">
                            <a href="/category/movies" class="nav-link">Movies</a>
                        </li>
                        <li class="nav-item {{ Request::is('category/theatreandcomedy*') ? 'active' : '' }}">
                            <a href="/category/theatreandcomedy" class="nav-link">Theatre/Comedy</a>
                        </li>
                        <li class="nav-item {{ Request::is('category/sports*') ? 'active' : '' }}">
                            <a href="/category/sports" class="nav-link">Sports</a>
                        </li>
                        <li class="nav-item {{ Request::is('category/festivals*') ? 'active' : '' }}">
                            <a href="/category/festivals" class="nav-link">Festivals</a>
                        </li>
                        <li class="nav-item {{ Request::is('category/others*') ? 'active' : '' }}">
                            <a href="/category/others" class="nav-link">Others</a>
                        </li>
                        <li class="nav-item {{ Request::is('contact*') ? 'active' : '' }}">
                            <a href="/contact" class="nav-link">Contact us</a>
                        </li>
                    </ul>
                </nav>

                <!-- User Section -->
                <div class="user-section">
                    <div class="auth-buttons">
                        <a href="/login" class="login-btn">Login</a>
                        <a href="/signup" class="signup-btn">Sign Up</a>
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu" aria-expanded="false">
                    <span class="menu-bar"></span>
                    <span class="menu-bar"></span>
                    <span class="menu-bar"></span>
                </button>
            </div>
        </div>
    </header>

    <div class="site-main">
        @if(session()->has("message"))
            <div class="qerror" x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show">
                <p>{{ session("message") }}</p>
            </div>
        @endif

        <div class="tyaround">
            <h1>Login to your account</h1>
            <p>Enter your login details to access your account</p>

            <form method="post" class="fstyle" action="/authenticated">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="Enter your email">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    @error('email')
                        <div class="inputerror">{{ $message }}</div>
                        @if ($errors->has('email') && Str::contains($errors->first('email'), 'Invalid login'))
                            <div class="account-not-found-alert">
                                <div class="alert-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="alert-content">
                                    <p>Invalid login credentials.</p>
                                    <div class="alert-actions">
                                        <a href="{{ route('register') }}">Create new account</a>
                                        <span class="alert-or">or</span>
                                        <a href="{{ route('password.request') }}">Recover your account</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-input" placeholder="Enter your password" required autocomplete="current-password">
                    <span class="input-icon password-toggle" onclick="togglePasswordVisibility()"><i class="fas fa-eye"></i></span>
                    @error('password')
                    <div class="inputerror">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember" class="remember-checkbox">
                    <label for="remember" class="checkbox-label">Remember me</label>
                </div>

                <a href="{{ route('fp') }}" class="forgot-password">Forgot Password?</a>

                <button class="login-button" type="submit">Log In</button>
            </form>

            <div class="social-login">
                <span class="social-login-text">or login with</span>
                <div class="social-buttons">
                    <a href="#" class="social-button google">
                        <i class="fab fa-google"></i>
                    </a>
                    <a href="#" class="social-button facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-button twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>

            <p class="create-account">Don't have an account?<a href="/signup">Sign up</a></p>
        </div>
    </div>

    <script>
        // Mobile menu functionality
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            document.querySelector('.nav-list').classList.toggle('active');
            document.body.classList.toggle('menu-open');
            this.setAttribute('aria-expanded',
                this.getAttribute('aria-expanded') === 'false' ? 'true' : 'false'
            );
        });

        // Password visibility toggle
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.querySelector('.password-toggle i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Input animation
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', () => {
                if (input.value === '') {
                    input.parentElement.classList.remove('focused');
                }
            });

            // Check on load if input has value
            if (input.value !== '') {
                input.parentElement.classList.add('focused');
            }
        });
    </script>
</body>
</html>
