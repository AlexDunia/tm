<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartService
{
    /**
     * Clear the cart for both authenticated and non-authenticated users
     * 
     * @return void
     */
    public static function clearCart()
    {
        Log::info('Clearing cart', [
            'auth_check' => Auth::check(),
            'user_id' => Auth::check() ? Auth::id() : 'guest'
        ]);

        if (Auth::check()) {
            // Clear database cart items
            Cart::where('user_id', Auth::id())->delete();
            
            // Clear cart-related caches
            Cache::forget('cart_totals_' . Auth::id());
            
            // Clear any item-specific caches
            $cachePattern = 'cart_item_' . Auth::id() . '_*';
            foreach (Cache::get($cachePattern, []) as $key => $value) {
                Cache::forget($key);
            }

            // Clear any user-specific cart caches
            Cache::forget('user_cart_' . Auth::id());
            Cache::forget('cart_count_' . Auth::id());
        }

        // Clear session cart data (for both auth and non-auth users)
        Session::forget([
            'cart_items',
            'tname',
            'tprice',
            'tquantity',
            'eventname',
            'totalprice',
            'timage',
            'pending_order',
            'transaction_id',
            'paystack_reference',
            'checkout_data',
            'cart_count',
            'cart_total',
            'cart_data'
        ]);

        // Set flags to indicate cart has been cleared
        Session::put('cart_cleared', true);
        Session::put('cart_cleared_time', now()->timestamp);

        // Clear any global cart count cache
        Cache::tags(['cart'])->flush();
        Cache::forget('global_cart_count');

        // Clear any temporary cart data
        Session::forget('temp_cart');
        Session::forget('cart_backup');

        Log::info('Cart cleared successfully');
    }

    /**
     * Get the current cart count for the header
     * 
     * @return int
     */
    public static function getCartCount()
    {
        // If payment was just successful, return 0
        if (request()->has('payment_success')) {
            return 0;
        }

        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->sum('cquantity');
        }

        $count = 0;
        $cartItems = Session::get('cart_items', []);
        
        foreach ($cartItems as $item) {
            $count += isset($item['quantity']) ? (int)$item['quantity'] : 
                     (isset($item['cquantity']) ? (int)$item['cquantity'] : 0);
        }

        return $count;
    }
} 