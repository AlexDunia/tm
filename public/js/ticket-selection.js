/**
 * Ticket Selection and Cart Functionality
 * 
 * This script handles:
 * - Event card click to open popup
 * - Ticket quantity selection
 * - Live price calculation
 * - Add to cart functionality
 * - Session storage for cart data
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initEventCards();
    initTicketPopup();
    initTicketSelection();
    updateCartCount();
});

/**
 * Initialize event cards click functionality
 */
function initEventCards() {
    const eventCards = document.querySelectorAll('.event-card');
    
    eventCards.forEach(card => {
        card.addEventListener('click', function() {
            const eventId = this.dataset.eventId;
            const eventTitle = this.querySelector('.event-card-title').textContent;
            
            // Set event info in popup
            const popup = document.querySelector('.ticket-popup');
            popup.dataset.eventId = eventId;
            
            // Set popup title
            const popupTitle = document.querySelector('.ticket-popup-title');
            if (popupTitle) {
                popupTitle.textContent = `Tickets for ${eventTitle}`;
            }
            
            // Show popup
            const overlay = document.querySelector('.ticket-popup-overlay');
            overlay.classList.add('active');
            
            // Prevent body scrolling
            document.body.style.overflow = 'hidden';
        });
    });
}

/**
 * Initialize ticket popup functionality
 */
function initTicketPopup() {
    // Close popup when clicking the X button
    const closeBtn = document.querySelector('.ticket-popup-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeTicketPopup);
    }
    
    // Close popup when clicking outside
    const overlay = document.querySelector('.ticket-popup-overlay');
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                closeTicketPopup();
            }
        });
    }
    
    // Add to cart button
    const addToCartBtn = document.querySelector('.add-to-cart-btn');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', addTicketsToCart);
    }
}

/**
 * Close the ticket popup
 */
function closeTicketPopup() {
    const overlay = document.querySelector('.ticket-popup-overlay');
    overlay.classList.remove('active');
    
    // Re-enable body scrolling
    document.body.style.overflow = '';
    
    // Reset quantities
    resetTicketQuantities();
}

/**
 * Reset all ticket quantities to 0
 */
function resetTicketQuantities() {
    const quantityInputs = document.querySelectorAll('.ticket-option .quantity-input');
    quantityInputs.forEach(input => {
        input.value = 0;
    });
    
    // Update total
    updateTicketTotal();
}

/**
 * Initialize ticket selection and quantity controls
 */
function initTicketSelection() {
    // Plus buttons
    const plusBtns = document.querySelectorAll('.quantity-btn.plus');
    plusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.quantity-input');
            input.value = parseInt(input.value || 0) + 1;
            
            // Highlight selected option
            const ticketOption = this.closest('.ticket-option');
            if (parseInt(input.value) > 0) {
                ticketOption.classList.add('selected');
            }
            
            updateTicketTotal();
        });
    });
    
    // Minus buttons
    const minusBtns = document.querySelectorAll('.quantity-btn.minus');
    minusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.quantity-input');
            const currentValue = parseInt(input.value || 0);
            if (currentValue > 0) {
                input.value = currentValue - 1;
                
                // Remove highlight if zero
                const ticketOption = this.closest('.ticket-option');
                if (parseInt(input.value) === 0) {
                    ticketOption.classList.remove('selected');
                }
                
                updateTicketTotal();
            }
        });
    });
    
    // Manual input
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Ensure non-negative integer
            let value = parseInt(this.value || 0);
            if (isNaN(value) || value < 0) value = 0;
            this.value = value;
            
            // Update highlight
            const ticketOption = this.closest('.ticket-option');
            if (value > 0) {
                ticketOption.classList.add('selected');
            } else {
                ticketOption.classList.remove('selected');
            }
            
            updateTicketTotal();
        });
    });
}

/**
 * Update the total price based on selected tickets
 */
function updateTicketTotal() {
    let total = 0;
    const ticketOptions = document.querySelectorAll('.ticket-option');
    
    ticketOptions.forEach(option => {
        const priceText = option.querySelector('.ticket-option-price').textContent;
        const price = parseFloat(priceText.replace(/[^\d.-]/g, ''));
        const quantity = parseInt(option.querySelector('.quantity-input').value || 0);
        
        total += price * quantity;
    });
    
    // Update total display
    const totalAmount = document.querySelector('.ticket-total-amount');
    if (totalAmount) {
        totalAmount.textContent = `₦${total.toLocaleString()}`;
    }
}

