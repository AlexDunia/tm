<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TestCartController extends Controller
{
    /**
     * Test adding an item to the cart for authenticated users
     */
    public function testAddToCart()
    {
        // Make sure user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        try {
            // Create a test cart item
            $cartItem = Cart::create([
                'user_id' => auth()->id(),
                'cname' => 'Test Product',
                'eventname' => 'Test Event',
                'cprice' => 100.00,
                'cquantity' => 1,
                'ctotalprice' => 100.00
            ]);

            // Log the created cart item
            Log::info('Test cart item created:', [
                'item_id' => $cartItem->id,
                'user_id' => $cartItem->user_id,
                'cname' => $cartItem->cname,
                'eventname' => $cartItem->eventname
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test cart item added successfully',
                'cart_item' => $cartItem
            ]);
        } catch (\Exception $e) {
            Log::error('Test cart error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add test cart item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View cart items for authenticated user
     */
    public function testViewCart()
    {
        // Make sure user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        // Get cart items
        $cartItems = Cart::where('user_id', auth()->id())->get();

        // Log the cart items
        Log::info('Test view cart items:', [
            'user_id' => auth()->id(),
            'cart_count' => $cartItems->count(),
            'items' => $cartItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->cname,
                    'event' => $item->eventname,
                    'quantity' => $item->cquantity,
                    'price' => $item->cprice,
                    'total' => $item->ctotalprice
                ];
            })->toArray()
        ]);

        return response()->json([
            'success' => true,
            'cart_items' => $cartItems,
            'count' => $cartItems->count()
        ]);
    }

    /**
     * Advanced test to debug cart creation issues
     */
    public function debugCartCreation()
    {
        // Check authentication
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ]);
        }

        try {
            // Step 1: Check if user model is accessible
            $user = Auth::user();
            Log::info('User retrieved', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // Step 2: Test simple DB insert
            $data = [
                'user_id' => $user->id,
                'cname' => 'DEBUG TEST PRODUCT',
                'clocation' => 'Test Location',
                'eventname' => 'Debug Test Event',
                'cprice' => 99.99,
                'cquantity' => 1,
                'ctotalprice' => 99.99
            ];

            Log::info('Attempting to create cart item with data', $data);

            $cartItem = new Cart();
            foreach ($data as $key => $value) {
                $cartItem->$key = $value;
            }
            $cartItem->save();

            Log::info('Cart item created successfully with save() method', [
                'cart_id' => $cartItem->id
            ]);

            // Step 3: Test Cart::create method
            $createData = [
                'user_id' => $user->id,
                'cname' => 'CREATE TEST PRODUCT',
                'clocation' => 'Create Test Location',
                'eventname' => 'Create Debug Event',
                'cprice' => 199.99,
                'cquantity' => 2,
                'ctotalprice' => 399.98
            ];

            Log::info('Attempting to create cart item with Cart::create', $createData);

            $createdCartItem = Cart::create($createData);

            Log::info('Cart item created successfully with create() method', [
                'cart_id' => $createdCartItem->id
            ]);

            // Return debug information
            return response()->json([
                'success' => true,
                'message' => 'Debug test completed successfully',
                'test_items' => [
                    'save_method' => $cartItem,
                    'create_method' => $createdCartItem
                ],
                'all_cart_items' => Cart::where('user_id', $user->id)->get()
            ]);

        } catch (\Exception $e) {
            Log::error('Debug cart creation error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error during debug test: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
