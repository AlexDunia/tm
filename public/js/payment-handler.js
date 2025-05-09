/**
 * Payment handler script
 * Responsible for ensuring cart is properly cleared after successful payment
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Payment handler initialized, checking for payment status');
    
    // IMMEDIATE ACTION: Check and clear cart count badge right away
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('payment_success') && urlParams.get('payment_success') === 'true') {
        console.log('Payment success detected from URL, clearing cart data immediately');
        // Immediately clear any cart badges
        const cartCountElements = document.querySelectorAll('#global-cart-count, #global-mobile-cart-count, .cart-count');
        cartCountElements.forEach(element => {
            if (element) {
                element.textContent = '0';
                element.classList.remove('active');
            }
        });
        
        // Also add a temporary flag in sessionStorage to indicate cart was just cleared
        sessionStorage.setItem('cart_just_cleared', 'true');
        sessionStorage.setItem('cart_cleared_time', Date.now().toString());
        
        // Then do the full clear
        clearAllCartData();
    }

    // Also check if we were just redirected from a payment page
    if (document.referrer.includes('checkout') || 
        document.referrer.includes('payment') || 
        document.referrer.includes('process-payment') ||
        sessionStorage.getItem('cart_just_cleared') === 'true') {
        console.log('Detected redirect from payment page or recent cart clear, resetting cart');
        clearAllCartData();
    }

    // Function to clear all cart data from localStorage
    window.clearAllCartData = function() {
        // Clear ALL possible cart storage options
        localStorage.removeItem('ticketCart');
        localStorage.removeItem('cartItems');
        localStorage.removeItem('cart');
        localStorage.removeItem('cartDiscount');
        localStorage.removeItem('cartTotal');
        
        // Clear session storage cart data too
        sessionStorage.removeItem('cartDiscount');
        sessionStorage.removeItem('cart');
        sessionStorage.removeItem('cartItems');
        
        // Set flag that cart was cleared (for other scripts)
        localStorage.setItem('cart_cleared', 'true');
        localStorage.setItem('cart_cleared_time', Date.now().toString());
        
        // Update the cart count in the UI to zero
        updateCartCountToZero();
        
        console.log('All cart data cleared from browser storage');
        
        // If we're on the cart page, reload it to show empty state
        if (window.location.pathname === '/cart') {
            console.log('On cart page, reloading to show empty state');
            window.location.reload();
        }
    };
    
    // Helper function to update cart count to zero
    function updateCartCountToZero() {
        // Find all possible cart count elements
        const cartCountElements = document.querySelectorAll('#global-cart-count, #global-mobile-cart-count, .cart-count');
        
        cartCountElements.forEach(element => {
            if (element) {
                // Update the text content
                element.textContent = '0';
                
                // Remove any active/visible classes
                element.classList.remove('active');
                element.classList.remove('show');
                element.classList.remove('visible');
            }
        });
        
        // Also use the global update function if available
        if (window.updateGlobalCartCount) {
            window.updateGlobalCartCount(0);
        }
    }

    // Override any existing XHR open method to intercept requests
    const originalOpen = XMLHttpRequest.prototype.open;
    XMLHttpRequest.prototype.open = function() {
        // Add an event listener to the XHR object
        this.addEventListener('load', function() {
            try {
                // Check if response includes payment success and clear_storage flag
                if (this.status >= 200 && this.status < 300 && this.responseText) {
                    const response = JSON.parse(this.responseText);
                    
                    // Check if we need to clear localStorage (either from success status or explicit flag)
                    if ((response.success === true && response.clear_storage === true) || 
                        (response.status === 'success' && response.verified === true) ||
                        (response.success === true && response.message && 
                         response.message.toLowerCase().includes('payment'))) {
                        console.log('Payment success detected from response, clearing cart data');
                        
                        // Set the cart_just_cleared flag
                        sessionStorage.setItem('cart_just_cleared', 'true');
                        sessionStorage.setItem('cart_cleared_time', Date.now().toString());
                        
                        // Immediately update UI
                        updateCartCountToZero();
                        
                        // Then do full clear
                        clearAllCartData();
                    }
                }
            } catch (e) {
                // Failed to parse JSON or access properties, ignore
                console.log('Error processing response', e);
            }
        });
        
        // Call the original open method
        return originalOpen.apply(this, arguments);
    };

    // Also override the fetch API to intercept responses
    const originalFetch = window.fetch;
    window.fetch = function() {
        return originalFetch.apply(this, arguments)
            .then(response => {
                // Clone the response so we can read it multiple times
                const clone = response.clone();
                
                // Process the cloned response
                clone.json().then(data => {
                    // Check if we need to clear localStorage
                    if ((data.success === true && data.clear_storage === true) || 
                        (data.status === 'success' && data.verified === true) ||
                        (data.success === true && data.message && 
                         data.message.toLowerCase().includes('payment'))) {
                        console.log('Payment success detected from fetch response, clearing cart data');
                        
                        // Set the cart_just_cleared flag
                        sessionStorage.setItem('cart_just_cleared', 'true');
                        sessionStorage.setItem('cart_cleared_time', Date.now().toString());
                        
                        // Immediately update UI
                        updateCartCountToZero();
                        
                        // Then do full clear
                        clearAllCartData();
                    }
                }).catch(() => {
                    // Not JSON or couldn't parse, ignore
                });
                
                // Return the original response
                return response;
            });
    };
}); 