/**
 * Add selected tickets to cart
 */
function addTicketsToCart() {
    const eventId = document.querySelector('.ticket-popup').dataset.eventId;
    const eventTitle = document.querySelector('.ticket-popup-title').textContent.replace('Tickets for ', '');
    
    let hasTickets = false;
    const tickets = [];
    
    // Collect selected tickets
    const ticketOptions = document.querySelectorAll('.ticket-option');
    ticketOptions.forEach(option => {
        const title = option.querySelector('.ticket-option-title').textContent;
        const priceText = option.querySelector('.ticket-option-price').textContent;
        const price = parseFloat(priceText.replace(/[^\d.-]/g, ''));
        const quantity = parseInt(option.querySelector('.quantity-input').value || 0);
        
        if (quantity > 0) {
            hasTickets = true;
            tickets.push({
                id: option.dataset.ticketId,
                title: title,
                price: price,
                quantity: quantity
            });
        }
    });
    
    if (!hasTickets) {
        alert('Please select at least one ticket.');
        return;
    }
    
    // Get cart from storage or initialize
    let cart = JSON.parse(localStorage.getItem('ticketCart') || '[]');
    
    // Add event info and tickets
    cart.push({
        eventId: eventId,
        eventTitle: eventTitle,
        image: document.querySelector(`.event-card[data-event-id="${eventId}"] .event-card-image img`)?.src || '',
        date: document.querySelector(`.event-card[data-event-id="${eventId}"] .month`)?.textContent + ' ' +
              document.querySelector(`.event-card[data-event-id="${eventId}"] .day`)?.textContent,
        location: document.querySelector(`.event-card[data-event-id="${eventId}"] .event-card-location`)?.textContent.trim(),
        tickets: tickets,
        timestamp: new Date().getTime()
    });
    
    // Save to storage
    localStorage.setItem('ticketCart', JSON.stringify(cart));
    
    // Show loading animation
    showLoadingAnimation();
    
    // Close popup
    closeTicketPopup();
    
    // Update cart count
    updateCartCount();
    
    // Redirect to cart page after delay
    setTimeout(() => {
        window.location.href = '/cart';
    }, 1500);
}

/**
 * Show loading animation
 */
function showLoadingAnimation() {
    const loadingOverlay = document.querySelector('.loading-overlay');
    loadingOverlay.classList.add('active');
}

/**
 * Update cart count in header
 */
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('ticketCart') || '[]');
    const cartCount = document.querySelector('.cart-count');
    
    if (cartCount) {
        cartCount.textContent = cart.length;
        
        // Show/hide based on items
        if (cart.length > 0) {
            cartCount.classList.add('active');
        } else {
            cartCount.classList.remove('active');
        }
    }
}

// Cart page functionality
if (window.location.pathname === '/cart') {
    document.addEventListener('DOMContentLoaded', function() {
        loadCartItems();
        initCartControls();
    });
}

/**
 * Load cart items from storage and render them
 */
