/**
 * Cart functionality with AJAX support
 */
document.addEventListener('DOMContentLoaded', function() {
    // Set up CSRF token for all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (csrfToken) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });
    }

    // Handle item removal with AJAX
    $(document).on('click', '.cart-item-remove', function(e) {
        e.preventDefault();

        const cartItem = $(this).closest('.cart-item');
        const itemId = cartItem.data('item-id');

        if (!itemId) return;

        // Fade out item
        cartItem.css('opacity', '0.5');

        // Send AJAX request to remove item
        $.ajax({
            url: '/cart/remove/' + itemId,
            type: 'DELETE',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Remove item with animation
                    cartItem.slideUp(300, function() {
                        $(this).remove();

                        // Check if cart is empty
                        if ($('.cart-item').length === 0) {
                            $('.empty-cart').show();
                            $('.cart-content').hide();
                        } else {
                            // Recalculate totals on server response
                            updateCartTotals();
                        }
                    });

                    // Update cart count in header
                    if (response.total_cart_items !== undefined && window.updateGlobalCartCount) {
                        window.updateGlobalCartCount(response.total_cart_items);
                    }

                    // Show notification
                    showNotification('Item removed from cart');
                }
            },
            error: function() {
                // Restore opacity and show error
                cartItem.css('opacity', '1');
                showNotification('Failed to remove item', 'error');
            }
        });
    });

    // Handle quantity updates with AJAX
    $(document).on('change', '.cart-quantity-input', function() {
        const input = $(this);
        const cartItem = input.closest('.cart-item');
        const itemId = cartItem.data('item-id');
        const quantity = parseInt(input.val());

        if (!itemId || isNaN(quantity) || quantity < 1) return;

        // Disable controls during update
        const plusBtn = cartItem.find('.cart-quantity-btn.plus');
        const minusBtn = cartItem.find('.cart-quantity-btn.minus');
        plusBtn.prop('disabled', true);
        minusBtn.prop('disabled', true);
        input.prop('disabled', true);

        // Send AJAX request to update quantity
        $.ajax({
            url: '/cart/update/' + itemId,
            type: 'PATCH',
            data: { quantity: quantity },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.item) {
                    // Update item total price display
                    const priceDisplay = cartItem.find('.cart-item-price');
                    if (priceDisplay.length) {
                        priceDisplay.text('₦' + parseFloat(response.item.total).toLocaleString());
                    }

                    // Update cart count in header
                    if (response.total_cart_items !== undefined && window.updateGlobalCartCount) {
                        window.updateGlobalCartCount(response.total_cart_items);
                    }

                    // Update cart totals
                    updateCartTotals();
                }
            },
            error: function() {
                showNotification('Failed to update quantity', 'error');
            },
            complete: function() {
                // Re-enable controls
                plusBtn.prop('disabled', false);
                minusBtn.prop('disabled', false);
                input.prop('disabled', false);
            }
        });
    });

    // Helper function to update cart totals via AJAX
    function updateCartTotals() {
        $.ajax({
            url: '/cart/totals',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Update subtotal and total in summary
                    $('.summary-row:nth-child(1) .summary-value').text('₦' + parseFloat(response.subtotal).toLocaleString());
                    $('.summary-total-value').text('₦' + parseFloat(response.total).toLocaleString());
                }
            }
        });
    }

    // Helper function to show notifications
    function showNotification(message, type = 'success') {
        const notification = $('<div class="cart-notification ' + type + '">' + message + '</div>');
        $('body').append(notification);

        // Show with animation
        setTimeout(function() {
            notification.addClass('show');

            // Hide after delay
            setTimeout(function() {
                notification.removeClass('show');
                setTimeout(function() {
                    notification.remove();
                }, 300);
            }, 3000);
        }, 10);
    }
});
