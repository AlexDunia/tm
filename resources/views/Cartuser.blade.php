@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
</style>

<div class="cart-container">
    <div class="cart-header">
        <div>
            <h1 class="cart-title">Your Order <span class="order-id">#ORD317140</span></h1>
            <div class="items-count">{{ count($mycart) }} {{ count($mycart) == 1 ? 'item' : 'items' }} in cart</div>
        </div>
        <div>
            <a href="{{ route('home') }}" class="continue-shopping">
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

            foreach($mycart as $cartItem) {
                if (isset($cartItem->cprice) && isset($cartItem->cquantity)) {
                    $totalAmount += $cartItem->cprice * $cartItem->cquantity;
                    $totalItems += $cartItem->cquantity;
                } else if (isset($cartItem['price']) && isset($cartItem['quantity'])) {
                    $totalAmount += $cartItem['price'] * $cartItem['quantity'];
                    $totalItems += $cartItem['quantity'];
                }
            }
        @endphp

        <div class="cart-items">
            @foreach($mycart as $item)
                <div class="cart-item">
                    <div class="ticket-type-badge">
                        {{ $item->cname ?? $item->product_name ?? '' }}
                    </div>

                    <div class="item-image">
                        @if(isset($item->image) && $item->image)
                        <img src="{{ asset($item->image) }}" alt="{{ $item->eventname ?? $item->item_name ?? 'Product' }}" onerror="this.onerror=null; this.src='/images/placeholder.jpg'">
                        @elseif(isset($item->cdescription) && $item->cdescription)
                        <img src="{{ asset('storage/' . $item->cdescription) }}" alt="{{ $item->eventname ?? $item->item_name ?? 'Product' }}" onerror="this.onerror=null; this.src='/images/placeholder.jpg'">
                        @else
                            <img src="{{ asset('/images/placeholder.jpg') }}" alt="{{ $item->eventname ?? $item->item_name ?? 'Product' }}">
                        @endif
                    </div>

                    <div class="item-details">
                        <div class="item-event">
                            {{ $item->eventname ?? $item->item_name ?? '' }}
                        </div>

                        <div class="item-meta">
                            <div class="item-price-info">
                                <div class="item-price">₦{{ number_format($item->cprice ?? $item->price ?? 0) }}</div>
                                <div class="ticket-quantity-display">
                                    {{ $item->cquantity ?? $item->quantity ?? 0 }}
                                    ticket{{ (($item->cquantity ?? $item->quantity ?? 0) > 1) ? 's' : '' }}
                                </div>
                                <div class="shipping-info">
                                    Eligible for returns up to 30 days
                                </div>
                            </div>

                            <div class="item-actions">
                                <div class="quantity-controls">
                                    <button class="quantity-btn decrease-btn" onclick="updateQuantity('{{ isset($item->id) ? $item->id : $loop->index }}', '{{ $item->cname ?? $item->product_name ?? '' }}', 'decrease', {{ $item->cquantity ?? $item->quantity ?? 0 }})">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <input type="number" class="quantity-input" value="{{ $item->cquantity ?? $item->quantity ?? 0 }}" min="1" max="50" readonly>
                                    <button class="quantity-btn increase-btn" onclick="updateQuantity('{{ isset($item->id) ? $item->id : $loop->index }}', '{{ $item->cname ?? $item->product_name ?? '' }}', 'increase', {{ $item->cquantity ?? $item->quantity ?? 0 }})">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>

                                <button class="remove-btn" onclick="removeCartItem('{{ isset($item->id) ? $item->id : $loop->index }}', '{{ $item->cname ?? $item->product_name ?? '' }}')">
                                    <i class="fa-solid fa-trash"></i> Remove
                                </button>
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
                <a href="{{ route('home') }}" class="continue-shopping">
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
            <a href="{{ route('home') }}" class="browse-events-btn">
                <i class="fa-solid fa-calendar-alt"></i> Browse Events
            </a>
        </div>
    @endif
</div>

