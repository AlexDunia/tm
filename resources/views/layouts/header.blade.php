@php
    use App\Services\CartService;
    
    // Get cart count using CartService
    // If payment was successful, force count to 0
    $cartCount = request()->has('payment_success') ? 0 : CartService::getCartCount();
@endphp

<header class="site-header"> 
    <!-- Cart count element -->
    <span id="global-cart-count" class="cart-count {{ $cartCount > 0 ? 'active' : '' }}" style="{{ $cartCount > 0 ? '' : 'display: none;' }}">{{ $cartCount }}</span>
    
    <!-- Add JavaScript to handle cart count updates -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to update cart count
            window.updateGlobalCartCount = function(count) {
                const cartCountElements = document.querySelectorAll('#global-cart-count, #global-mobile-cart-count, .cart-count');
                cartCountElements.forEach(element => {
                    if (element) {
                        element.textContent = count;
                        if (count > 0) {
                            element.style.display = '';
                            element.classList.add('active');
                        } else {
                            element.style.display = 'none';
                            element.classList.remove('active');
                        }
                    }
                });
            };

            // Check for payment success and clear cart indicators
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('payment_success')) {
                updateGlobalCartCount(0);
                // Force clear any remaining cart data
                if (window.clearAllCartData) {
                    window.clearAllCartData();
                }
            }
        });
    </script>
</header> 