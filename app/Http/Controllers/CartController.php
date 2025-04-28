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
        return view('checkout');
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

            // Cache cart totals for performance
            if (auth()->check()) {
                $cacheKey = 'cart_totals_' . auth()->id();
                Cache::put($cacheKey, [
                    'count' => Cart::where('user_id', auth()->id())->sum(DB::raw('CAST(cquantity AS INTEGER)')),
                    'total' => Cart::where('user_id', auth()->id())->sum(DB::raw('CAST(ctotalprice AS DECIMAL(10,2))'))
                ], now()->addMinutes(10));
            }

            if ($request->ajax() || $request->has('no_redirect')) {
                return response()->json([
                    'success' => true,
                    'items_added' => $addedItems,
                    'updated_items' => $updatedItems
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
    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            if (auth()->check()) {
                $cartItem = Cart::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

                $cartItem->cquantity = (int)$request->quantity;
                $cartItem->ctotalprice = (float)$cartItem->cprice * (int)$request->quantity;
                $cartItem->save();

                // Invalidate cart cache
                Cache::forget('cart_totals_' . auth()->id());

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'quantity' => $cartItem->cquantity,
                        'total' => $cartItem->ctotalprice,
                        'cart_total' => Cart::where('user_id', auth()->id())->sum(DB::raw('CAST(ctotalprice AS DECIMAL(10,2))'))
                    ]);
                }
            } else {
                $cartItems = session()->get('cart_items', []);

                if (isset($cartItems[$id])) {
                    $cartItems[$id]['quantity'] = $request->quantity;
                    $cartItems[$id]['total'] = $cartItems[$id]['price'] * $request->quantity;
                    session()->put('cart_items', $cartItems);

                    if ($request->ajax()) {
                        // Calculate total from all items
                        $cartTotal = 0;
                        foreach ($cartItems as $item) {
                            $cartTotal += $item['total'];
                        }

                        return response()->json([
                            'success' => true,
                            'quantity' => $cartItems[$id]['quantity'],
                            'total' => $cartItems[$id]['total'],
                            'cart_total' => $cartTotal
                        ]);
                    }
                }
            }

            return redirect()->back()->with('success', 'Cart updated successfully');
        } catch (\Exception $e) {
            Log::error('Update cart error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update cart']);
            }

            return redirect()->back()->with('error', 'Failed to update cart');
        }
    }

    /**
     * Remove an item from the cart with enhanced security
     */
    public function removeItem(Request $request, $id)
    {
        // Validate inputs
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id === false) {
            return $this->securityFailureResponse($request, 'Invalid item ID format');
        }

        // Sanitize inputs to prevent XSS in logs
        $ticketType = $request->input('ticket_type');
        if ($ticketType) {
            $ticketType = filter_var($ticketType, FILTER_SANITIZE_STRING);
        }

        // Rate limiting to prevent brute force attempts
        // Only allow 10 delete attempts per minute per IP
        $ipAddress = $request->ip();
        $rateLimitKey = 'cart_delete_' . md5($ipAddress);
        $attempts = Cache::get($rateLimitKey, 0);

        if ($attempts > 10) {
            Log::warning('SECURITY - Rate limit exceeded for cart item removal', [
                'ip' => $ipAddress,
                'attempts' => $attempts
            ]);
            return $this->securityFailureResponse($request, 'Too many attempts, please try again later');
        }

        Cache::put($rateLimitKey, $attempts + 1, now()->addMinutes(1));

        // Log the removal request with sanitized data
        Log::info('CART DEBUG - Remove Item Request', [
            'id' => $id,
            'ticket_type' => $ticketType ? htmlspecialchars($ticketType) : null,
            'is_authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'ip' => $ipAddress
        ]);

        // For authenticated users, use database with strict ownership validation
        if (auth()->check()) {
            // Only find items belonging to the current user
            $cartItem = Cart::where('user_id', auth()->id())->find($id);

            // If ID doesn't exist but we have a ticket type, try to find by ticket type (but only one item)
            if (!$cartItem && $ticketType) {
                $cartItem = Cart::where('user_id', auth()->id())
                    ->where('cname', $ticketType)
                    ->first();
            }

            if ($cartItem) {
                // Double-check ownership before deletion
                if ($cartItem->user_id !== auth()->id()) {
                    Log::warning('SECURITY - User attempted to delete another user\'s cart item', [
                        'user_id' => auth()->id(),
                        'attempted_item_id' => $id,
                        'item_owner' => $cartItem->user_id
                    ]);
                    return $this->securityFailureResponse($request, 'Unauthorized action');
                }

                $cartItem->delete();

                // Invalidate cart cache to prevent stale data
                Cache::forget('cart_totals_' . auth()->id());

                if ($request->ajax()) {
                    return response()->json(['success' => true, 'message' => 'Item removed successfully']);
                }
                return redirect()->route('cart')->with('success', 'Item removed successfully');
            }
        }
        // For non-authenticated users, use session with proper validation
        else {
            $cartItems = session()->get('cart_items', []);

            // Try to remove by ID first with strict validation
            if (isset($cartItems[$id]) && is_array($cartItems[$id])) {
                // Save item info for logging
                $removedItem = $cartItems[$id];

                // Delete just this specific item
                unset($cartItems[$id]);
                session()->put('cart_items', $cartItems);

                Log::info('CART DEBUG - Removed session item by ID', [
                    'id' => $id
                ]);

                if ($request->ajax()) {
                    return response()->json(['success' => true, 'message' => 'Item removed successfully']);
                }
                return redirect()->route('cart')->with('success', 'Item removed successfully');
            }

            // If we couldn't find by ID and we have a ticket type, remove just one item of that type
            if ($ticketType) {
                // Find the first item matching the ticket type
                foreach ($cartItems as $key => $item) {
                    if (isset($item['product_name']) && $item['product_name'] == $ticketType) {
                        // Delete just this specific item
                        unset($cartItems[$key]);
                        session()->put('cart_items', $cartItems);

                        Log::info('CART DEBUG - Removed session item by ticket type', [
                            'ticket_type' => htmlspecialchars($ticketType),
                            'key' => $key
                        ]);

                        if ($request->ajax()) {
                            return response()->json(['success' => true, 'message' => 'Item removed successfully']);
                        }
                        return redirect()->route('cart')->with('success', 'Item removed successfully');
                    }
                }
            }
        }

        // If we get here, nothing was removed
        return $this->securityFailureResponse($request, 'Item not found', 404);
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
}
