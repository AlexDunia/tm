<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Forgot Password | Tixdemand</title>
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

    .info-box {
        background-color: rgba(37, 36, 50, .6);
        border-radius: 6px;
        padding: 20px;
        margin-top: 40px;
        margin-bottom: 20px;
        border-left: 4px solid #C04888;
    }

    .info-box p {
        margin-bottom: 10px;
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
                        <a href="/login" class="auth-link">Log in</a>
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
        @if(session()->has('message'))
            <div class="qerror" x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show">
                <p>{{ session('message') }}</p>
                </div>
        @endif

        @if(session()->has('error'))
            <div class="qerror" x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="tyaround">
            <h1>Forgot Your Password?</h1>
            <p>Enter your email address below and we'll send you a link to reset your password.</p>

            <form method="post" class="fstyle" action="/forgotpasswordpost">
                @csrf
                <label for="email">Email</label>
                <input id="email" type="email" name="email" placeholder="Your Email Address" value="{{ old('email') }}" required autocomplete="email">
              @error('email')
                <p class="inputerror">{{ $message }}</p>
              @enderror

                <button class="btncontact" type="submit">Send Reset Link</button>
            </form>

            <div class="info-box">
                <p><strong>Note:</strong> If your email is registered with us, you will receive a password reset link. For security reasons, we do not disclose whether an email is registered in our system.</p>
                <p>The password reset link will expire in 60 minutes.</p>
          </div>

            <p class="createact">Remember your password? <a href="/login">Log in</a></p>
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

{{-- <head>

    <link
        rel="stylesheet"
        href="\css\signup.css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>
<body>


        <div class="circular" style="background-image: url('{{ asset('images/crowd.jpg') }}');">
    <div class="popupwithdraww" id="popupwhitewithdraww">
       <a href="{{ route('logg') }}">  <div class="xmark"><i class="fa-solid fa-xmark"></i></div>
       </a>


        <div class="newsform">
            <h1 class="newsletterhead"> Log in</h1>
            <p> Enter your Login details </p>
            <br/>
            <form method="post" class="fstyle" action="/authenticated" enctype="multipart/form-data">

                {{ csrf_field() }}
                <div>
                    <input id="email" class="nlstyle" type="email" name="email" placeholder="Your Email">
                    @error('email')
                    <p class="inputeerror" style="color:rgb(252, 80, 80)"> {{$message}} </p>
                    @enderror

                </div>

                <div>
                    <input id="email" class="nlstyle" type="password" name="password" placeholder="Your Password">
                    @error('password')
                    <p class="inputeerror" style="color:rgb(252, 80, 80)"> {{$message}} </p>
                    @enderror

                    <a> Forgot Password </a>
                </div>
                      <div>
                    <button class="newsbtn" type="submit"> Log In </button>
                      </div>

                    </form>
                    <p class="createact"> Already have an account? Log in </p>


    </div>
</div>

</body> --}}


{{-- @if(session()->Has("message")){
  <div class="qerror" x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show">
      <p> {{$message}} </p>
      </div>
}
@endif --}}

