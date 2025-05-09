/**
 * Cart Cleaner Script
 * This script ensures the cart view is properly emptied after a payment
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart cleaner initialized');
    
    // Check if we're on the cart page
    if (window.location.pathname === '/cart') {
        console.log('On cart page, checking for payment indicators');
        
        // Check for payment success indicators in various places
        const urlParams = new URLSearchParams(window.location.search);
        const hasPaymentSuccess = urlParams.has('payment_success');
        const hasCartCleared = localStorage.getItem('cart_cleared') === 'true';
        const hasSessionCartCleared = sessionStorage.getItem('cart_just_cleared') === 'true';
        const hasRecentPayment = checkRecentPayment();
        
        console.log('Payment indicators:', {
            urlPaymentSuccess: hasPaymentSuccess,
            localStorageCartCleared: hasCartCleared,
            sessionStorageCartCleared: hasSessionCartCleared,
            recentPayment: hasRecentPayment
        });
        
        // If any payment indicator is present, force empty cart display
        if (hasPaymentSuccess || hasCartCleared || hasSessionCartCleared || hasRecentPayment) {
            console.log('Payment indicator found, forcing empty cart view');
            forceEmptyCartDisplay();
        }
    }
    
    // Check if there was a recent payment (within last 5 minutes)
    function checkRecentPayment() {
        const clearedTime = localStorage.getItem('cart_cleared_time') || 
                           sessionStorage.getItem('cart_cleared_time');
                           
        if (clearedTime) {
            const timeElapsed = Date.now() - parseInt(clearedTime);
            // If cleared within last 5 minutes (300000 ms)
            return timeElapsed < 300000;
        }
        return false;
    }
    
    // Function to force the cart page to show empty state
    function forceEmptyCartDisplay() {
        // Try to find cart items container and empty cart message
        const cartItemsContainer = document.querySelector('.cart-items');
        const emptyCartMessage = document.querySelector('.empty-cart');
        const cartContent = document.querySelector('.cart-content');
        const cartFooter = document.querySelector('.cart-footer');
        const fixedFooter = document.querySelector('#fixedBuyFooter');
        
        // Hide cart items if present
        if (cartItemsContainer) {
            cartItemsContainer.innerHTML = '';
            console.log('Cleared cart items container');
        }
        
        // Show empty cart message if present
        if (emptyCartMessage) {
            emptyCartMessage.style.display = 'block';
            console.log('Showing empty cart message');
        }
        
        // Hide cart content if present
        if (cartContent) {
            cartContent.style.display = 'none';
            console.log('Hiding cart content');
        }
        
        // Hide cart footer if present
        if (cartFooter) {
            cartFooter.style.display = 'none';
            console.log('Hiding cart footer');
        }
        
        // Hide fixed footer if present
        if (fixedFooter) {
            fixedFooter.style.display = 'none';
            console.log('Hiding fixed footer');
        }
        
        // Update cart count badge in header
        const cartCountElements = document.querySelectorAll('#global-cart-count, #global-mobile-cart-count, .cart-count');
        cartCountElements.forEach(element => {
            if (element) {
                element.textContent = '0';
                element.classList.remove('active');
                console.log('Updated cart count badge to 0');
            }
        });
        
        // Add a special class to body to indicate empty cart
        document.body.classList.add('empty-cart-after-payment');
    }
}); 