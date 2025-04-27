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
                @auth
                <!-- Authenticated User -->
                <!-- Cart Icon -->
                <a href="/cart" class="cart-icon-wrapper">
                    <div class="cart-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span class="cart-count @if(Auth::check() && Auth::user()->relatewithcart()->count() > 0) active @endif">
                            @if(Auth::check())
                                {{ Auth::user()->relatewithcart()->sum('cquantity') }}
                            @else
                                0
                            @endif
                        </span>
                    </div>
                </a>
                <div class="user-profile" id="usericonid">
                    @if(auth()->user()->profilepic)
                    <div class="profile-image">
                        <img src="{{ asset('storage/' . auth()->user()->profilepic) }}" alt="{{ auth()->user()->name }}">
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
                <!-- Cart Icon -->
                <a href="/cart" class="cart-icon-wrapper">
                    <div class="cart-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span class="cart-count @if(session()->has('cart_items') && count(session()->get('cart_items', [])) > 0) active @endif">
                            @if(session()->has('cart_items'))
                                {{ collect(session()->get('cart_items', []))->sum('cquantity') }}
                            @else
                                0
                            @endif
                        </span>
                    </div>
                </a>
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
                    <span class="mobile-cart-count">
                        @if(Auth::check())
                            {{ Auth::user()->relatewithcart()->sum('cquantity') }}
                        @elseif(session()->has('cart_items'))
                            {{ collect(session()->get('cart_items', []))->sum('cquantity') }}
                        @else
                            0
                        @endif
                    </span>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Update cart count
    updateCartCount();
});

// Update cart count function
function updateCartCount() {
    // Only update cart count with JS if not authenticated
    @if(!Auth::check())
    let cartCount = 0;

    // Check if we're using localStorage cart (frontend approach)
    if (localStorage.getItem('ticketCart')) {
        const cart = JSON.parse(localStorage.getItem('ticketCart') || '[]');

        // Count total tickets in cart
        cart.forEach(item => {
            if (item.tickets) {
                item.tickets.forEach(ticket => {
                    cartCount += ticket.quantity || 0;
                });
            }
        });

        // Update all cart count elements
        const cartCountElements = document.querySelectorAll('.cart-count, .mobile-cart-count');
        cartCountElements.forEach(element => {
            element.textContent = cartCount;

            // Add active class if there are items in cart
            if (cartCount > 0) {
                element.classList.add('active');
            } else {
                element.classList.remove('active');
            }
        });
    }
    @endif
}

// Listen for storage changes to update cart count
window.addEventListener('storage', function(e) {
    if (e.key === 'ticketCart') {
        updateCartCount();
    }
});
</script>
