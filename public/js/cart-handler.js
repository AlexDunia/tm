/**
 * Global Cart Handler
 * Handles cart operations and updates across the site
 */
document.addEventListener('DOMContentLoaded', function() {
    // Function to clear all cart data
    window.clearAllCartData = function() {
        console.log('Clearing all cart data...');
        
        // Clear localStorage
        localStorage.removeItem('ticketCart');
        localStorage.removeItem('cartItems');
        localStorage.removeItem('cart');
        localStorage.removeItem('cartDiscount');
        localStorage.removeItem('cartTotal');
        localStorage.removeItem('cart_data');
        localStorage.removeItem('cart_count');
        localStorage.removeItem('cart_items');
        localStorage.removeItem('temp_cart');
        localStorage.removeItem('cart_totals');
        
        // Clear sessionStorage
        sessionStorage.removeItem('cartDiscount');
        sessionStorage.removeItem('cart');
        sessionStorage.removeItem('cartItems');
        sessionStorage.removeItem('cart_data');
        sessionStorage.removeItem('cart_count');
        sessionStorage.removeItem('cart_items');
        sessionStorage.removeItem('temp_cart');
        sessionStorage.removeItem('cart_totals');
        
        // Update all cart count displays to zero
        const cartCountElements = document.querySelectorAll('.cart-count, #global-cart-count, #global-mobile-cart-count');
        cartCountElements.forEach(element => {
            if (element) {
                element.textContent = '0';
                element.style.display = 'none';
                element.classList.remove('active');
            }
        });

        // Clear cart badge if exists
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            cartBadge.style.display = 'none';
        }

        // Make an AJAX call to clear server-side cart
        fetch('/clear-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        }).then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        }).then(data => {
            console.log('Server-side cart cleared:', data);
            // Reload the page if we're on the cart page
            if (window.location.pathname === '/cart') {
                window.location.reload();
            }
        }).catch(error => {
            console.error('Error clearing server-side cart:', error);
        });

        console.log('Cart data cleared successfully');
    };

    // Check URL parameters for payment success
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('payment_success')) {
        window.clearAllCartData();
    }

    // Intercept fetch requests to handle payment success
    const originalFetch = window.fetch;
    window.fetch = function() {
        return originalFetch.apply(this, arguments)
            .then(response => {
                response.clone().json().catch(() => ({})).then(data => {
                    if ((data.success === true && data.clear_storage === true) || 
                        (data.status === 'success' && data.verified === true)) {
                        window.clearAllCartData();
                    }
                });
                return response;
            });
    };

    // Function to clear cart data from localStorage
    function clearCartStorage() {
        // Clear cart-related items from localStorage
        localStorage.removeItem('cart_items');
        localStorage.removeItem('cart_count');
        localStorage.removeItem('cart_totals');
        
        // Update cart count in header to 0
        const cartCountElement = document.querySelector('.cart-count');
        if (cartCountElement) {
            cartCountElement.textContent = '0';
            cartCountElement.style.display = 'none';
        }
    }

    // Check for clear cart signal from backend
    if (sessionStorage.getItem('clear_cart_storage') === 'true') {
        clearCartStorage();
        sessionStorage.removeItem('clear_cart_storage');
    }

    // Listen for custom event that might be triggered after payment success
    document.addEventListener('payment:success', function() {
        window.clearAllCartData();
    });

    // Export for use in other scripts
    window.clearCartStorage = clearCartStorage;
}); 