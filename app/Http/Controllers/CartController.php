<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\mctlists;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        // Get cart items based on authentication state
        if (auth()->check()) {
            // For authenticated users, get cart items from database
            $cartItems = Cart::where('user_id', auth()->id())->get();
        } else {
            // For non-authenticated users, get cart items from session
            $sessionCartItems = session()->get('cart_items', []);
            $cartItems = collect();

            foreach ($sessionCartItems as $id => $item) {
                // Convert array item to object-like structure for consistent template handling
                $cartObj = new \stdClass();
                $cartObj->id = $id;
                $cartObj->cname = $item['product_name'] ?? '';
                $cartObj->eventname = $item['item_name'] ?? '';
                $cartObj->cprice = (float)($item['price'] ?? 0);
                $cartObj->cquantity = (int)($item['quantity'] ?? 0);
                $cartObj->ctotalprice = (float)($item['total'] ?? ($cartObj->cprice * $cartObj->cquantity));
                $cartItems->push($cartObj);
            }
        }

        return view('Cartuser', ['mycart' => $cartItems]);
    }

    /**
     * Display the checkout page
     */
    public function checkout()
    {
        // Get cart items based on authentication state
        if (auth()->check()) {
            // For authenticated users, get cart items from database
            $cartItems = Cart::where('user_id', auth()->id())->get();
        } else {
            // For non-authenticated users, get cart items from session
            $sessionCartItems = session()->get('cart_items', []);
            $cartItems = collect();

            foreach ($sessionCartItems as $id => $item) {
                // Convert array item to object-like structure for consistent template handling
                $cartObj = new \stdClass();
                $cartObj->id = $id;
                $cartObj->cname = $item['product_name'] ?? '';
                $cartObj->eventname = $item['item_name'] ?? '';
                $cartObj->cprice = (float)($item['price'] ?? 0);
                $cartObj->cquantity = (int)($item['quantity'] ?? 0);
                $cartObj->ctotalprice = (float)($item['total'] ?? ($cartObj->cprice * $cartObj->cquantity));
                $cartItems->push($cartObj);
            }
        }

        return view('Checkout', ['mycart' => $cartItems]);
    }

    /**
     * Add items to cart with optimized performance
     */
    public function addToCart(Request $request)
    {
        // Validate request and check ticket types
        $hasLegacyTickets = $request->has('product_ids') && $request->has('table_names') && $request->has('quantities');
        $hasNewTickets = $request->has('ticket_ids') && $request->has('ticket_quantities');

        if (!$hasLegacyTickets && !$hasNewTickets) {
            if ($request->ajax() || $request->has('no_redirect')) {
                return response()->json(['success' => false, 'message' => 'No valid ticket data provided']);
            }
            return redirect()->back()->with('message', 'No valid ticket data provided');
        }

        // Use database transaction for integrity
        DB::beginTransaction();
        try {
            $addedItems = 0;
            $updatedItems = [];
            $processedTickets = [];

            // Process legacy table tickets if present
            if ($hasLegacyTickets) {
                // Validate the incoming request data
                $request->validate([
                    'product_ids' => 'required|array',
                    'table_names' => 'required|array',
                    'quantities' => 'required|array',
                ]);

                // Batch query to reduce database calls
                $productIds = array_unique($request->product_ids);
                $products = mctlists::whereIn('id', $productIds)->get()->keyBy('id');

                foreach ($request->product_ids as $key => $productId) {
                    // Skip duplicates
                    $ticketKey = $productId . '-' . $request->table_names[$key];
                    if (in_array($ticketKey, $processedTickets)) {
                        continue;
                    }
                    $processedTickets[] = $ticketKey;

                    $quantity = intval($request->quantities[$key]);
                    if ($quantity <= 0) continue;

                    $addedItems += $quantity;

                    if (!isset($products[$productId])) {
                        continue;
                    }
                    $product = $products[$productId];

                    $nameandprice = $request->table_names[$key];
                    $nameandpricesplit = explode(',', $nameandprice);
                    $namepart = trim($nameandpricesplit[0]);
                    $pricepart = trim($nameandpricesplit[1]);

                    $this->processCartItem($product, $namepart, $pricepart, $quantity, $updatedItems);
                }
            }

            // Process new ticket types if present
            if ($hasNewTickets) {
                $request->validate([
                    'ticket_ids' => 'required|array',
                    'ticket_quantities' => 'required|array',
                ]);

                // Batch query for ticket types
                $ticketIds = array_unique($request->ticket_ids);
                $tickets = TicketType::with('event')->whereIn('id', $ticketIds)->get()->keyBy('id');

                foreach ($request->ticket_ids as $key => $ticketId) {
                    $quantity = intval($request->ticket_quantities[$key]);
                    if ($quantity <= 0) continue;

                    $addedItems += $quantity;

                    if (!isset($tickets[$ticketId])) {
                        continue;
                    }
                    $ticket = $tickets[$ticketId];
                    $product = $ticket->event;

                    $this->processCartItem($product, $ticket->name, $ticket->price, $quantity, $updatedItems);
                }
            }

            DB::commit();

            // Get the total cart count for the response
            $totalCartCount = 0;

            if (auth()->check()) {
                $totalCartCount = Cart::where('user_id', auth()->id())->sum(DB::raw('CAST(cquantity AS INTEGER)'));

                // Cache cart totals for performance
                $cacheKey = 'cart_totals_' . auth()->id();
                Cache::put($cacheKey, [
                    'count' => $totalCartCount,
                    'total' => Cart::where('user_id', auth()->id())->sum(DB::raw('CAST(ctotalprice AS DECIMAL(10,2))'))
                ], now()->addMinutes(10));
            } else {
                // For guest users, count from session
                $cartItems = session()->get('cart_items', []);
                foreach ($cartItems as $item) {
                    $totalCartCount += (int)($item['quantity'] ?? 0);
                }
            }

            if ($request->ajax() || $request->has('no_redirect')) {
                return response()->json([
                    'success' => true,
                    'items_added' => $addedItems,
                    'updated_items' => $updatedItems,
                    'total_cart_items' => $totalCartCount
                ]);
            }

            return redirect()->route('cart')->with('success', 'Items added to cart');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cart error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            if ($request->ajax() || $request->has('no_redirect')) {
                return response()->json(['success' => false, 'message' => 'Failed to add items to cart']);
            }

            return redirect()->back()->with('error', 'Failed to add items to cart');
        }
    }

    /**
     * Process individual cart item with optimized DB operations
     */
    private function processCartItem($product, $name, $price, $quantity, &$updatedItems)
    {
        if (auth()->check()) {
            $existingCartItem = Cart::where('user_id', auth()->id())
                ->where('eventname', $name)
                ->where('cname', $product->name)
                ->first();

            if ($existingCartItem) {
                $existingCartItem->cquantity = $quantity;
                $existingCartItem->ctotalprice = $price * $quantity;
                $existingCartItem->save();

                $updatedItems[] = [
                    'id' => $existingCartItem->id,
                    'type' => $product->name,
                    'name' => $name,
                    'quantity' => $existingCartItem->cquantity,
                    'price' => $price,
                    'total' => $existingCartItem->ctotalprice
                ];
            } else {
                $cartItem = Cart::create([
                    'user_id' => auth()->id(),
                    'cname' => $product->name,
                    'eventname' => $name,
                    'cprice' => $price,
                    'cquantity' => $quantity,
                    'ctotalprice' => $price * $quantity
                ]);

                $updatedItems[] = [
                    'id' => $cartItem->id,
                    'type' => $product->name,
                    'name' => $name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $cartItem->ctotalprice
                ];
            }
        } else {
            // Handle session-based cart
            $cartItems = session()->get('cart_items', []);
            $cartItemKey = $product->id . '-' . md5($name);

            $cartItems[$cartItemKey] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'item_name' => $name,
                'price' => (float)$price,
                'quantity' => (int)$quantity,
                'total' => (float)$price * (int)$quantity
            ];

            session()->put('cart_items', $cartItems);

            $updatedItems[] = [
                'id' => $cartItemKey,
                'type' => $product->name,
                'name' => $name,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $price * $quantity
            ];
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateItem(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            if (auth()->check()) {
                // For authenticated users, update database record
                $cartItem = Cart::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

                $cartItem->cquantity = $request->quantity;
                $cartItem->ctotalprice = $cartItem->cprice * $request->quantity;
                $cartItem->save();

                // Get cart count for response
                $totalCartCount = Cart::where('user_id', auth()->id())->sum(DB::raw('CAST(cquantity AS INTEGER)'));

                return response()->json([
                    'success' => true,
                    'item' => [
                        'id' => $cartItem->id,
                        'quantity' => $cartItem->cquantity,
                        'total' => $cartItem->ctotalprice
                    ],
                    'total_cart_items' => $totalCartCount
                ]);
            } else {
                // For non-authenticated users, update session cart
                $cartItems = session()->get('cart_items', []);

                if (!isset($cartItems[$id])) {
                    return $this->securityFailureResponse($request, 'Cart item not found');
                }

                $cartItems[$id]['quantity'] = $request->quantity;
                $cartItems[$id]['total'] = $cartItems[$id]['price'] * $request->quantity;
                session()->put('cart_items', $cartItems);

                // Get cart count for response
                $totalCartCount = 0;
                foreach ($cartItems as $item) {
                    $totalCartCount += (int)($item['quantity'] ?? 0);
                }

                return response()->json([
                    'success' => true,
                    'item' => [
                        'id' => $id,
                        'quantity' => $cartItems[$id]['quantity'],
                        'total' => $cartItems[$id]['total']
                    ],
                    'total_cart_items' => $totalCartCount
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Cart update error: ' . $e->getMessage(), [
                'exception' => $e,
                'id' => $id,
                'request' => $request->all()
            ]);

            return $this->securityFailureResponse($request);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request, $id)
    {
        try {
            if (auth()->check()) {
                // For authenticated users, remove from database
                $cartItem = Cart::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

                $cartItem->delete();

                // Get updated cart count for response
                $totalCartCount = Cart::where('user_id', auth()->id())->sum(DB::raw('CAST(cquantity AS INTEGER)'));

                // Update cache
                $cacheKey = 'cart_totals_' . auth()->id();
                Cache::put($cacheKey, [
                    'count' => $totalCartCount,
                    'total' => Cart::where('user_id', auth()->id())->sum(DB::raw('CAST(ctotalprice AS DECIMAL(10,2))'))
                ], now()->addMinutes(10));

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'total_cart_items' => $totalCartCount
                ]);
            } else {
                // For non-authenticated users, remove from session
                $cartItems = session()->get('cart_items', []);

                if (!isset($cartItems[$id])) {
                    return $this->securityFailureResponse($request, 'Cart item not found');
                }

                unset($cartItems[$id]);
                session()->put('cart_items', $cartItems);

                // Get updated cart count for response
                $totalCartCount = 0;
                foreach ($cartItems as $item) {
                    $totalCartCount += (int)($item['quantity'] ?? 0);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'total_cart_items' => $totalCartCount
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Cart remove error: ' . $e->getMessage(), [
                'exception' => $e,
                'id' => $id,
                'request' => $request->all()
            ]);

            return $this->securityFailureResponse($request);
        }
    }

    /**
     * Handle security failure responses consistently
     */
    private function securityFailureResponse(Request $request, $message = 'Invalid request', $status = 400)
    {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $message], $status);
        }
        return redirect()->route('cart')->with('error', $message);
    }

    /**
     * Get total cart items count for current user or session
     * This is used for the cart count badge in header
     */
    public static function getCartCount()
    {
        if (auth()->check()) {
            return auth()->user()->relatewithcart()->sum(DB::raw('CAST(cquantity AS INTEGER)'));
        } else {
            $count = 0;
            $cartItems = session()->get('cart_items', []);

            foreach ($cartItems as $item) {
                $count += isset($item['quantity']) ? (int)$item['quantity'] :
                          (isset($item['cquantity']) ? (int)$item['cquantity'] : 0);
            }

            return $count;
        }
    }

    /**
     * Get cart totals for AJAX requests
     */
    public function getCartTotals(Request $request)
    {
        try {
            if (auth()->check()) {
                // For authenticated users, calculate from database
                $subtotal = Cart::where('user_id', auth()->id())->sum(DB::raw('CAST(ctotalprice AS DECIMAL(10,2))'));

                // You can add discount logic here if needed
                $total = $subtotal;

                return response()->json([
                    'success' => true,
                    'subtotal' => $subtotal,
                    'total' => $total
                ]);
            } else {
                // For non-authenticated users, calculate from session
                $cartItems = session()->get('cart_items', []);
                $subtotal = 0;

                foreach ($cartItems as $item) {
                    $subtotal += (float)($item['total'] ?? 0);
                }

                // You can add discount logic here if needed
                $total = $subtotal;

                return response()->json([
                    'success' => true,
                    'subtotal' => $subtotal,
                    'total' => $total
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Cart totals error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get cart totals'
            ], 500);
        }
    }

    /**
     * Store pending order information in session before payment
     */
    public function preparePayment(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'email' => 'required|email',
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'phone' => 'required|string|max:20',
                'amount' => 'required|numeric',
                'ticket_data' => 'required|array'
            ]);

            // Generate a unique order reference
            $orderRef = 'ORD-' . strtoupper(uniqid()) . '-' . rand(1000, 9999);

            // Store order data in session
            $orderData = [
                'ref' => $orderRef,
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'amount' => $request->amount,
                'ticket_data' => $request->ticket_data,
                'created_at' => now()->toDateTimeString()
            ];

            session()->put('pending_order', $orderData);

            return response()->json([
                'success' => true,
                'order_ref' => $orderRef
            ]);
        } catch (\Exception $e) {
            Log::error('Prepare payment error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to prepare payment data'
            ], 500);
        }
    }

    /**
     * Handle successful payment processing
     */
    public function processSuccessfulPayment($paymentRef)
    {
        try {
            // Get pending order from session
            $pendingOrder = session()->get('pending_order');

            if (!$pendingOrder) {
                Log::error('No pending order found for payment: ' . $paymentRef);
                return response()->json([
                    'success' => false,
                    'message' => 'No order information found'
                ], 404);
            }

            // Process the order (save to orders table, etc.)
            // This would typically involve creating an actual order record

            // Clear the cart after successful payment
            if (auth()->check()) {
                Cart::where('user_id', auth()->id())->delete();
            } else {
                session()->forget('cart_items');
            }

            // Clear the pending order but keep ticket data for success page
            session()->put('completed_order', $pendingOrder);
            session()->forget('pending_order');

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'payment_ref' => $paymentRef,
                'redirect_url' => route('success')
            ]);
        } catch (\Exception $e) {
            Log::error('Process payment error: ' . $e->getMessage(), [
                'exception' => $e,
                'payment_ref' => $paymentRef
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing payment'
            ], 500);
        }
    }
}
