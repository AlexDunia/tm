<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\mctlists;
use App\Mail\EmailTemplate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Stevebauman\Location\Facades\Location;
use Illuminate\Validation\ValidationException;
use App\Models\User as AppUser;
use Illuminate\Support\Facades\Password;

// $cartItemCount = Cart::count();

// // Return the view with the $cartItemCount variable
// return redirect('/')->with('cartItemCount', $cartItemCount);

class ListingController extends Controller
{
    //

    // public function index(){
    //     if(auth::id()) {
    //         $username = auth->user()->name;
    //         if($username =='duniaadmin'){
    //             return view('Adminpanel')
    //         }
    //     }
    //     $cartItemCount = Cart::count();
    //     $welcomeData = mctlists::all();

    //     return view('welcome', [
    //         'heading' => 'My laravel application',
    //         'welcome' => $welcomeData,
    //         'cartItemCount' => $cartItemCount,
    //     ]);
    // }
    // public function trypayment() {
    //     return view('trypay');
    // }

    public function contact() {
         return view('Contact');
    }

    public function searchnotfound(){
        return view("Snf");
    }

    public function notfound(){
        return view("404");
    }

    // public function viewone(mctlists $listonee){
    //     return view('Editpost', [
    //         'lexlist'=> $listonee
    //     ]);
    // }

    // public function contactsend(Request $request)
    // {
    //     $contactFields = $request->validate([
    //         'name'=>'required',
    //         'email' => ['required', 'email'],
    //         'phone' => 'required',
    //         'comment'=>'required',
    //     ]);

    //     Mail::send('Emailtemplate', ['token' => $token], function ($message) use ($request){
    //         $message->to($request->email);
    //         $message->subject('Reset password');
    //     });
    // }