function loadCartItems() {
    const cart = JSON.parse(localStorage.getItem('ticketCart') || '[]');
    const cartItemsContainer = document.querySelector('.cart-items');
    const emptyCartContainer = document.querySelector('.empty-cart');
    const cartContent = document.querySelector('.cart-content');
    
    if (!cartItemsContainer) return;
    
    // Show/hide empty cart message
    if (cart.length === 0) {
        if (emptyCartContainer) emptyCartContainer.style.display = 'block';
        if (cartContent) cartContent.style.display = 'none';
        return;
    } else {
        if (emptyCartContainer) emptyCartContainer.style.display = 'none';
        if (cartContent) cartContent.style.display = 'flex';
    }
    
    // Clear container
    cartItemsContainer.innerHTML = '';
    
    // Generate HTML for each cart item
    let totalAmount = 0;
    
    cart.forEach((event, eventIndex) => {
        event.tickets.forEach((ticket, ticketIndex) => {
            const subtotal = ticket.price * ticket.quantity;
            totalAmount += subtotal;
            
            const itemHTML = `
                <div class="cart-item" data-event-index="${eventIndex}" data-ticket-index="${ticketIndex}">
                    <div class="cart-item-image">
                        <img src="${event.image || '/images/placeholder.jpg'}" alt="${event.eventTitle}">
                    </div>
                    <div class="cart-item-content">
                        <div class="cart-item-header">
                            <h3 class="cart-item-title">${event.eventTitle}</h3>
                            <div class="cart-item-price">₦${subtotal.toLocaleString()}</div>
                        </div>
                        <div class="cart-item-details">
                            <div class="cart-item-detail">
                                <i class="fas fa-ticket-alt"></i> ${ticket.title}
                            </div>
                            <div class="cart-item-detail">
                                <i class="far fa-calendar"></i> ${event.date}
                            </div>
                            <div class="cart-item-detail">
                                <i class="fas fa-map-marker-alt"></i> ${event.location}
                            </div>
                        </div>
                        <div class="cart-item-actions">
                            <div class="cart-item-quantity">
                                <button class="cart-quantity-btn minus">-</button>
                                <input type="number" class="cart-quantity-input" value="${ticket.quantity}" min="1">
                                <button class="cart-quantity-btn plus">+</button>
                            </div>
                            <button class="cart-item-remove">
                                <i class="fas fa-trash-alt"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            cartItemsContainer.innerHTML += itemHTML;
        });
    });
    
    // Update summary
    updateCartSummary(totalAmount);
}

/**
 * Update the cart summary totals
 */
function updateCartSummary(totalAmount) {
    const subtotalEl = document.querySelector('.summary-row:nth-child(1) .summary-value');
    const totalEl = document.querySelector('.summary-total-value');
    
    if (subtotalEl) subtotalEl.textContent = `₦${totalAmount.toLocaleString()}`;
    if (totalEl) totalEl.textContent = `₦${totalAmount.toLocaleString()}`;
}

/**
 * Initialize cart control buttons
 */
function initCartControls() {
    // Quantity controls
    initCartQuantityControls();
    
    // Remove buttons
    const removeButtons = document.querySelectorAll('.cart-item-remove');
    removeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const cartItem = this.closest('.cart-item');
            const eventIndex = parseInt(cartItem.dataset.eventIndex);
            const ticketIndex = parseInt(cartItem.dataset.ticketIndex);
            
            removeCartItem(eventIndex, ticketIndex);
        });
    });
    
    // Checkout button
    const checkoutBtn = document.querySelector('.checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            proceedToCheckout();
        });
    }
    
    // Promo code form
    const promoForm = document.querySelector('.promo-form');
    if (promoForm) {
        promoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyPromoCode();
        });
    }
}

/**
 * Initialize quantity controls in cart
 */
function initCartQuantityControls() {
    // Plus buttons
    const plusBtns = document.querySelectorAll('.cart-item .cart-quantity-btn.plus');
    plusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.cart-quantity-input');
            input.value = parseInt(input.value || 1) + 1;
            updateCartItemQuantity(this.closest('.cart-item'), parseInt(input.value));
        });
    });
    
    // Minus buttons
    const minusBtns = document.querySelectorAll('.cart-item .cart-quantity-btn.minus');
    minusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentNode.querySelector('.cart-quantity-input');
            const currentValue = parseInt(input.value || 1);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateCartItemQuantity(this.closest('.cart-item'), parseInt(input.value));
            }
        });
    });
    
    // Manual input
    const quantityInputs = document.querySelectorAll('.cart-item .cart-quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Ensure positive integer
            let value = parseInt(this.value || 1);
            if (isNaN(value) || value < 1) value = 1;
            this.value = value;
            
            updateCartItemQuantity(this.closest('.cart-item'), value);
        });
    });
}

/**
 * Update cart item quantity in storage and UI
 */
function updateCartItemQuantity(cartItem, newQuantity) {
    const eventIndex = parseInt(cartItem.dataset.eventIndex);
    const ticketIndex = parseInt(cartItem.dataset.ticketIndex);
    
    // Get cart from storage
    let cart = JSON.parse(localStorage.getItem('ticketCart') || '[]');
    
    // Update quantity
    cart[eventIndex].tickets[ticketIndex].quantity = newQuantity;
    
    // Save back to storage
    localStorage.setItem('ticketCart', JSON.stringify(cart));
    
    // Update price displayed for this item
    const price = cart[eventIndex].tickets[ticketIndex].price;
    const subtotal = price * newQuantity;
    const priceEl = cartItem.querySelector('.cart-item-price');
    if (priceEl) {
        priceEl.textContent = `₦${subtotal.toLocaleString()}`;
    }
    
    // Recalculate total
    let totalAmount = 0;
    cart.forEach(event => {
        event.tickets.forEach(ticket => {
            totalAmount += ticket.price * ticket.quantity;
        });
    });
    
    // Update summary
    updateCartSummary(totalAmount);
}

/**
 * Remove an item from the cart
 */
function removeCartItem(eventIndex, ticketIndex) {
    // Get cart from storage
    let cart = JSON.parse(localStorage.getItem('ticketCart') || '[]');
    
    // Remove the ticket
    cart[eventIndex].tickets.splice(ticketIndex, 1);
    
    // If no tickets left for this event, remove the event too
    if (cart[eventIndex].tickets.length === 0) {
        cart.splice(eventIndex, 1);
    }
    
    // Save back to storage
    localStorage.setItem('ticketCart', JSON.stringify(cart));
    
    // Update cart count
    updateCartCount();
    
    // Reload cart items
    loadCartItems();
    
    // Reinitialize controls
    initCartControls();
}

/**
 * Apply promo code
 */
function applyPromoCode() {
    const promoInput = document.querySelector('.promo-input');
    const promoCode = promoInput?.value?.trim();
    
    if (!promoCode) {
        alert('Please enter a promo code.');
        return;
    }
    
    // Simulate API call with loading
    const promoBtn = document.querySelector('.promo-btn');
    if (promoBtn) {
        promoBtn.textContent = 'Applying...';
        promoBtn.disabled = true;
    }
    
    setTimeout(() => {
        if (promoCode.toUpperCase() === 'WELCOME10') {
            // Apply 10% discount
            applyDiscount(10);
            alert('Promo code applied successfully! 10% discount');
        } else {
            alert('Invalid promo code. Please try again.');
        }
        
        if (promoBtn) {
            promoBtn.textContent = 'Apply';
            promoBtn.disabled = false;
        }
    }, 1000);
}

/**
 * Apply discount to cart total
 */
function applyDiscount(percentDiscount) {
    // Get cart from storage
    const cart = JSON.parse(localStorage.getItem('ticketCart') || '[]');
    
    // Calculate total before discount
    let totalAmount = 0;
    cart.forEach(event => {
        event.tickets.forEach(ticket => {
            totalAmount += ticket.price * ticket.quantity;
        });
    });
    
    // Calculate discount amount
    const discountAmount = (totalAmount * percentDiscount) / 100;
    const finalTotal = totalAmount - discountAmount;
    
    // Update discount row
    const discountRow = document.createElement('div');
    discountRow.className = 'summary-row discount-row';
    discountRow.innerHTML = `
        <div class="summary-label">Discount (${percentDiscount}%)</div>
        <div class="summary-value">-₦${discountAmount.toLocaleString()}</div>
    `;
    
    // Remove any existing discount row
    const existingDiscountRow = document.querySelector('.discount-row');
    if (existingDiscountRow) {
        existingDiscountRow.remove();
    }
    
    // Add before total
    const totalRow = document.querySelector('.summary-total-row');
    if (totalRow) {
        totalRow.before(discountRow);
    }
    
    // Update total
    const totalEl = document.querySelector('.summary-total-value');
    if (totalEl) {
        totalEl.textContent = `₦${finalTotal.toLocaleString()}`;
    }
    
    // Save discount to session
    sessionStorage.setItem('cartDiscount', percentDiscount);
}

/**
 * Proceed to checkout
 */
function proceedToCheckout() {
    // Show loading animation
    showLoadingAnimation();
    
    // Simulate redirect to payment page
    setTimeout(() => {
        window.location.href = '/checkout';
    }, 1500);
} 