<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign Up | Tixdemand</title>
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
        max-width: 600px;
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

    form label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    form input,
    form textarea {
        width: 100%;
        padding: 20px;
        background: rgba(37, 36, 50, .8);
        border: none;
        margin-bottom: 20px;
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
        margin-top: 10px;
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
        margin-top: -15px;
        margin-bottom: 15px;
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

    .security-banner {
        background: rgba(39, 41, 60, 0.9);
        border-left: 4px solid #C04888;
        border-radius: 6px;
        padding: 20px;
        margin: 0 auto 30px;
        max-width: 600px;
        display: flex;
        align-items: flex-start;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        width: 85%;
    }

    .security-icon {
        font-size: 22px;
        color: #C04888;
        margin-right: 15px;
        padding-top: 3px;
    }

    .security-content {
        flex: 1;
    }

    .security-content h3 {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 18px;
        color: #f1f1f1;
    }

    .security-content p {
        margin-bottom: 10px;
        font-size: 14px;
        color: #c7c7c7;
    }

    .security-tip {
        background: rgba(192, 72, 136, 0.2);
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 13px !important;
        color: #f1f1f1 !important;
    }

    .security-tip i {
        margin-right: 5px;
        color: #C04888;
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
                        <a href="/login" class="auth-link">Log in</a>
                        <a href="/signup" class="auth-link active">Sign up</a>
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

        @if(session('message') && str_contains(session('message'), 'couldn\'t find an account'))
            <div class="security-banner">
                <div class="security-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="security-content">
                    <h3>Account Not Found</h3>
                    <p>We couldn't locate an account with the email you provided. Create a new account below to get started with Tixdemand.</p>
                    <p class="security-tip"><i class="fas fa-info-circle"></i> Your security is important to us. All accounts require a strong password and are protected with advanced security measures.</p>
                </div>
            </div>
        @endif

        <div class="tyaround">
            <h1>Create Your Account</h1>
            <p>Enter your personal details to get started</p>

            <form method="post" class="fstyle" action="/createnewadmin" enctype="multipart/form-data">
                @csrf
                <label for="firstname">First name</label>
                <input type="text" name="firstname" placeholder="First Name" value="{{ old('firstname') }}" required>
                    @error('firstname')
                <p class="inputerror">{{ $message }}</p>
                    @enderror

                <label for="lastname">Last name</label>
                <input type="text" name="lastname" placeholder="Last Name" value="{{ old('lastname') }}" required>
                @error('lastname')
                <p class="inputerror">{{ $message }}</p>
                @enderror

                <label for="email">Email</label>
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                <p class="inputerror">{{ $message }}</p>
                    @enderror

                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Password (min. 8 characters)" required autocomplete="new-password">
                    @error('password')
                <p class="inputerror">{{ $message }}</p>
                    @enderror

                <button class="btncontact" type="submit">Sign up</button>
                    </form>

            <p class="createact">Already have an account? <a href="/login">Log in</a></p>
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
