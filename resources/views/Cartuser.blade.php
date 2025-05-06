@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Include jQuery for AJAX functionality -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include our custom cart.js script -->
<script src="{{ asset('js/cart.js') }}"></script>

<style>
    .cart-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
        padding-bottom: 60px;
        position: relative;
        min-height: 70vh;
        width: 85%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .cart-header {
        text-align: left;
        margin-bottom: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        position: relative;
        width: 100%;
    }

    .cart-header::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(to right, rgba(255,255,255,0.05), rgba(255,255,255,0.1), rgba(255,255,255,0.05));
    }

    .cart-title {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 0;
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .order-id {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.5);
        margin-left: 10px;
        background: rgba(255,255,255,0.07);
        padding: 4px 10px;
        border-radius: 20px;
    }

    .items-count {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 5px;
        background: rgba(192, 72, 136, 0.15);
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    .cart-items {
        display: flex;
        flex-direction: column;
        gap: 16px;
        width: 100%;
    }

    .cart-item {
        background: rgba(30, 30, 40, 0.25);
        border-radius: 10px;
        padding: 24px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        flex-direction: column;
        position: relative;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .cart-item:hover {
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    @media (min-width: 768px) {
        .cart-item {
            flex-direction: row;
            align-items: center;
            gap: 24px;
        }
    }

    .ticket-type-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(192, 72, 136, 0.8);
        color: white;
        padding: 4px 12px;
        border-radius: 16px;
        font-size: 11px;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        z-index: 2;
    }

    .item-image {
        width: 100%;
        height: 150px;
        border-radius: 6px;
        overflow: hidden;
        background-color: rgba(255, 255, 255, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        position: relative;
    }

    @media (min-width: 768px) {
        .item-image {
            width: 140px;
            height: 140px;
            margin-bottom: 0;
            flex-shrink: 0;
        }
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-details {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .item-event {
        font-size: 16px;
        font-weight: 600;
        color: white;
        margin-bottom: 12px;
        letter-spacing: 0.2px;
    }

    .item-meta {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
    }

    .item-price-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .item-price {
        font-size: 18px;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
    }

    .item-ticket-count {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.5);
    }

    .ticket-quantity-display {
        font-size: 16px;
        font-weight: 600;
        color: white;
        background: rgba(192, 72, 136, 0.2);
        padding: 2px 10px;
        border-radius: 4px;
        display: inline-block;
        margin-top: 6px;
    }

    .shipping-info {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.5);
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .shipping-info:before {
        content: '\f53f';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        color: rgba(52, 152, 219, 0.7);
    }

    .item-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        align-items: center;
    }

    @media (min-width: 768px) {
        .item-actions {
            margin-top: 0;
        }
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .quantity-btn {
        width: 26px;
        height: 26px;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .quantity-btn:hover {
        background: rgba(255, 255, 255, 0.12);
    }

    .quantity-input {
        width: 36px;
        height: 26px;
        text-align: center;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 4px;
        color: white;
        font-size: 13px;
    }

    .remove-btn {
        background: rgba(231, 76, 60, 0.15);
        color: rgba(231, 76, 60, 0.9);
        border: 1px solid rgba(231, 76, 60, 0.2);
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        margin-left: 5px;
        text-decoration: none;
    }

    .remove-btn:hover {
        background: rgba(231, 76, 60, 0.25);
    }

    .cart-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        flex-wrap: wrap;
        gap: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 20px;
        width: 100%;
    }

    .cart-total {
        display: flex;
        align-items: baseline;
        gap: 12px;
    }

    .total-label {
        font-size: 16px;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.7);
    }

    .total-value {
        font-size: 22px;
        font-weight: 600;
        color: #C04888;
    }

    .cart-buttons {
        display: flex;
        gap: 12px;
    }

    .continue-shopping {
        display: inline-flex;
        align-items: center;
        color: white;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.12);
        padding: 10px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        justify-content: center;
    }

    .continue-shopping i {
        margin-right: 8px;
        font-size: 12px;
    }

    .continue-shopping:hover {
        background: rgba(255, 255, 255, 0.12);
    }

    .checkout-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 20px;
        background: rgba(192, 72, 136, 0.8);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .checkout-btn:hover {
        background: rgba(192, 72, 136, 1);
    }

    .checkout-btn i {
        margin-right: 8px;
        font-size: 12px;
    }

    .clear-cart {
        display: inline-flex;
        align-items: center;
        color: rgba(255, 255, 255, 0.8);
        background: rgba(255, 107, 107, 0.15);
        border: 1px solid rgba(255, 107, 107, 0.25);
        padding: 10px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
        justify-content: center;
    }

    .clear-cart i {
        margin-right: 8px;
        font-size: 12px;
    }

    .clear-cart:hover {
        background: rgba(255, 107, 107, 0.25);
    }

    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        background: rgba(40, 40, 55, 0.5);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .empty-cart-icon {
        font-size: 50px;
        color: rgba(255, 255, 255, 0.15);
        margin-bottom: 20px;
    }

    .empty-cart-title {
        font-size: 22px;
        font-weight: 600;
        color: white;
        margin-bottom: 10px;
    }

    .empty-cart-text {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: 25px;
    }

    .browse-events-btn {
        display: inline-flex;
        align-items: center;
        background: rgba(192, 72, 136, 0.8);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .browse-events-btn i {
        margin-right: 8px;
        font-size: 12px;
    }

    .browse-events-btn:hover {
        background: rgba(192, 72, 136, 1);
    }

    .quantity-btn.selected {
        background-color: rgba(192, 72, 136, 0.8);
        color: white;
    }

    .cart-item:hover .item-image {
        box-shadow: 0 0 15px rgba(192, 72, 136, 0.3);
    }

    /* Add fixed footer styles */
    .fixed-buy-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(30, 30, 40, 0.95);
        backdrop-filter: blur(10px);
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
        box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.2);
        transform: translateY(100%);
        transition: transform 0.3s ease-out;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .fixed-buy-footer.visible {
        transform: translateY(0);
    }

    .fixed-buy-summary {
        display: flex;
        flex-direction: column;
    }

    .fixed-buy-count {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 4px;
    }

    .fixed-buy-total {
        font-size: 18px;
        font-weight: 700;
        color: white;
    }

    .fixed-buy-total .amount {
        color: #C04888;
    }

    .fixed-buy-btn {
        background: #C04888;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .fixed-buy-btn:hover {
        background: #d65c9e;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(192, 72, 136, 0.3);
    }

    .fixed-buy-btn i {
        font-size: 18px;
    }

    /* Cart notification styles */
    .cart-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 12px 20px;
        background: #333;
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .cart-notification.show {
        transform: translateY(0);
        opacity: 1;
    }

    .cart-notification.success {
        background: #4CAF50;
    }

    .cart-notification.error {
        background: #F44336;
    }
</style>

<div class="cart-container">
    <div class="cart-header">
        <div>
            <h1 class="cart-title">Your Order</h1>
        </div>
        <div>
            <a href="/" class="continue-shopping">
                <i class="fa-solid fa-arrow-left"></i> Continue Shopping
            </a>
            @if(count($mycart) > 0 && !Auth::check())
            <a href="{{ url('/clear-cart') }}" class="clear-cart" style="margin-left: 10px;">
                <i class="fa-solid fa-trash"></i> Clear Cart
            </a>
            @endif
        </div>
    </div>

    @if(count($mycart) > 0)
        @php
            $totalAmount = 0;
            $totalItems = 0;

            // Helper functions to safely get values from either object or array
            function getCartItemPrice($item) {
                if(is_object($item)) {
                    return isset($item->cprice) ? $item->cprice : 0;
                } else {
                    return isset($item['price']) ? $item['price'] : 0;
                }
            }

            function getCartItemQuantity($item) {
                if(is_object($item)) {
                    return isset($item->cquantity) ? $item->cquantity : 0;
                } else {
                    return isset($item['quantity']) ? $item['quantity'] : 0;
                }
            }

            function getCartItemName($item) {
                if(is_object($item)) {
                    return isset($item->cname) ? $item->cname : '';
                } else {
                    return isset($item['product_name']) ? $item['product_name'] : '';
                }
            }

            function getCartItemEventName($item) {
                if(is_object($item)) {
                    return isset($item->eventname) ? $item->eventname : 'Product';
                } else {
                    return isset($item['item_name']) ? $item['item_name'] : 'Product';
                }
            }

            function getCartItemImage($item) {
                // Debug: Log the available image fields for debugging
                error_log('Image fields for item: ' . json_encode($item));

                // Check if this is the object structure (from database)
                if(is_object($item)) {
                    // First priority: Cloudinary URLs
                    if(isset($item->image) && !empty($item->image) && strpos($item->image, 'cloudinary.com') !== false) {
                        error_log('Using item->image (Cloudinary): ' . $item->image);
                        return $item->image;
                    }
                    else if(isset($item->event_image) && !empty($item->event_image) && strpos($item->event_image, 'cloudinary.com') !== false) {
                        error_log('Using item->event_image (Cloudinary): ' . $item->event_image);
                        return $item->event_image;
                    }
                    // Second priority: Any image URLs
                    else if(isset($item->image) && !empty($item->image)) {
                        error_log('Using item->image: ' . $item->image);
                        return $item->image;
                    }
                    else if(isset($item->event_image) && !empty($item->event_image)) {
                        error_log('Using item->event_image: ' . $item->event_image);
                        return $item->event_image;
                    }
                    // Third priority: Other potential fields
                    else if(isset($item->cdescription) && !empty($item->cdescription)) {
                        if(strpos($item->cdescription, 'http') === 0) {
                            error_log('Using item->cdescription as URL: ' . $item->cdescription);
                            return $item->cdescription;
                        }
                        error_log('Using item->cdescription as storage path: /storage/' . $item->cdescription);
                        return '/storage/' . $item->cdescription;
                    }
                    else if(isset($item->thumbnail) && !empty($item->thumbnail)) {
                        error_log('Using item->thumbnail: ' . $item->thumbnail);
                        return $item->thumbnail;
                    }

                    // Last priority: Log the failure
                    error_log('No image found in object item');
                }
                // Check if this is the array structure (from session)
                else {
                    // First priority: Cloudinary URLs
                    if(isset($item['image']) && !empty($item['image']) && strpos($item['image'], 'cloudinary.com') !== false) {
                        error_log('Using item[image] (Cloudinary): ' . $item['image']);
                        return $item['image'];
                    }
                    else if(isset($item['event_image']) && !empty($item['event_image']) && strpos($item['event_image'], 'cloudinary.com') !== false) {
                        error_log('Using item[event_image] (Cloudinary): ' . $item['event_image']);
                        return $item['event_image'];
                    }
                    // Second priority: Any image URLs
                    else if(isset($item['image']) && !empty($item['image'])) {
                        error_log('Using item[image]: ' . $item['image']);
                        return $item['image'];
                    }
                    else if(isset($item['event_image']) && !empty($item['event_image'])) {
                        error_log('Using item[event_image]: ' . $item['event_image']);
                        return $item['event_image'];
                    }
                    // Third priority: Other potential fields
                    else if(isset($item['cdescription']) && !empty($item['cdescription'])) {
                        if(strpos($item['cdescription'], 'http') === 0) {
                            error_log('Using item[cdescription] as URL: ' . $item['cdescription']);
                            return $item['cdescription'];
                        }
                        error_log('Using item[cdescription] as storage path: /storage/' . $item['cdescription']);
                        return '/storage/' . $item['cdescription'];
                    }
                    else if(isset($item['thumbnail']) && !empty($item['thumbnail'])) {
                        error_log('Using item[thumbnail]: ' . $item['thumbnail']);
                        return $item['thumbnail'];
                    }

                    // Last priority: Log the failure
                    error_log('No image found in array item');
                }

                error_log('Using placeholder image');
                return '/images/placeholder.jpg';
            }

            function getCartItemId($item, $index) {
                if(is_object($item)) {
                    return isset($item->id) ? $item->id : $index;
                } else {
                    return isset($item['id']) ? $item['id'] : $index;
                }
            }

            foreach($mycart as $cartItem) {
                $totalAmount += getCartItemPrice($cartItem) * getCartItemQuantity($cartItem);
                $totalItems += getCartItemQuantity($cartItem);
            }
        @endphp

        <div class="cart-items">
            @foreach($mycart as $index => $item)
                <div class="cart-item" data-item-id="{{ getCartItemId($item, $index) }}">
                    <div class="ticket-type-badge">
                        {{ getCartItemName($item) }}
                    </div>

                    <div class="item-image">
                        @php
                            $imageUrl = getCartItemImage($item);
                            $eventName = getCartItemEventName($item);
                            error_log("Image URL for {$eventName}: {$imageUrl}");

                            // Additional debug info
                            if(is_object($item)) {
                                error_log("Debug object item:");
                                error_log("- id: " . ($item->id ?? 'null'));
                                error_log("- event_id: " . ($item->event_id ?? 'null'));
                                error_log("- image: " . ($item->image ?? 'null'));
                                error_log("- event_image: " . ($item->event_image ?? 'null'));
                                error_log("- cdescription: " . ($item->cdescription ?? 'null'));
                            } else {
                                error_log("Debug array item:");
                                error_log("- id: " . ($item['id'] ?? 'null'));
                                error_log("- event_id: " . ($item['event_id'] ?? 'null'));
                                error_log("- image: " . ($item['image'] ?? 'null'));
                                error_log("- event_image: " . ($item['event_image'] ?? 'null'));
                                error_log("- cdescription: " . ($item['cdescription'] ?? 'null'));
                            }
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $eventName }}"
                            onerror="this.onerror=null; this.src='/images/placeholder.jpg'; console.error('Failed to load image: ' + this.getAttribute('data-original-src'));"
                            data-original-src="{{ $imageUrl }}">
                    </div>

                    <div class="item-details">
                        <div class="item-event">
                            {{ getCartItemEventName($item) }}
                        </div>

                        <div class="item-meta">
                            <div class="item-price-info">
                                <div class="item-price">₦{{ number_format(getCartItemPrice($item)) }}</div>
                                <div class="ticket-quantity-display">{{ getCartItemQuantity($item) }} ticket{{ getCartItemQuantity($item) > 1 ? 's' : '' }}</div>
                            </div>

                            <div class="item-actions">
                                <a href="/cart/remove/{{ getCartItemId($item, $index) }}" class="remove-btn cart-item-remove">
                                    <i class="fa-solid fa-trash"></i> Remove
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="cart-footer">
            <div class="cart-total">
                <div class="total-label">Total:</div>
                <div class="total-value">₦{{ number_format($totalAmount) }}</div>
            </div>

            <div class="cart-buttons">
                <a href="/" class="continue-shopping">
                    <i class="fa-solid fa-arrow-left"></i> Continue Shopping
                </a>
                <a href="{{ route('checkout') }}" class="checkout-btn">
                    <i class="fas fa-shopping-bag"></i> Checkout
                </a>
            </div>
        </div>

        <!-- Fixed Buy Now Footer -->
        @if(count($mycart) > 0)
        <div class="fixed-buy-footer visible" id="fixedBuyFooter">
            <div class="fixed-buy-summary">
                <div class="fixed-buy-count" id="fixedBuyCount">{{ $totalItems }} {{ $totalItems == 1 ? 'ticket' : 'tickets' }} selected</div>
                <div class="fixed-buy-total">Total: <span class="amount" id="fixedBuyTotal">₦{{ number_format($totalAmount) }}</span></div>
            </div>
            <a href="{{ route('checkout') }}" class="fixed-buy-btn">
                <i class="fa-solid fa-shopping-bag"></i> Checkout
            </a>
        </div>
        @endif
    @else
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fa-solid fa-shopping-cart"></i>
            </div>
            <h2 class="empty-cart-title">Your cart is empty</h2>
            <p class="empty-cart-text">Looks like you haven't added any tickets to your cart yet.</p>
            <a href="/" class="browse-events-btn">
                <i class="fa-solid fa-ticket"></i> Browse Events
            </a>
        </div>
    @endif
