@php
    use Illuminate\Support\Facades\Auth;
    use App\Http\Controllers\CartController;

    // Get cart count using the method from CartController
    $cartCount = CartController::getCartCount();
@endphp

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
                <!-- Cart Icon - Same for both auth and guest users -->
                <a href="/cart" class="cart-icon-wrapper">
                    <div class="cart-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span class="cart-count {{ $cartCount > 0 ? 'active' : '' }}" id="global-cart-count">
                            {{ $cartCount }}
                        </span>
                    </div>
                </a>

                @auth
                <!-- Authenticated User -->
                <div class="user-profile" id="usericonid">
                    @if(auth()->user()->profilepic)
                    <div class="profile-image">
                        <img src="{{ asset('storage/' . auth()->user()->profilepic) }}" alt="{{ auth()->user()->firstname }}">
                    </div>
                    @else
                    <div class="profile-initials">
                        <span>{{ strtoupper(substr(auth()->user()->firstname, 0, 1)) }}{{ strtoupper(substr(auth()->user()->lastname, 0, 1)) }}</span>
                    </div>
                    @endif
                </div>

                <!-- User Dropdown -->
                <div class="user-dropdown" id="userDropdown">
                    <div class="dropdown-content">
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="dropdown-link">Logout</button>
                        </form>
                        <a href="/profile" class="dropdown-link">My Profile</a>
                        <a href="/orders" class="dropdown-link">My Orders</a>
                    </div>
                </div>
                @else
                <!-- Guest User -->
                <div class="auth-buttons">
                    <a href="/login" class="login-btn">Login</a>
                    <a href="/signup" class="signup-btn">Sign Up</a>
                </div>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" id="menuToggle" aria-label="Toggle Menu">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div class="mobile-nav" id="mobileNav">
        <ul class="mobile-nav-list">
            <li class="mobile-nav-item {{ Request::is('/') ? 'active' : '' }}">
                <a href="/" class="mobile-nav-link">All</a>
            </li>
            <li class="mobile-nav-item {{ Request::is('category/music*') ? 'active' : '' }}">
                <a href="/category/music" class="mobile-nav-link">Music</a>
            </li>
            <li class="mobile-nav-item {{ Request::is('category/movies*') ? 'active' : '' }}">
                <a href="/category/movies" class="mobile-nav-link">Movies</a>
            </li>
            <li class="mobile-nav-item {{ Request::is('category/theatreandcomedy*') ? 'active' : '' }}">
                <a href="/category/theatreandcomedy" class="mobile-nav-link">Theatre/Comedy</a>
            </li>
            <li class="mobile-nav-item {{ Request::is('category/sports*') ? 'active' : '' }}">
                <a href="/category/sports" class="mobile-nav-link">Sports</a>
            </li>
            <li class="mobile-nav-item {{ Request::is('category/festivals*') ? 'active' : '' }}">
                <a href="/category/festivals" class="mobile-nav-link">Festivals</a>
            </li>
            <li class="mobile-nav-item {{ Request::is('category/others*') ? 'active' : '' }}">
                <a href="/category/others" class="mobile-nav-link">Others</a>
            </li>
            <li class="mobile-nav-item {{ Request::is('contact*') ? 'active' : '' }}">
                <a href="/contact" class="mobile-nav-link">Contact us</a>
            </li>
            <li class="mobile-nav-item {{ Request::is('cart*') ? 'active' : '' }}">
                <a href="/cart" class="mobile-nav-link">
                    <i class="fa-solid fa-cart-shopping"></i> Cart
                    <span class="mobile-cart-count {{ $cartCount > 0 ? 'active' : '' }}" id="global-mobile-cart-count">{{ $cartCount }}</span>
                </a>
            </li>
            @guest
            <li class="mobile-nav-item auth-item">
                <a href="/login" class="mobile-nav-link">Login</a>
            </li>
            <li class="mobile-nav-item auth-item">
                <a href="/signup" class="mobile-nav-link">Sign Up</a>
            </li>
            @endguest
            @auth
            <li class="mobile-nav-item auth-item">
                <form method="POST" action="/logout" class="mobile-logout-form">
                    @csrf
                    <button type="submit" class="mobile-nav-link logout-link">Logout</button>
                </form>
            </li>
            <li class="mobile-nav-item auth-item">
                <a href="/profile" class="mobile-nav-link">My Profile</a>
            </li>
            <li class="mobile-nav-item auth-item">
                <a href="/orders" class="mobile-nav-link">My Orders</a>
            </li>
            @endauth
        </ul>
    </div>
</header>

<style>
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

.nav-item.active .nav-link:after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: #C04888;
}

.user-section {
    display: flex;
    align-items: center;
    position: relative;
}

/* Cart Icon Styles */
.cart-icon-wrapper {
    position: relative;
    margin-right: 25px;
    display: inline-block;
}