    public function contactsend(Request $request)
    {
        // Validate your contact form fields
        $contactFields = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'phone' => 'required',
            'comment' => 'required',
        ]);

        // Specify the recipient email address
        $recipientEmail = 'thealexdunia@gmail.com';

        // Prepare the email content with form field values
        $emailContent = "Name: {$contactFields['name']}\n";
        $emailContent .= "Email: {$contactFields['email']}\n";
        $emailContent .= "Phone: {$contactFields['phone']}\n";
        $emailContent .= "Comment: {$contactFields['comment']}\n";

        Mail::raw($emailContent, function ($message) use ($recipientEmail) {
            // Set the recipient email address
            $message->to($recipientEmail);

            // Set the email subject
            $message->subject(' Tix demand Contact Form Submission');
        });

        // You can add further logic here, such as redirecting the user or displaying a success message.
        return redirect()->route('logg')->with('message', 'Password Reset Link Sent To Your Email! Click to Change Now.');
    }


    public function payform()
    {
        if (Auth::check()) {
            // Get all cart items for the authenticated user instead of just the latest one
            $cartItems = auth()->user()->relatewithcart()->get();

            // Calculate total price
            $totalPrice = $cartItems->sum('ctotalprice');

            // Get all ticket IDs from session for each cart item
            $allTicketIds = [];
            foreach ($cartItems as $item) {
                $ticketIds = session('ticket_ids_' . $item->id, []);
                if (empty($ticketIds)) {
                    // Generate IDs if not in session
                    $baseId = 'TIX-' . strtoupper(substr(md5($item->eventname . $item->cname), 0, 6));
                    for ($i = 1; $i <= $item->cquantity; $i++) {
                        $ticketIds[] = $baseId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                    }
                    session(['ticket_ids_' . $item->id => $ticketIds]);
                }
                $allTicketIds[$item->id] = $ticketIds;
            }

            return view('Checkout', [
                'cartItems' => $cartItems,
                'totalPrice' => $totalPrice,
                'ticketIds' => $allTicketIds
            ]);
        }

        // For non-authenticated users, check if we have multiple items in session
        if (session()->has('cart_items')) {
            $sessionCartItems = session()->get('cart_items', []);

            // Convert to collection of objects
            $cartItems = collect();
            $totalPrice = 0;
            $allTicketIds = [];

            foreach ($sessionCartItems as $item) {
                $cartObj = (object)$item;
                $cartItems->push($cartObj);
                $totalPrice += $cartObj->ctotalprice;

                // Generate ticket IDs for this item
                $ticketIds = [];
                $baseId = 'TIX-' . strtoupper(substr(md5($cartObj->eventname . $cartObj->cname), 0, 6));
                for ($i = 1; $i <= $cartObj->cquantity; $i++) {
                    $ticketIds[] = $baseId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                }
                $allTicketIds[$cartObj->id] = $ticketIds;
            }

            return view('Checkout', [
                'cartItems' => $cartItems,
                'totalPrice' => $totalPrice,
                'ticketIds' => $allTicketIds
            ]);
        }

        // Fallback to legacy single item session
        $tname = session()->get('tname');
        $timage = session()->get('timage');
        $tprice = session()->get('tprice');
        $totalprice = session()->get('totalprice');
        $tquantity = session()->get('tquantity');
        $eventname = session()->get('eventname');

        // Generate ticket IDs for non-authenticated users
        $ticketIds = [];
        $baseId = 'TIX-' . strtoupper(substr(md5($eventname . $tname), 0, 6));
        for ($i = 1; $i <= $tquantity; $i++) {
            $ticketIds[] = $baseId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
        }
        session(['temp_ticket_ids' => $ticketIds]);

        return view('Checkout', compact('tname', 'tprice', 'totalprice', 'tquantity', 'eventname', 'timage', 'ticketIds'));
    }


    public function index()
    {
        // Fetch real data from the database
        $events = mctlists::latest()->paginate(10);

        // Log the number of events for debugging
        \Log::info('Home page loaded with ' . $events->count() . ' events');

        // If no events, log a warning
        if ($events->isEmpty()) {
            \Log::warning('No events found in the database');
        } else {
            // Log the first event's details
            \Log::info('First event: ' . json_encode($events->first()->toArray()));
        }

        return view('welcome', [
            'heading' => 'My Laravel Application',
            'welcome' => $events,
        ]);
    }


    public function search(Request $request) {
        // Retrieve the user input and sanitize it using trim()
        $si = trim($request->input('name'));

        // Ensure $si is not empty before proceeding
        if (!empty($si)) {
            // Use Laravel's query builder to construct a secure query
            $products = mctlists::where('name', 'like', '%' . $si . '%')
                ->orWhere('description', 'like', '%' . $si . '%')
                ->latest()->simplePaginate(5);

            // Check if the query result is empty

            if ($products->isEmpty()) {
                $si = trim($request->input('name'));
                // Return a different view for no results found
                return view('Snf', compact('si'));
            }

            return view('Search', compact('products', 'si'));
        } else {
            // Handle the case where the search input is empty
            // We need to customise a page where they will be told nothing is found here.
            return redirect()->back()->with('error', 'Please enter a valid search term.');
        }
    }

    // public function filterByCategory(string $category)
    // {
    //     $posts = mctlists::where('description', $category)->get();

    //     return view('posts.index', compact('posts'));
    // }


    // public function showByCategory($category)
    // {
    //     $posts = mctlists::where('description', $category)->get();

    //     return view('Filter', compact('posts', 'category'));
    // }

      public function showByCategory($category){
        $sr = mctlists::where('description', $category)->latest()->simplePaginate(5);
        if($sr->isEmpty()){
            return view('Nofilter');
        }
        else{
            return view('Filter', [
                // 'heading' => 'My Laravel Application',
                // 'welcome' => mctlists::latest()->get(),
                'posts' => $sr,
                'category' => $category,
            ]);
        }
    }

      public function forgotpassword(){
        return view('Forgotpassword');
      }

      public function forgotpasswordpost(Request $request){
        // Validate the email address
        $request->validate([
            'email' => "required|email|exists:users",
        ]);

        // Delete any existing tokens for this email to prevent token buildup
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Generate a random token securely
        $token = Str::random(64);

        // Validate the generated token
        if (!is_string($token) || !preg_match('/^[A-Za-z0-9]+$/', $token) || strlen($token) !== 64) {
            // Invalid token generated, handle the error (e.g., log it)
            \Log::error('Invalid token generated for password reset');
            throw ValidationException::withMessages([
                'token' => 'Error generating security token. Please try again.',
            ]);
        }

        // Set token expiration (1 hour from now)
        $expires = now()->addHour();

        // Now add data into the password_resets table
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        try {
            // Send password reset email
            Mail::send('Emailtemplate', ['token' => $token], function ($message) use ($request){
                $message->to($request->email);
                $message->subject('Tixdemand Password Reset Request');
            });

            return redirect()->route('logg')->with('message', 'Password reset link sent to your email. Please check your inbox.');
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Could not send password reset link. Please try again later.']);
        }
    }

    public function resetpassword($token){
        // Validate token format for basic security
        if (!preg_match('/^[A-Za-z0-9]{64}$/', $token)) {
            return redirect()->route('logg')->with('error', 'Invalid password reset token.');
        }

        // Check if token exists and is not expired (1 hour validity)
        $resetRecord = DB::table('password_resets')
            ->where('token', $token)
            ->where('created_at', '>', now()->subHour())
            ->first();

        if (!$resetRecord) {
            return redirect()->route('fp')->with('error', 'Password reset link has expired or is invalid. Please request a new one.');
        }

        return view('Resetpassword', compact('token'));
    }

    public function resetpasswordpost(Request $request){
        // Validate form inputs with stronger password requirements
        $request->validate([
            "email" => "required|email|exists:users",
            "password" => [
                "required",
                "string",
                "min:8",
                "confirmed",
                "regex:/[a-z]/", // at least one lowercase letter
                "regex:/[A-Z]/", // at least one uppercase letter
                "regex:/[0-9]/", // at least one number
                "regex:/[@$!%*#?&]/" // at least one special character
            ],
            "password_confirmation" => "required",
            "token" => "required|string|size:64"
        ], [
            'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        // Find the password reset record
        $resetRecord = DB::table('password_resets')
            ->where([
                "email" => $request->email,
                "token" => $request->token,
            ])
            ->first();

        // Check if reset record exists and is valid
        if (!$resetRecord) {
            return redirect()->route('fp')
                ->with('error', 'Invalid password reset link. Please request a new password reset link.');
        }

        // Check if the token is expired (1 hour validity)
        if (Carbon::parse($resetRecord->created_at)->addHour()->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return redirect()->route('fp')
                ->with('error', 'Password reset link has expired. Please request a new one.');
        }

        // Update the user's password
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->route('logg')
                ->with('error', 'Could not find your account. Please try again.');
        }

        // Update password with proper hashing
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete all password reset tokens for this user
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Log a password change event for security
        \Log::info('Password reset successful for user: ' . $user->email);

        return redirect()->route('logg')
            ->with('message', 'Your password has been reset successfully. Please log in with your new password.');
    }

    // public function search(Request $request) {
    //     // Retrieve the user input and sanitize it using trim()
    //     $si = trim($request->input('name'));
    //     // Ensure $si is not empty before proceeding
    //     if (!empty($si)) {
    //         // Use Laravel's query builder to construct a secure query
    //         $products = mctlists::where('name', 'like', '%' . $si . '%')
    //             ->get();

    //         return view('search', compact('products'));
    //     } else {
    //         // Handle the case where the search input is empty
    //         // We need to customise a page where they will be told nothing is found here.
    //         $si = trim($request->input('name'));
    //         return view('Snf', compact('si'));
    //     }
    // }



    // public function payform(){
    //     if (Auth::check()) {

    //         $cartItems = auth()->user()->relatewithcart()->get();

    //         // Check if there's more than one item in the cart
    //         if ($cartItems->count() > 1) {
    //             // Delete each cart item
    //             foreach ($cartItems as $cartItem) {
    //                 $cartItem->delete();
    //             }
    //         }

    //         return view('Checkout', [
    //             'mycart' => $cartItems,
    //         ]);
    //     }

    //     // Rest of your code...
    //     $tname = session()->get('tname');
    //     $tprice = session()->get('tprice');
    //     $totalprice = session()->get('totalprice');
    //     return view('Checkout', compact('tname', 'tprice', 'totalprice'));
    // }






    // public function show($id) {
    //     return view('listone', [
    //         'listonee' => mctlists::find($id)
    //     ]);
    // }

    // public function show($name) {
    //     return view('listone', [
    //         'listonee' => mctlists::where('name', $name)->first()
    //     ]);
    // }

    public function show($name) {
        $listonee = mctlists::where('name', $name)->first();

        if (!empty($listonee->status)) {
            // If the 'description' field is not empty, return a different view.
            return view('expiredlistone', [
                'listonee' => $listonee
            ]);
        } else {
            // Check if the date contains @ symbol and format it properly
            if (isset($listonee->date) && strpos($listonee->date, '@') !== false) {
                try {
                    // Try to parse the date using Carbon
                    $formattedDate = \Carbon\Carbon::parse(str_replace('@', ' ', $listonee->date))->format('Y-m-d H:i:s');
                    $listonee->date = $formattedDate;
                } catch (\Exception $e) {
                    // If parsing fails, log the error but continue
                    \Illuminate\Support\Facades\Log::error('Date parsing error: ' . $e->getMessage());
                }
            }

            // If the 'description' field is empty, return the original view.
            return view('listone', [
                'listonee' => $listonee
            ]);
        }
    }


    // The next controller shows the cart page.
    // After you have done authentication,. its going to show according to users email.
    public function cartpage(){
        // Add debug log to track cart page access
        \Illuminate\Support\Facades\Log::info('CART DEBUG - Cart Page Accessed');

        if (Auth::check()) {
            // Get cart items for authenticated users
            $carts = Cart::where('user_id', auth()->id())->get();

            // Enhanced logging for cart items
            \Illuminate\Support\Facades\Log::info('CART DEBUG - Authenticated User Cart Items:', [
                'user_id' => auth()->id(),
                'cart_count' => $carts->count(),
                'total_quantity' => $carts->sum('cquantity'),
                'items' => $carts->map(function($item) {
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
        } else {
            // For non-authenticated users, check if we have session data
            if (session()->has('cart_items')) {
                // Get items from the new cart_items session array
                $cartItems = session()->get('cart_items', []);

                // Convert to collection of objects
                $carts = collect();
                foreach ($cartItems as $item) {
                    $cartObj = (object)$item;
                    $carts->push($cartObj);
                }

                // Enhanced logging for session cart items
                \Illuminate\Support\Facades\Log::info('CART DEBUG - Session Cart Items:', [
                    'count' => count($cartItems),
                    'total_quantity' => collect($cartItems)->sum('cquantity'),
                    'items' => $cartItems
                ]);
            } else if (session()->has('tname')) {
                // Legacy fallback for compatibility
                // Create a temporary cart item to display
                $tempCart = new \stdClass();
                $tempCart->id = 'session-item';
                $tempCart->cname = session()->get('eventname');
                $tempCart->eventname = session()->get('tname');
                $tempCart->cprice = session()->get('tprice');
                $tempCart->cquantity = session()->get('tquantity');
                $tempCart->ctotalprice = session()->get('totalprice');
                $tempCart->cdescription = session()->get('timage');

                $carts = collect([$tempCart]);

                // Log the legacy cart
                \Illuminate\Support\Facades\Log::info('CART DEBUG - Legacy Session Cart:', [
                    'name' => $tempCart->cname,
                    'event' => $tempCart->eventname,
                    'quantity' => $tempCart->cquantity,
                    'price' => $tempCart->cprice
                ]);
            } else {
                $carts = collect([]);
                \Illuminate\Support\Facades\Log::info('CART DEBUG - Empty Cart');
            }
        }

        return view('Cartuser', [
            'mycart' => $carts,
        ]);
    }


    // Delete
    public function delete($id) {
        // Log the delete request for debugging
        \Illuminate\Support\Facades\Log::info('CART DEBUG - Delete request:', [
            'id' => $id,
            'authenticated' => Auth::check(),
            'request_data' => request()->all()
        ]);

        if (!$id) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Invalid item ID.'], 400);
            }

            session()->flash('error', 'Invalid item ID.');
            return redirect()->back();
        }

        // Check for JSON data
        $isJsonRequest = request()->expectsJson() || request()->header('Content-Type') === 'application/json';
        $ticketType = null;

        if ($isJsonRequest && request()->json()->has('ticket_type')) {
            $ticketType = request()->json()->get('ticket_type');
        } else {
            // Extract ticket_type from regular POST request
            $ticketType = request()->input('ticket_type');
        }

        // Log the extracted ticket type
        \Illuminate\Support\Facades\Log::info('CART DEBUG - Delete parameters:', [
            'id' => $id,
            'ticket_type' => $ticketType,
            'method' => request()->method(),
            'is_json' => $isJsonRequest,
            'all_inputs' => request()->all()
        ]);

        // First check if user is authenticated
        if (Auth::check()) {
            // For authenticated users, delete from database
            $cdelete = Cart::find($id);

            // Check if item exists before attempting to delete
            if ($cdelete) {
                // Log what's being deleted
                \Illuminate\Support\Facades\Log::info('CART DEBUG - Deleting item:', [
                    'item_id' => $cdelete->id,
                    'name' => $cdelete->cname,
                    'event' => $cdelete->eventname,
                    'quantity' => $cdelete->cquantity
                ]);

                $cdelete->delete();

                if (request()->ajax()) {
                    return response()->json(['success' => true, 'message' => 'Item successfully removed from cart.']);
                }

                session()->flash('success', 'Item successfully removed from cart.');
            } else {
                // Log the error for debugging
                \Illuminate\Support\Facades\Log::error('CART DEBUG - Delete error: Item not found', [
                    'id' => $id
                ]);

                if (request()->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Could not find the item to remove.'], 404);
                }

                session()->flash('error', 'Could not find the item to remove.');
            }
        } else {
            // For non-authenticated users, delete from session
            if (session()->has('cart_items')) {
                $cartItems = session()->get('cart_items', []);
                $originalCount = count($cartItems);

                // Log session cart before deletion
                \Illuminate\Support\Facades\Log::info('CART DEBUG - Session cart before deletion:', [
                    'count' => count($cartItems),
                    'items' => $cartItems,
                    'ticket_type_to_remove' => $ticketType,
                    'id_to_remove' => $id
                ]);

                $found = false;
                $itemsToKeep = [];

                // ALWAYS prioritize removing by ticket_type if it's provided
                if ($ticketType) {
                    foreach ($cartItems as $item) {
                        // Skip items matching the ticket type (don't keep them)
                        if (!isset($item['cname']) || trim($item['cname']) != trim($ticketType)) {
                            $itemsToKeep[] = $item;
                        } else {
                            $found = true;
                            \Illuminate\Support\Facades\Log::info('CART DEBUG - Found item to remove by ticket type:', [
                                'item' => $item
                            ]);
                        }
                    }
                } else {
                    // No ticket_type provided, try to find by ID
                    foreach ($cartItems as $key => $item) {
                        if (!isset($item['id']) || $item['id'] != $id) {
                            $itemsToKeep[] = $item;
                        } else {
                            $found = true;
                            \Illuminate\Support\Facades\Log::info('CART DEBUG - Found item to remove by ID:', [
                                'item' => $item
                            ]);
                        }
                    }
                }

                // If we found and removed items
                if ($found) {
                    // Re-index the array and update session
                    session()->put('cart_items', $itemsToKeep);

                    // Log the updated cart
                    \Illuminate\Support\Facades\Log::info('CART DEBUG - Session cart after deletion:', [
                        'items_removed' => $originalCount - count($itemsToKeep),
                        'count_remaining' => count($itemsToKeep),
                        'removed_by' => $ticketType ? 'ticket_type' : 'id'
                    ]);

                    if (request()->ajax()) {
                        return response()->json(['success' => true, 'message' => 'Item successfully removed from cart.']);
                    }

                    session()->flash('success', 'Item successfully removed from cart.');
                } else {
                    // Log the error
                    \Illuminate\Support\Facades\Log::error('CART DEBUG - Delete error: Session item not found', [
                        'requested_id' => $id,
                        'ticket_type' => $ticketType,
                        'available_items' => collect($cartItems)->map(function ($item) {
                            return [
                                'id' => $item['id'] ?? 'missing',
                                'cname' => $item['cname'] ?? 'missing'
                            ];
                        })->toArray()
                    ]);

                    if (request()->ajax()) {
                        return response()->json(['success' => false, 'message' => 'Could not find the item to remove.'], 404);
                    }

                    session()->flash('error', 'Could not find the item to remove.');
                }
            } elseif (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'No cart items in session.'], 404);
            } else {
                session()->flash('error', 'Your cart is empty.');
            }
        }

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Item removed successfully.']);
        }

        return redirect()->back();
    }

    // Update cart quantity
    public function updateCart(Request $request, $id = null){
        // Check if the request is a JSON request
        $isJsonRequest = $request->expectsJson() || $request->header('Content-Type') === 'application/json';

        // Handle JSON request data
        if ($isJsonRequest) {
            $data = $request->json()->all();
            $itemId = $id ?? $data['item_id'] ?? null;
            $quantity = $data['quantity'] ?? null;
            $ticketType = $data['ticket_type'] ?? null; // For filtering by ticket type
        } else {
            // Handle form POST data
            $itemId = $id ?? $request->input('item_id');
            $quantity = $request->input('quantity');
            $ticketType = $request->input('ticket_type');
        }

        // Validate the input
        if (!$itemId || !is_numeric($quantity) || $quantity < 1) {
            if ($isJsonRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid input data.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Invalid input data.');
        }

        // For authenticated users, update in database
        if (Auth::check()) {
            $cartItem = Cart::find($itemId);

            if (!$cartItem) {
                if ($isJsonRequest) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cart item not found.'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Cart item not found.');
            }

            // Update quantity
            $cartItem->cquantity = $quantity;

            // Recalculate total price
            $cartItem->ctotalprice = $cartItem->cprice * $quantity;

            // Generate unique ticket IDs and store them in session
            $ticketIds = [];
            $baseId = 'TIX-' . strtoupper(substr(md5($cartItem->eventname . $cartItem->cname), 0, 6));
            for ($i = 1; $i <= $quantity; $i++) {
                $ticketIds[] = $baseId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            }
            session(['ticket_ids_' . $cartItem->id => $ticketIds]);

            $cartItem->save();

            if ($isJsonRequest) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully',
                    'item' => $cartItem,
                    'ticket_ids' => $ticketIds
                ]);
            }
        }
        // For non-authenticated users, update in session
        else if (session()->has('cart_items')) {
            $cartItems = session()->get('cart_items', []);
            $updated = false;
            $updatedItem = null;

            // If we have a ticket type, we need to update all items of that type
            if ($ticketType) {
                // Calculate how much to add/remove per item
                $currentTotalQty = 0;
                $itemsOfType = [];

                // First, gather all items of this ticket type
                foreach ($cartItems as $key => $item) {
                    if (trim($item['cname']) == trim($ticketType)) {
                        $currentTotalQty += $item['cquantity'];
                        $itemsOfType[] = $key;
                    }
                }

                // If we have items of this type and the target quantity differs from current
                if (count($itemsOfType) > 0 && $currentTotalQty != $quantity) {
                    // For simplicity, let's update the first item only
                    $mainKey = $itemsOfType[0];
                    $cartItems[$mainKey]['cquantity'] = $quantity;
                    $cartItems[$mainKey]['ctotalprice'] = $cartItems[$mainKey]['cprice'] * $quantity;
                    $updated = true;
                    $updatedItem = $cartItems[$mainKey];

                    // Remove other items of same type
                    foreach ($itemsOfType as $k) {
                        if ($k != $mainKey) {
                            unset($cartItems[$k]);
                        }
                    }

                    // Re-index the array
                    $cartItems = array_values($cartItems);
                }
            } else {
                // Classic single-item update by ID
                foreach ($cartItems as $key => $item) {
                    if ($item['id'] == $itemId) {
                        $cartItems[$key]['cquantity'] = $quantity;
                        $cartItems[$key]['ctotalprice'] = $cartItems[$key]['cprice'] * $quantity;
                        $updated = true;
                        $updatedItem = $cartItems[$key];

                        // Generate ticket IDs
                        $ticketIds = [];
                        $baseId = 'TIX-' . strtoupper(substr(md5($item['eventname'] . $item['cname']), 0, 6));
                        for ($i = 1; $i <= $quantity; $i++) {
                            $ticketIds[] = $baseId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                        }
                        session(['ticket_ids_' . $itemId => $ticketIds]);
                        break;
                    }
                }
            }

            if ($updated) {
                session()->put('cart_items', $cartItems);

                if ($isJsonRequest) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Cart updated successfully',
                        'item' => (object)$updatedItem,
                        'ticket_ids' => $ticketIds ?? []
                    ]);
                }
            } else if ($isJsonRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found in session.'
                ], 404);
            }
        } else if ($isJsonRequest) {
            return response()->json([
                'success' => false,
                'message' => 'No cart items in session.'
            ], 404);
        }

        if ($isJsonRequest) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }
}
