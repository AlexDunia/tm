<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Log in | Tixdemand</title>
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/css/admin.css">
<link rel="preconnect" href="https://fonts.gstatic.com">
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
    }

    .tyaround {
        width: 85%;
        margin: auto;
        padding-top: 40px;
    }

    .tyaround h1 {
        font-size: 40px;
        margin-top: 30px;
        margin-bottom: 20px;
    }

    .tyaround p {
        font-size: 16px;
        margin-bottom: 30px;
        color: #a0aec0;
    }

    form input,
    form textarea {
        width: 100%;
        padding: 20px;
        background: rgba(37, 36, 50, .8);
        border: none;
        margin-top: 10px;
        box-sizing: border-box;
        font-size: 16px;
        color: white;
        border-radius: 4px;
    }

    .btncontact {
        width: 100%;
        padding: 20px;
        background-color: #C04888;
        color: white;
        font-size: 20px;
        font-weight: 600;
        border: none;
        margin-top: 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btncontact:hover {
        background-color: #a73672;
    }

    .fstyle .inputerror {
        color: #fc5050;
        font-weight: 600;
        margin-top: 5px;
        font-size: 14px;
    }

    .qerror {
        background: #C04888;
        padding: 12px 15px;
        color: white;
        font-weight: 600;
        font-size: 15px;
        position: fixed;
        top: 90px;
        z-index: 99;
        right: 20px;
        max-width: 400px;
        border-radius: 4px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .fstyle a {
        color: #C04888;
        font-weight: 600;
        font-size: 16px;
        text-decoration: none;
        display: inline-block;
        margin-top: 10px;
    }

    .fstyle a:hover {
        color: #a73672;
        text-decoration: underline;
    }

    .createact {
        margin-top: 30px;
        text-align: center;
    }

    .createact a {
        color: #C04888;
        font-weight: 600;
        text-decoration: none;
    }

    .createact a:hover {
        text-decoration: underline;
    }

    .account-not-found-alert {
        margin-top: 10px;
        padding: 15px;
        background-color: #fdf2f5;
        border: 1px solid #fbd2e0;
        border-left: 4px solid #C04888;
        border-radius: 4px;
        display: flex;
        align-items: flex-start;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .account-not-found-alert .alert-icon {
        margin-right: 12px;
        color: #C04888;
        font-size: 20px;
    }

    .account-not-found-alert .alert-content {
        flex: 1;
    }

    .account-not-found-alert .alert-content p {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 14px;
        line-height: 1.4;
    }

    .account-not-found-alert .alert-actions {
        display: flex;
        align-items: center;
        margin-top: 8px;
    }

    .account-not-found-alert .alert-actions a {
        display: inline-block;
        margin: 0;
        padding: 6px 12px;
        background-color: #C04888;
        color: white;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.2s ease;
    }

    .account-not-found-alert .alert-actions a:hover {
        background-color: #a73672;
        text-decoration: none;
    }

    .account-not-found-alert .alert-or {
        margin: 0 10px;
        color: #666;
        font-size: 14px;
    }

    @media (max-width: 700px) {
        .tyaround h1 {
            font-size: 30px;
        }

        .qerror {
            width: 90%;
            right: 5%;
            font-size: 14px;
            max-width: 90%;
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
                        <img src="/images/tdlogo.png" alt="Tixdemand Logo" class="logo-image">
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
                        <li class="nav-item {{ Request::is('contact*') ? 'active' : '' }}">
                            <a href="/contact" class="nav-link">Contact us</a>
                        </li>
                    </ul>
                </nav>

                <!-- User Section -->
                <div class="user-section">
                    <div class="auth-buttons">
                        <a href="/login" class="auth-link active">Log in</a>
                        <a href="/signup" class="auth-link">Sign up</a>
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
                <div class="mb-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="Enter your email">
                    @error('email')
                        <div class="inputerror">{{ $message }}</div>
                        @if ($errors->has('email') && Str::contains($errors->first('email'), 'Account not found'))
                            <div class="account-not-found-alert">
                                <div class="alert-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="alert-content">
                                    <p>We couldn't find an account with that email address.</p>
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

                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Your Password" required autocomplete="current-password">
                @error('password')
                <p class="inputerror">{{ $message }}</p>
                @enderror

                <a href="{{ route('fp') }}">Forgot Password?</a>

                <button class="btncontact" type="submit">Log In</button>
            </form>

            <p class="createact">Don't have an account? <a href="/signup">Sign up</a></p>
        </div>
    </div>

    <script>
        // Mobile menu functionality
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            document.getElementById('mobileNav').classList.toggle('active');
            document.body.classList.toggle('menu-open');
            this.setAttribute('aria-expanded',
                this.getAttribute('aria-expanded') === 'false' ? 'true' : 'false'
            );
        });
    </script>
</body>
</html>