.cart-icon {
    position: relative;
    width: 24px;
    height: 24px;
}

.cart-icon svg {
    width: 100%;
    height: 100%;
    color: white;
}

.cart-count, .mobile-cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background-color: #C04888;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.cart-count.active, .mobile-cart-count.active {
    opacity: 1;
}

/* Auth Button Styles */
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

/* User Profile Styles */
.user-profile {
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-image {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    overflow: hidden;
}

.profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-initials {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: #C04888;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

/* Dropdown Styles */
.user-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background-color: #252432;
    border-radius: 4px;
    width: 200px;
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    display: none;
    overflow: hidden;
}

.user-dropdown.show {
    display: block;
}

.dropdown-content {
    padding: 10px 0;
}

.dropdown-link {
    display: block;
    padding: 10px 20px;
    color: white;
    text-decoration: none;
    transition: background-color 0.3s ease;
    text-align: left;
    width: 100%;
    border: none;
    background: none;
    font-size: 14px;
    cursor: pointer;
}

.dropdown-link:hover {
    background-color: #333240;
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

.bar {
    display: block;
    width: 100%;
    height: 2px;
    background-color: white;
    position: absolute;
    left: 0;
    transition: all 0.3s ease;
}

.bar:nth-child(1) {
    top: 0;
}

.bar:nth-child(2) {
    top: 50%;
    transform: translateY(-50%);
}

.bar:nth-child(3) {
    bottom: 0;
}

.mobile-menu-toggle.active .bar:nth-child(1) {
    transform: rotate(45deg);
    top: 9px;
}

.mobile-menu-toggle.active .bar:nth-child(2) {
    opacity: 0;
}

.mobile-menu-toggle.active .bar:nth-child(3) {
    transform: rotate(-45deg);
    bottom: 9px;
}

/* Mobile nav */
.mobile-nav {
    display: none;
    position: fixed;
    top: 80px;
    left: 0;
    width: 100%;
    height: calc(100vh - 80px);
    background-color: #0d0c11;
    z-index: 999;
    overflow-y: auto;
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.mobile-nav.active {
    transform: translateX(0);
}

.mobile-nav-list {
    list-style: none;
    padding: 20px;
    margin: 0;
}

.mobile-nav-item {
    margin-bottom: 15px;
}

.mobile-nav-link, .logout-link {
    display: block;
    color: white;
    text-decoration: none;
    font-size: 18px;
    padding: 12px 0;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    background: none;
    border-left: none;
    border-right: none;
    border-top: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
}

.mobile-nav-link:hover {
    color: #C04888;
}

.auth-item {
    margin-top: 15px;
}

.mobile-logout-form {
    margin: 0;
    padding: 0;
}

/* Media queries */
@media (max-width: 991px) {
    .nav-list {
        display: none;
    }

    .mobile-menu-toggle {
        display: block;
    }

    .mobile-nav {
        display: block;
    }
}

@media (max-width: 768px) {
    .header-content {
        height: 70px;
    }

    .logo-image {
        height: 35px;
    }

    .cart-icon-wrapper {
        margin-right: 10px;
    }

    .auth-buttons {
        display: none;
    }

    .mobile-nav {
        top: 70px;
        height: calc(100vh - 70px);
    }
}
</style>

<script>
// Custom event-based cart system for real-time updates
document.addEventListener('DOMContentLoaded', function() {
    // Create a custom event for cart updates
    const cartUpdateEvent = new CustomEvent('cart:updated');

    // Function to update cart count display
    window.updateGlobalCartCount = function(count) {
        const cartCountElements = document.querySelectorAll('#global-cart-count, #global-mobile-cart-count');

        cartCountElements.forEach(element => {
            if (element) {
                // Update the text content
                element.textContent = count;

                // Toggle the 'active' class based on count
                if (count > 0) {
                    element.classList.add('active');
                } else {
                    element.classList.remove('active');
                }
            }
        });

        // Dispatch the custom event
        document.dispatchEvent(cartUpdateEvent);
    };

    // Custom event listener for other scripts to hook into
    document.addEventListener('cart:updated', function(e) {
        console.log('Cart updated event triggered');
    });

    // User dropdown toggle
    const userProfile = document.getElementById('usericonid');
    const userDropdown = document.getElementById('userDropdown');

    if (userProfile && userDropdown) {
        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (userDropdown.classList.contains('show') && !userProfile.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.remove('show');
            }
        });
    }

    // Mobile menu toggle
    const menuToggle = document.getElementById('menuToggle');
    const mobileNav = document.getElementById('mobileNav');

    if (menuToggle && mobileNav) {
        menuToggle.addEventListener('click', function() {
            mobileNav.classList.toggle('active');
            menuToggle.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });
    }
});
</script>