</div>

<!-- Cart notification container -->
<div id="cart-notification-container"></div>

<!-- Add this script at the end of the file -->
<script>
    $(function() {
        // Cache DOM elements and HTML templates
        const $cartContainer = $('.cart-container');
        const $cartItems = $('.cart-items');
        const $cartNotifications = $('#cart-notification-container');

        // Create empty cart HTML template only once
        const emptyCartHTML = `
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fa-solid fa-shopping-cart"></i>
                </div>
                <h2 class="empty-cart-title">Your cart is empty</h2>
                <p class="empty-cart-text">Looks like you haven't added any tickets to your cart yet.</p>
                <a href="/" class="browse-events-btn">
                    <i class="fa-solid fa-ticket"></i> Browse Events
                </a>
            </div>
        `;

        // Delegate click handler for better performance - only attach one event listener
        $cartItems.on('click', '.cart-item-remove', function(e) {
            // Fast check if this is the last item in the cart
            if ($('.cart-item').length <= 1) {
                e.preventDefault();
                e.stopPropagation();

                const removeUrl = this.href;
                const $btn = $(this).addClass('disabled');

                // Clean up any existing notifications first
                $('.cart-notification').remove();

                // Use GET request which is faster than DELETE for simple operations
                $.get(removeUrl)
                    .always(function() {
                        showNotification('Last item removed from cart', 'success');

                        // Optimized reload - use timeout to ensure notification is visible
                        setTimeout(function() {
                            window.location.reload();
                        }, 800); // Reduced time for faster response
                    });
            }
        });

        // Optimized notification function
        function showNotification(message, type) {
            const notification = $('<div class="cart-notification ' + type + '">' + message + '</div>');
            $cartNotifications.html(notification); // Replace instead of append for better performance

            // Use requestAnimationFrame for smoother animations
            requestAnimationFrame(function() {
                notification.addClass('show');
            });
        }
    });
</script>
@endsection
