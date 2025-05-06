<?php
// use auth;
use App\Models\Cart;
use App\Models\Admin;
use App\Models\mctlists;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PaymentController;
// use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\MainadminController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Myorders;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TicketType;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\SessionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome', [
//         'heading' => 'My laravel application',
//         'welcome' => mctlists::all()
//     ]);
// });

// Route::get('/events/{id}', function($id) {
//     return view('listone', [
//         'listonee' => mctlists::find($id)
//     ]);
// });


Route::get('/', [ListingController::class, 'index'])->name('home');

Route::get('/payment', [PaymentController::class, 'index'] );
Route::get('/contact', [ListingController::class, 'contact'] );
Route::get('/search', [ListingController::class, 'search'] );
Route::get('/searchnotfound', [ListingController::class, 'searchnotfound'] );
Route::get('/verifypayment/{reference}', [PaymentController::class, 'verify'])->name('payment.verify');
Route::get('/tryverifypayment/{reference}', [PaymentController::class, 'tryverify'] );

// View Admin Panel - with admin-only access
Route::get('/dunia', [AdminController::class, 'index'])
    ->middleware(['auth', 'admin.only']);

Route::middleware(['web'])->group(function () {
    // Define your routes here
    // USER REGISTRATION
    Route::get('/signup', function(){
        return view('Signup');
    })->name('signup');

    // This stores the user. and note that admin is user here.
    Route::post('/createnewadmin', [AdminController::class, 'storeuser'])->name('register');

    // Add other routes as needed
});

