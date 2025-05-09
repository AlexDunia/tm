<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\mctlists;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Services\CartService;
use Illuminate\Support\Facades\Session;

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

            // Fetch event details for cart items that have event_id
            foreach($cartItems as $item) {
                if (!empty($item->event_id)) {
                    $event = mctlists::find($item->event_id);
                    if ($event) {
                        // Make sure images are available from the original event
                        $item->image = !empty($item->image) ? $item->image : $event->image;
                        $item->event_image = !empty($item->event_image) ? $item->event_image : $event->heroimage;
                    }
                }
            }
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

                // Make sure images are available
                $cartObj->image = $item['image'] ?? '';
                $cartObj->event_image = $item['event_image'] ?? '';

                // If we have event_id, try to fetch original images if missing
                if (isset($item['event_id']) && empty($cartObj->image)) {
                    $event = mctlists::find($item['event_id']);
                    if ($event) {
                        $cartObj->image = $event->image;
                        $cartObj->event_image = $event->heroimage;
                    }
                }

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

            // Fetch event details for cart items that have event_id
            foreach($cartItems as $item) {
                if (!empty($item->event_id)) {
                    $event = mctlists::find($item->event_id);
                    if ($event) {
                        // Make sure images are available from the original event
                        $item->image = !empty($item->image) ? $item->image : $event->image;
                        $item->event_image = !empty($item->event_image) ? $item->event_image : $event->heroimage;
                    }
                }
            }
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

                // Make sure images are available
                $cartObj->image = $item['image'] ?? '';
                $cartObj->event_image = $item['event_image'] ?? '';

                // If we have event_id, try to fetch original images if missing
                if (isset($item['event_id']) && empty($cartObj->image)) {
                    $event = mctlists::find($item['event_id']);
                    if ($event) {
                        $cartObj->image = $event->image;
                        $cartObj->event_image = $event->heroimage;
                    }
                }

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
                $totalCartCount = Cart::where('user_id', auth()->id())->sum(DB::raw('CAST(cquantity AS SIGNED)'));

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
        // Add debug logging
        Log::info('Processing cart item', [
            'auth_check' => auth()->check(),
            'user_id' => auth()->check() ? auth()->id() : 'guest',
            'product_name' => $product->name,
            'item_name' => $name,
            'price' => $price,
            'quantity' => $quantity
        ]);

        if (auth()->check()) {
            // For authenticated users, save to database
            Log::info('Authenticated user cart processing');

            // Use a cache key specific to this product for this user
            $cacheKey = 'cart_item_' . auth()->id() . '_' . md5($product->name . $name);

            // Try to get from cache first
            $existingCartItem = Cache::remember($cacheKey, now()->addMinutes(30), function() use ($product, $name) {
                // Use the optimized model method
                return Cart::findUserCartItem(auth()->id(), $product->name, $name);
            });

            if ($existingCartItem) {
                // Update existing cart item
                Log::info('Updating existing cart item', [
                    'cart_id' => $existingCartItem->id,
                    'old_quantity' => $existingCartItem->cquantity,
                    'new_quantity' => $quantity
                ]);

                $existingCartItem->cquantity = $quantity;
                $existingCartItem->ctotalprice = $price * $quantity;
                $existingCartItem->save();

                // Clear the cache for this item
                Cache::forget($cacheKey);

                $updatedItems[] = [
                    'id' => $existingCartItem->id,
                    'type' => $product->name,
                    'name' => $name,
                    'quantity' => $existingCartItem->cquantity,
                    'price' => $price,
                    'total' => $existingCartItem->ctotalprice
                ];
            } else {
                // Create new cart item
                Log::info('Creating new cart item');

                try {
                    // Filter and sanitize inputs
                    $safeProductName = htmlspecialchars(strip_tags($product->name), ENT_QUOTES, 'UTF-8');
                    $safeEventName = htmlspecialchars(strip_tags($name), ENT_QUOTES, 'UTF-8');
                    $safeLocation = htmlspecialchars(strip_tags($product->location ?? 'Not specified'), ENT_QUOTES, 'UTF-8');

                    // Get the image URL from the product
                    $imageUrl = $product->image ?? '';
                    $heroImage = $product->heroimage ?? '';

                    // Add a log to debug image URLs
                    Log::info('Adding cart item with images', [
                        'product_id' => $product->id,
                        'image_url' => $imageUrl,
                        'hero_image' => $heroImage,
                        'name' => $safeProductName
                    ]);

                    $cartItem = Cart::create([
                        'user_id' => auth()->id(),
                        'cname' => $safeProductName,
                        'eventname' => $safeEventName,
                        'cprice' => (float)$price,
                        'cquantity' => (int)$quantity,
                        'ctotalprice' => (float)$price * (int)$quantity,
                        'clocation' => $safeLocation,
                        'image' => $imageUrl, // Add the image URL
                        'event_image' => $heroImage, // Add the hero image as event_image
                        'cdescription' => $imageUrl, // For backward compatibility
                        'event_id' => $product->id // Add the event ID
                    ]);

                    Log::info('Created new cart item', [
                        'cart_id' => $cartItem->id,
                        'user_id' => $cartItem->user_id,
                        'cname' => $cartItem->cname,
                        'eventname' => $cartItem->eventname,
                        'image' => $cartItem->image,
                        'event_image' => $cartItem->event_image
                    ]);

                    $updatedItems[] = [
                        'id' => $cartItem->id,
                        'type' => $product->name,
                        'name' => $name,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $cartItem->ctotalprice,
                        'image' => $imageUrl,
                        'event_image' => $heroImage
                    ];

                    // Update global cart totals cache
                    $this->updateCartTotalsCache();

                } catch (\Exception $e) {
                    Log::error('Error creating cart item: ' . $e->getMessage(), [
                        'exception' => $e,
                        'user_id' => auth()->id(),
                        'product_name' => $product->name,
                        'item_name' => $name
                    ]);
                    throw $e;
                }
            }
        } else {
            // For non-authenticated users, save to session
            Log::info('Non-authenticated user - using session');

            $cartItems = session()->get('cart_items', []);
            $cartItemKey = $product->id . '-' . md5($name);

            // Sanitize inputs before storing in session
            $safeProductName = htmlspecialchars(strip_tags($product->name), ENT_QUOTES, 'UTF-8');
            $safeItemName = htmlspecialchars(strip_tags($name), ENT_QUOTES, 'UTF-8');

            // Get the image URLs from the product
            $imageUrl = $product->image ?? '';
            $heroImage = $product->heroimage ?? '';

            $cartItems[$cartItemKey] = [
                'product_id' => $product->id,
                'product_name' => $safeProductName,
                'item_name' => $safeItemName,
                'price' => (float)$price,
                'quantity' => (int)$quantity,
                'total' => (float)$price * (int)$quantity,
                'image' => $imageUrl, // Add the image URL
                'event_image' => $heroImage, // Add the hero image
                'cdescription' => $imageUrl, // For backward compatibility
                'event_id' => $product->id // Add the event ID
            ];

            session()->put('cart_items', $cartItems);

            Log::info('Session cart updated', [
                'cart_key' => $cartItemKey,
                'session_items_count' => count($cartItems),
                'image' => $imageUrl,
                'event_image' => $heroImage
            ]);

            $updatedItems[] = [
                'id' => $cartItemKey,
                'type' => $product->name,
                'name' => $name,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $price * $quantity,
                'image' => $imageUrl,
                'event_image' => $heroImage
            ];
        }

        // Log complete state after processing
        Log::info('Cart item processing completed', [
            'auth_check' => auth()->check(),
            'updated_items_count' => count($updatedItems)
        ]);
    }

    /**
     * Updates the cache for cart totals
     * This is a helper method to centralize cache management
     */
    private function updateCartTotalsCache()
    {
        if (auth()->check()) {
            $userId = auth()->id();

            // Get the cart totals with a database query
            $cartItems = Cart::where('user_id', $userId)->get();
            $totalCount = $cartItems->sum('cquantity');
            $totalAmount = $cartItems->sum('ctotalprice');

            // Store in cache with a TTL of 30 minutes
            $cacheKey = 'cart_totals_' . $userId;
            Cache::put($cacheKey, [
                'count' => $totalCount,
                'total' => $totalAmount,
                'updated_at' => now()->timestamp
            ], now()->addMinutes(30));

            return [
                'count' => $totalCount,
                'total' => $totalAmount
            ];
        }

        return null;
    }

    /**
     * Get cart totals from cache or database
     *
     * @return array
     */
    private function getCartTotalsFromCacheOrDb()
    {
        if (auth()->check()) {
            $userId = auth()->id();
            $cacheKey = 'cart_totals_' . $userId;

            // Try to get from cache
            $cachedTotals = Cache::get($cacheKey);

            // Check if cache exists and is not expired (1 minute)
            if ($cachedTotals &&
                isset($cachedTotals['updated_at']) &&
                (now()->timestamp - $cachedTotals['updated_at']) < 60) {

                Log::info('Cart totals retrieved from cache', [
                    'user_id' => $userId,
                    'count' => $cachedTotals['count'],
                    'total' => $cachedTotals['total']
                ]);

                return [
                    'count' => $cachedTotals['count'],
                    'total' => $cachedTotals['total']
                ];
            }

            // If not in cache or expired, use model methods for better performance
            return [
                'count' => Cart::getUserCartCount($userId),
                'total' => Cart::getUserCartTotal($userId)
            ];
        }

        return null;
    }

    /**
     * Get cart totals for AJAX requests
     */
    public function getCartTotals(Request $request)
    {
        try {
            Log::info('Getting cart totals', [
                'auth_check' => auth()->check(),
                'user_id' => auth()->check() ? auth()->id() : 'guest'
            ]);

            if (auth()->check()) {
                // For authenticated users, try to get from cache first
                $totals = $this->getCartTotalsFromCacheOrDb();

                if ($totals) {
                    $subtotal = $totals['total'];
                    $totalCount = $totals['count'];
                } else {
                    // Fallback to direct database query
                    $cartItems = Cart::where('user_id', auth()->id())->get();
                    $subtotal = $cartItems->sum('ctotalprice');
                    $totalCount = $cartItems->sum('cquantity');
                }

                $itemCount = Cart::where('user_id', auth()->id())->count();

                // You can add discount logic here if needed
                $total = $subtotal;

                Log::info('Auth user cart totals calculated', [
                    'items_count' => $itemCount,
                    'total_tickets' => $totalCount,
                    'subtotal' => $subtotal,
                    'total' => $total
                ]);

                return response()->json([
                    'success' => true,
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'total_items' => $totalCount,
                    'item_count' => $itemCount,
                    'cart_empty' => ($itemCount == 0)
                ]);
            } else {
                // For non-authenticated users, calculate from session
                $cartItems = session()->get('cart_items', []);
                $subtotal = 0;
                $totalCount = 0;

                foreach ($cartItems as $item) {
                    $subtotal += (float)($item['total'] ?? 0);
                    $totalCount += (int)($item['quantity'] ?? 0);
                }

                // You can add discount logic here if needed
                $total = $subtotal;

                Log::info('Non-auth user cart totals calculated', [
                    'items_count' => count($cartItems),
                    'total_tickets' => $totalCount,
                    'subtotal' => $subtotal,
                    'total' => $total
                ]);

                return response()->json([
                    'success' => true,
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'total_items' => $totalCount,
                    'item_count' => count($cartItems),
                    'cart_empty' => (count($cartItems) == 0)
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
     * Update cart item quantity
     */
    public function updateItem(Request $request, $id)
    {
        // Validate request
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1|max:10' // Add maximum value for security
        ]);

        // Add debug logging
        Log::info('Updating cart item', [
            'auth_check' => auth()->check(),
            'user_id' => auth()->check() ? auth()->id() : 'guest',
            'item_id' => $id,
            'quantity' => $validatedData['quantity']
        ]);

        try {
            if (auth()->check()) {
                // For authenticated users, update database record
                Log::info('Updating cart for authenticated user');

                $cartItem = Cart::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

                Log::info('Found cart item to update', [
                    'cart_id' => $cartItem->id,
                    'old_quantity' => $cartItem->cquantity,
                    'new_quantity' => $validatedData['quantity']
                ]);

                $cartItem->cquantity = $validatedData['quantity'];
                $cartItem->ctotalprice = $cartItem->cprice * $validatedData['quantity'];
                $cartItem->save();

                // Clear any cache for this specific item
                $cacheKey = 'cart_item_' . auth()->id() . '_' . md5($cartItem->cname . $cartItem->eventname);
                Cache::forget($cacheKey);

                // Update totals in cache
                $totals = $this->updateCartTotalsCache();
                $totalCartCount = $totals['count'];

                Log::info('Cart updated for auth user', [
                    'total_cart_count' => $totalCartCount
                ]);

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
                Log::info('Updating cart for non-authenticated user');

                $cartItems = session()->get('cart_items', []);

                if (!isset($cartItems[$id])) {
                    Log::warning('Cart item not found in session', [
                        'item_id' => $id,
                        'available_keys' => array_keys($cartItems)
                    ]);
                    return $this->securityFailureResponse($request, 'Cart item not found');
                }

                $cartItems[$id]['quantity'] = $validatedData['quantity'];
                $cartItems[$id]['total'] = $cartItems[$id]['price'] * $validatedData['quantity'];
                session()->put('cart_items', $cartItems);

                // Get cart count for response
                $totalCartCount = 0;
                foreach ($cartItems as $item) {
                    $totalCartCount += (int)($item['quantity'] ?? 0);
                }

                Log::info('Cart updated for non-auth user', [
                    'total_cart_count' => $totalCartCount
                ]);

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
        // Skip CSRF token verification for GET requests
        if ($request->method() === 'DELETE' && $request->header('X-CSRF-TOKEN') !== csrf_token() && $request->input('_token') !== csrf_token()) {
            Log::warning('CSRF token mismatch on cart removal', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return $this->securityFailureResponse($request, 'Invalid security token', 403);
        }

        // Add debug logging
        Log::info('Removing cart item', [
            'auth_check' => auth()->check(),
            'user_id' => auth()->check() ? auth()->id() : 'guest',
            'item_id' => $id,
            'method' => $request->method()
        ]);

        try {
            if (auth()->check()) {
                // For authenticated users, remove from database
                Log::info('Removing cart item for authenticated user');

                $cartItem = Cart::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->first();

                if (!$cartItem) {
                    Log::warning('Cart item not found for authenticated user', [
                        'item_id' => $id,
                        'user_id' => auth()->id()
                    ]);

                    // For direct GET requests, always redirect to cart page
                    if ($request->method() === 'GET') {
                        return redirect()->route('cart')->with('error', 'Item not found in your cart');
                    }

                    return $this->securityFailureResponse($request, 'Cart item not found');
                }

                Log::info('Found cart item to remove', [
                    'cart_id' => $cartItem->id,
                    'name' => $cartItem->cname,
                    'event' => $cartItem->eventname,
                    'quantity' => $cartItem->cquantity
                ]);

                // Check if this is the last item in the cart
                $isLastItem = Cart::where('user_id', auth()->id())->count() === 1;

                // Store values before deleting for response
                $cartName = $cartItem->cname;
                $eventName = $cartItem->eventname;

                $cartItem->delete();

                // Update cache after deletion
                $totals = $this->updateCartTotalsCache();
                $totalCartCount = $totals['count'] ?? 0;
                $totalAmount = $totals['total'] ?? 0;

                // Clear any item-specific cache
                $itemCacheKey = 'cart_item_' . auth()->id() . '_' . md5($cartName . $eventName);
                Cache::forget($itemCacheKey);

                Log::info('Cart item removed for auth user', [
                    'total_cart_count' => $totalCartCount,
                    'total_amount' => $totalAmount,
                    'was_last_item' => $isLastItem
                ]);

                // For direct GET requests
                if ($request->method() === 'GET') {
                    if ($isLastItem) {
                        // Do a full redirect to the cart page to force a fresh load
                        return redirect('/cart');
                    } else {
                        return redirect()->route('cart')->with('success', 'Item removed from cart');
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'total_cart_items' => $totalCartCount,
                    'cart_empty' => ($totalCartCount == 0),
                    'subtotal' => $totalAmount,
                    'total' => $totalAmount
                ]);
            } else {
                // For non-authenticated users, remove from session
                Log::info('Removing cart item for non-authenticated user');

                $cartItems = session()->get('cart_items', []);

                if (!isset($cartItems[$id])) {
                    Log::warning('Cart item not found in session', [
                        'item_id' => $id,
                        'available_keys' => array_keys($cartItems)
                    ]);

                    // For direct GET requests, always redirect to cart page
                    if ($request->method() === 'GET') {
                        return redirect()->route('cart')->with('error', 'Item not found in your cart');
                    }

                    return $this->securityFailureResponse($request, 'Cart item not found');
                }

                Log::info('Found session cart item to remove', [
                    'cart_key' => $id,
                    'product' => $cartItems[$id]['product_name'] ?? 'Unknown',
                    'item' => $cartItems[$id]['item_name'] ?? 'Unknown'
                ]);

                // Check if this is the last item in the cart
                $isLastItem = count($cartItems) === 1;

                unset($cartItems[$id]);
                session()->put('cart_items', $cartItems);

                // Get updated cart count for response
                $totalCartCount = 0;
                $totalAmount = 0;
                foreach ($cartItems as $item) {
                    $totalCartCount += (int)($item['quantity'] ?? 0);
                    $totalAmount += (float)($item['total'] ?? 0);
                }

                Log::info('Cart item removed for non-auth user', [
                    'total_cart_count' => $totalCartCount,
                    'total_amount' => $totalAmount,
                    'was_last_item' => $isLastItem
                ]);

                // For direct GET requests
                if ($request->method() === 'GET') {
                    if ($isLastItem) {
                        // Do a full redirect to the cart page to force a fresh load
                        return redirect('/cart');
                    } else {
                        return redirect()->route('cart')->with('success', 'Item removed from cart');
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'total_cart_items' => $totalCartCount,
                    'cart_empty' => ($totalCartCount == 0),
                    'subtotal' => $totalAmount,
                    'total' => $totalAmount
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Cart remove error: ' . $e->getMessage(), [
                'exception' => $e,
                'id' => $id,
                'request' => $request->all()
            ]);

            // For direct GET requests, redirect to cart page with error message
            if ($request->method() === 'GET') {
                return redirect()->route('cart')->with('error', 'Failed to remove item from cart');
            }

            return $this->securityFailureResponse($request);
        }
    }

    /**
     * Handle security failure responses consistently with enhanced logging
     */
    private function securityFailureResponse(Request $request, $message = 'Invalid request', $status = 400)
    {
        // Log the security failure with IP address and user agent
        Log::warning('Security failure in cart operation', [
            'message' => $message,
            'status' => $status,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'auth_check' => auth()->check(),
            'user_id' => auth()->check() ? auth()->id() : 'guest'
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], $status);
        }

        // For non-AJAX requests, redirect with error message
        return redirect()->route('cart')->with('error', $message);
    }

    /**
     * Get total cart items count for current user or session
     * This is used for the cart count badge in header
     */
    public static function getCartCount()
    {
        return CartService::getCartCount();
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
            // Validate the reference format for security
            if (!preg_match('/^[A-Za-z0-9\-_]+$/', $paymentRef)) {
                Log::error('Invalid payment reference format: ' . $paymentRef);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment reference format'
                ], 400);
            }

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

            // Generate a success token for secure redirect
            $successToken = hash('sha256', $paymentRef . time() . uniqid());
            session(['success_token' => $successToken]);
            session(['success_token_expires' => now()->addMinutes(5)->timestamp]);

            // Clear the cart after successful payment
            if (auth()->check()) {
                Cart::where('user_id', auth()->id())->delete();

                // Clear any cart-related caches
                Cache::forget('cart_totals_' . auth()->id());
            } else {
                session()->forget('cart_items');
            }

            // Store the completed order in session for the success page
            session()->put('completed_order', [
                'ref' => $pendingOrder['ref'],
                'paymentRef' => $paymentRef,
                'email' => $pendingOrder['email'],
                'first_name' => $pendingOrder['first_name'],
                'last_name' => $pendingOrder['last_name'],
                'phone' => $pendingOrder['phone'],
                'amount' => $pendingOrder['amount'],
                'ticket_data' => $pendingOrder['ticket_data'],
                'completed_at' => now()->toDateTimeString()
            ]);

            // Clear the pending order
            session()->forget('pending_order');

            // For Ajax responses, return proper success URL with token
            $successUrl = route('home', [
                'payment_success' => true,
                'reference' => $paymentRef
            ]);

            Log::info('Payment processed successfully', [
                'payment_ref' => $paymentRef,
                'user_id' => auth()->check() ? auth()->id() : 'guest',
                'success_url' => $successUrl
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'payment_ref' => $paymentRef,
                'redirect_url' => $successUrl
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

    /**
     * Clear the cart after successful payment
     */
    private function clearCart()
    {
        CartService::clearCart();
    }

    public function forceCartClear(Request $request)
    {
        try {
            // Clear database cart for authenticated users
            if (auth()->check()) {
                Cart::where('user_id', auth()->id())->delete();
                Cache::forget('cart_totals_' . auth()->id());
                Cache::forget('cart_items_' . auth()->id());
                Cache::forget('cart_count_' . auth()->id());
            }
            
            // Clear session cart data
            Session::forget('cart_items');
            Session::forget('cart_totals');
            Session::forget('cart_count');
            Session::forget('cart');
            Session::save();
            
            return response()->json(['status' => 'success', 'message' => 'Cart cleared successfully']);
        } catch (\Exception $e) {
            \Log::error('Error clearing cart: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error clearing cart'], 500);
        }
    }
}