<script>
    function updateQuantity(itemId, itemType, action, currentQty) {
        if (!itemId || !itemType) {
            console.error('Invalid item data');
            alert('Invalid item information. Please refresh the page and try again.');
            return;
        }

        const inputEl = event.target.closest('.quantity-controls').querySelector('.quantity-input');
        let newQty = parseInt(inputEl.value);

        if (action === 'increase') {
            newQty = currentQty + 1;
            inputEl.value = newQty;

            const decreaseBtn = event.target.closest('.quantity-controls').querySelector('.decrease-btn');
            decreaseBtn.classList.add('selected');

            updateCartItemQuantity(itemId, itemType, newQty);
        } else if (action === 'decrease' && currentQty > 1) {
            newQty = currentQty - 1;
            inputEl.value = newQty;

            if (newQty === 1) {
                const decreaseBtn = event.target;
                decreaseBtn.classList.remove('selected');
            }

            updateCartItemQuantity(itemId, itemType, newQty);
        }
    }

    function updateCartItemQuantity(itemId, itemType, newQuantity) {
        const cartItem = event.target.closest('.cart-item');
        cartItem.style.opacity = '0.7';
        cartItem.style.pointerEvents = 'none';

        fetch(`/update-cart/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                item_id: itemId,
                quantity: newQuantity,
                ticket_type: itemType
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update DOM with the new values
                const itemPrice = cartItem.querySelector('.item-price');
                const ticketQuantity = cartItem.querySelector('.ticket-quantity-display');

                // Update quantities in the DOM
                if (ticketQuantity) {
                    ticketQuantity.textContent = `${newQuantity} ticket${newQuantity > 1 ? 's' : ''}`;
                }

                // Update the cart totals
                const cartTotal = document.querySelector('.total-value');
                const fixedBuyTotal = document.getElementById('fixedBuyTotal');
                const fixedBuyCount = document.getElementById('fixedBuyCount');

                if (cartTotal && data.cart_total) {
                    cartTotal.textContent = `₦${formatNumber(data.cart_total)}`;
                }

                if (fixedBuyTotal && data.cart_total) {
                    fixedBuyTotal.textContent = `₦${formatNumber(data.cart_total)}`;
                }

                if (fixedBuyCount) {
                    // Calculate total items
                    let totalItems = 0;
                    document.querySelectorAll('.quantity-input').forEach(input => {
                        totalItems += parseInt(input.value);
                    });

                    fixedBuyCount.textContent = `${totalItems} ${totalItems === 1 ? 'ticket' : 'tickets'} selected`;
                }

                cartItem.style.opacity = '1';
                cartItem.style.pointerEvents = 'auto';
            } else {
                cartItem.style.opacity = '1';
                cartItem.style.pointerEvents = 'auto';
                alert('Failed to update quantity: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            cartItem.style.opacity = '1';
            cartItem.style.pointerEvents = 'auto';
            console.error('Error updating quantity:', error);
            alert('An error occurred while updating quantity');
        });
    }

    function removeCartItem(itemId, itemType) {
        if (!itemId || typeof itemId !== 'string' && typeof itemId !== 'number') {
            console.error('Invalid item ID');
            alert('Invalid item information. Please refresh the page and try again.');
            return;
        }

        if (!itemType || typeof itemType !== 'string') {
            console.error('Invalid ticket type');
            alert('Invalid ticket information. Please refresh the page and try again.');
            return;
        }

        itemId = itemId.toString().trim();
        itemType = itemType.toString().trim();

        if (confirm('Are you sure you want to remove this item from your cart?')) {
            try {
                const cartItem = event.target.closest('.cart-item');
                if (!cartItem) {
                    console.error('Could not find parent cart item');
                    alert('An error occurred. Please refresh the page and try again.');
                    return;
                }

                cartItem.style.opacity = '0.7';
                cartItem.style.pointerEvents = 'none';

                // Use fetch for AJAX removal instead of form submission
                fetch(`/remove-cart-item/${encodeURIComponent(itemId)}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ticket_type: itemType
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Remove the item from DOM
                        cartItem.style.opacity = '0';
                        cartItem.style.maxHeight = '0';

                        setTimeout(() => {
                            cartItem.remove();

                            // Check if cart is empty and reload if needed
                            const cartItems = document.querySelectorAll('.cart-item');
                            if (cartItems.length === 0) {
                                location.reload();
                            } else {
                                // Update counts and totals
                                updateCartTotals();
                            }
                        }, 300);
                    } else {
                        cartItem.style.opacity = '1';
                        cartItem.style.pointerEvents = 'auto';
                        alert(data.message || 'Failed to remove item');
                    }
                })
                .catch(error => {
                    cartItem.style.opacity = '1';
                    cartItem.style.pointerEvents = 'auto';
                    console.error('Error removing item:', error);
                    alert('An error occurred while removing the item');
                });
            } catch (error) {
                console.error('Error removing cart item:', error);
                alert('An unexpected error occurred. Please try again or contact support.');
            }
        }
    }

    function updateCartTotals() {
        // Calculate new totals from DOM
        let totalAmount = 0;
        let totalItems = 0;

        document.querySelectorAll('.cart-item').forEach(item => {
            const priceText = item.querySelector('.item-price').textContent.replace('₦', '').replace(/,/g, '');
            const quantityInput = item.querySelector('.quantity-input');

            const price = parseFloat(priceText);
            const quantity = parseInt(quantityInput.value);

            if (!isNaN(price) && !isNaN(quantity)) {
                totalAmount += price * quantity;
                totalItems += quantity;
            }
        });

        // Update total displays
        const cartTotal = document.querySelector('.total-value');
        const fixedBuyTotal = document.getElementById('fixedBuyTotal');
        const fixedBuyCount = document.getElementById('fixedBuyCount');
        const itemsCount = document.querySelector('.items-count');

        if (cartTotal) {
            cartTotal.textContent = `₦${formatNumber(totalAmount)}`;
        }

        if (fixedBuyTotal) {
            fixedBuyTotal.textContent = `₦${formatNumber(totalAmount)}`;
        }

        if (fixedBuyCount) {
            fixedBuyCount.textContent = `${totalItems} ${totalItems === 1 ? 'ticket' : 'tickets'} selected`;
        }

        if (itemsCount) {
            const cartItemCount = document.querySelectorAll('.cart-item').length;
            itemsCount.textContent = `${cartItemCount} ${cartItemCount === 1 ? 'item' : 'items'} in cart`;
        }
    }

    function formatNumber(number) {
        return new Intl.NumberFormat().format(number);
    }
</script>
@endsection