// Password Reset Routes
Route::get('/forgot-password', function () {
    return view('forgotpassword');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('resetpassword', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
                ? redirect()->route('logg')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// With this, auth and non auth users can send emails
Route::post('/formsent', [ListingController::class, 'contactsend']);

// This is the form that gives ability to make posts.
Route::get('/createpost', [AdminController::class, 'adminform'])->middleware('auth');

// This gives the ability to create events (for both admins and regular users)
Route::post('/creationsuccess', [AdminController::class, 'store'])->middleware('secure.upload');

// Users can now Login
Route::post('/authenticated', [AdminController::class, 'authenticate'])
    ->name('login')
    ->middleware('enhanced.throttle:5,1');

// Users can now Log out
// Route::post('/logout', [AdminController::class, 'disauthenticate']);
Route::match(['get', 'post'], '/logout', [AdminController::class, 'disauthenticate'])->name('logout');

// create ghon ghon
// Route::get('/alexadmin', function () {
//     return view('Admin');
// });

// Cart routes (optimized)
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/addtocart', [CartController::class, 'addToCart'])
    ->name('addtocart')
    ->middleware(['throttle:20,1']); // Limit add to cart attempts to 20 per minute

Route::middleware(['throttle:60,1'])->group(function () {
    Route::patch('/cart/update/{id}', [CartController::class, 'updateItem'])
        ->name('cart.update')
        ->middleware('auth', 'resource.auth:cart');

    // Allow both GET and DELETE methods for cart removal without authentication
    Route::match(['get', 'delete'], '/cart/remove/{id}', [CartController::class, 'removeItem'])
        ->name('cart.remove');

    Route::get('/cart/totals', [CartController::class, 'getCartTotals'])->name('cart.totals');
});

Route::get('/trypayment', [ListingController::class, 'trypayment'] );
Route::post('/tryverify/{reference}', [ListingController::class, 'tryverify'] );

// Cart view.
Route::get('/success', [PaymentController::class, 'success'])
    ->name('success')
    ->middleware('payment.verified');

Route::post('/verify-reference', [PaymentController::class, 'verifyReference'])->name('verify.reference');

Route::get('/notfound', [ListingController::class, 'notfound'] );
Route::get('/forgotpassword', [ListingController::class, 'forgotpassword'])->name('fp');
Route::post('/forgotpasswordpost', [ListingController::class, 'forgotpasswordpost'])->name('fpp');
Route::get('/resetpassword/{token}', [ListingController::class, 'resetpassword'])->name('rp');
Route::post('/resetpasswordpost', [ListingController::class, 'resetpasswordpost'])->name('rpp');

Route::get('/deviceinfo', function(){
    return view('logindevice');
});
// The forgot password post willl send a mail, and this mail will have a link

Route::get('/login', function(){
    return view('Login');
})->name('logg');

Route::get('/cartitem', function(){
    return view('checkout');
});

// We need to create a new route to host the filter.
// Route::get('/category/{category}', 'ListingController@showByCategory')->name('Filter');
Route::get('/category/{category}', [ListingController::class, 'showByCategory'] );
Route::get('/noresults/{category}', [ListingController::class, 'showByCategory'] );
// Route::get('/c', function(){
//     return view('checkout');
// });

// Route::get('/music', [ListingController::class, 'filterByCategory']);
// Route::get('/movies', 'ListingController@index');

// Make the create admin button work





// delete functionality.
Route::get('/delete/{id}', [ListingController::class, 'delete']);
Route::post('/delete/{id}', [ListingController::class, 'delete'])->middleware('web');

// View create
Route::POST('/createticket', function (Request $request) {
    // Log the entire request for debugging
    \Illuminate\Support\Facades\Log::info('Create Ticket Request', [
        'all' => $request->all(),
        'has_ticket_names' => $request->has('ticket_name'),
        'ticket_names' => $request->input('ticket_name'),
        'ticket_prices' => $request->input('ticket_price'),
        'form_data' => $request->except('_token')
    ]);

    // name description location date
    $clientevent = $request->validate([
        'name'=>'required',
        'description'=>'required',
        'location'=>'required',
        'date'=>'required',
        'image' => 'nullable|url',
        'heroimage' => 'nullable|url',
        'herolink' => 'nullable',
        'category' => 'nullable',
    ]);

    try {
        // Create the event
        $event = mctlists::create($clientevent);

        \Illuminate\Support\Facades\Log::info('Created event with ID: ' . $event->id);

        // Check if we should create ticket types
        if ($request->has('ticket_name') && is_array($request->ticket_name)) {
            $ticketNames = $request->ticket_name;
            $ticketPrices = $request->ticket_price;
            $ticketDescriptions = $request->ticket_description;
            $ticketCapacities = $request->ticket_capacity;

            \Illuminate\Support\Facades\Log::info('Creating ' . count($ticketNames) . ' ticket types', [
                'names' => $ticketNames,
                'prices' => $ticketPrices,
                'descriptions' => $ticketDescriptions,
                'capacities' => $ticketCapacities
            ]);

            foreach ($ticketNames as $index => $name) {
                if (!empty($name) && isset($ticketPrices[$index])) {
                    try {
                        $ticket = TicketType::create([
                            'mctlists_id' => $event->id,
                            'name' => $name,
                            'price' => $ticketPrices[$index],
                            'description' => $ticketDescriptions[$index] ?? '',
                            'capacity' => !empty($ticketCapacities[$index]) ? $ticketCapacities[$index] : null,
                            'sales_start' => now(),
                            'sales_end' => now()->addMonths(2),
                            'is_active' => true,
                            'sold' => 0
                        ]);

                        \Illuminate\Support\Facades\Log::info("Created ticket type: {$name} with ID: {$ticket->id}");
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Error creating ticket type: {$name}", [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                }
            }
        } else {
            // Still create at least one default ticket if no ticket types were specified
            try {
                $ticket = TicketType::create([
                    'mctlists_id' => $event->id,
                    'name' => 'Regular Ticket',
                    'price' => 5000,
                    'description' => 'Standard event access',
                    'capacity' => null,
                    'sales_start' => now(),
                    'sales_end' => now()->addMonths(2),
                    'is_active' => true,
                    'sold' => 0
                ]);

                \Illuminate\Support\Facades\Log::info("Created default ticket type with ID: {$ticket->id}");
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error creating default ticket type", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        // Load the event with ticket types to verify they were created
        $loadedEvent = mctlists::with('ticketTypes')->find($event->id);
        \Illuminate\Support\Facades\Log::info("Ticket types created for event {$event->id}: " . $loadedEvent->ticketTypes->count());

        return redirect('/event/' . $event->id . '/ticket');
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error("Error in createticket route", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()->with('error', 'There was an error creating your event: ' . $e->getMessage());
    }
});



// I belive it would be easier to work on the edit now that i know where the error was actually coming from.
Route::get('/event/{listonee}/ticket', [ListingController::class, 'viewone']);

Route::get('/events/{name}', [ListingController::class, 'show'] );
// Route::get('/addtocart/{id}', [ListingController::class, 'cart'] );


// so what exactly do we want to do first?
// Click to view something with the code.
//
// Auth::routes();

// Remove home route - use root route instead
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Clear cart for non-authenticated users
Route::get('/clear-cart', function() {
    if (!Auth::check()) {
        session()->forget('cart_items');
        session()->forget('tname');
        session()->forget('tprice');
        session()->forget('tquantity');
        session()->forget('eventname');
        session()->forget('totalprice');
        session()->forget('timage');
    } else {
        // Also clear cart for authenticated users
        Cart::where('user_id', auth()->id())->delete();
    }
    return redirect()->route('cart')->with('message', 'Cart cleared successfully');
});

// Add new cart payment processing routes
Route::post('/prepare-payment', [CartController::class, 'preparePayment'])->name('prepare.payment');
Route::get('/process-payment/{reference}', [CartController::class, 'processSuccessfulPayment'])->name('process.payment');

// Test cart routes for debugging
Route::get('/test-add-to-cart', [App\Http\Controllers\TestCartController::class, 'testAddToCart']);
Route::get('/test-view-cart', [App\Http\Controllers\TestCartController::class, 'testViewCart']);
Route::get('/debug-cart-creation', [App\Http\Controllers\TestCartController::class, 'debugCartCreation']);

// Test route to create event with ticket types
Route::get('/test-ticket-types', function() {
    // Create a test event
    $event = \App\Models\mctlists::create([
        'name' => 'Test Event ' . time(),
        'description' => 'This is a test event',
        'location' => 'Test Location',
        'date' => now()->addWeek(),
        'image' => 'https://res.cloudinary.com/demo/image/upload/v1629401603/samples/landscapes/architecture-signs.jpg',
        'heroimage' => 'https://res.cloudinary.com/demo/image/upload/v1629401603/samples/landscapes/nature-mountains.jpg',
        'herolink' => 'https://example.com/event',
        'category' => 'Music'
    ]);

    // Create ticket types
    $regularTicket = \App\Models\TicketType::create([
        'mctlists_id' => $event->id,
        'name' => 'Regular Ticket',
        'price' => 5000,
        'description' => 'Standard event access',
        'capacity' => 100,
        'sales_start' => now(),
        'sales_end' => now()->addMonths(1),
        'is_active' => true,
        'sold' => 0
    ]);

    $vipTicket = \App\Models\TicketType::create([
        'mctlists_id' => $event->id,
        'name' => 'VIP Ticket',
        'price' => 15000,
        'description' => 'Premium seating with complimentary drinks',
        'capacity' => 50,
        'sales_start' => now(),
        'sales_end' => now()->addMonths(1),
        'is_active' => true,
        'sold' => 0
    ]);

    // Load the event with ticket types to verify they're connected
    $loadedEvent = \App\Models\mctlists::with('ticketTypes')->find($event->id);

    // Output debug information
    return response()->json([
        'event' => $event,
        'ticket_types_count' => $loadedEvent->ticketTypes->count(),
        'ticket_types' => $loadedEvent->ticketTypes,
        'regular_ticket' => $regularTicket,
        'vip_ticket' => $vipTicket,
        'view_url' => url('/event/' . $event->id . '/ticket')
    ]);
});

// CSRF token refresh endpoint for AJAX requests
Route::get('/csrf-refresh', function() {
    return response()->json([
        'token' => csrf_token()
    ]);
});

// Note: API routes for transactions have been moved to the api.php routes file

// Recommendation routes
Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations');
Route::get('/similar-events/{eventId}', [RecommendationController::class, 'similarEvents'])->name('similar-events');

// Admin recommendation routes
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/admin/update-transaction-ids', [RecommendationController::class, 'updateTransactionIds'])->name('admin.update-transaction-ids');
    Route::get('/admin/trending-events', [RecommendationController::class, 'trending'])->name('admin.trending-events');
    Route::get('/admin/payment-security-logs', [AdminController::class, 'paymentSecurityLogs'])->name('admin.payment-security-logs');
});

// Test routes - ONLY for non-production environments
if (app()->environment('local', 'development', 'testing')) {
    Route::get('/test-success-page', [PaymentController::class, 'testSuccessPage'])->name('test.success-page');
}

// Session Management Routes
Route::post('/session/refresh', [SessionController::class, 'refresh'])
    ->middleware(['web', 'auth'])
    ->name('session.refresh');

Route::post('/session/silent-auth', [SessionController::class, 'silentAuth'])
    ->middleware(['web'])
    ->name('session.silent_auth');

Route::get('/session/expired', [SessionController::class, 'expired'])
    ->middleware(['web'])
    ->name('session.expired');
