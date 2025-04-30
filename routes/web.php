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
Route::get('/verifypayment/{reference}', [PaymentController::class, 'verify'] );
Route::get('/tryverifypayment/{reference}', [PaymentController::class, 'tryverify'] );

// View Admin Panel
Route::get('/dunia', [AdminController::class, 'index']);

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

// This is the form that gives admin ability to make posts.
Route::get('/createpost', [AdminController::class, 'adminform']);

// This gives the admin the ability to edit posts.
Route::post('/creationsuccess', [AdminController::class, 'store']);

// Users can now Login
Route::post('/authenticated', [AdminController::class, 'authenticate'])->name('login');

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
Route::post('/addtocart', [CartController::class, 'addToCart'])->name('addtocart');
Route::patch('/cart/update/{id}', [CartController::class, 'updateItem'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::get('/cart/totals', [CartController::class, 'getCartTotals'])->name('cart.totals');

Route::get('/trypayment', [ListingController::class, 'trypayment'] );
Route::post('/tryverify/{reference}', [ListingController::class, 'tryverify'] );

// Cart view.
Route::get('/success', [PaymentController::class, 'success'])->name('success')->withoutMiddleware(['auth']);

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
    // dd($request->all());

       // Three basic things you must proritize in this space.
    // Request a validation.
    // Create directly with the model.
    // Return a redirect
    // return view('Admin');

    // in my laravel appliction, I want to click on a button that will add aleady existing products that is
    // already in my seeder into a new database.

    // name description location date
    $clientevent = $request->validate([
        'name'=>'required',
        'description'=>'required',
        'location'=>'required',
        'date'=>'required',
    ]);

    mctlists::create($clientevent);

    return redirect('/');

});



// I belive it would be easier to work on the edit now that i know where the error was actually coming from.
Route::get('/event/{listonee}/ticket', [ListingController::class, 'viewone']);

Route::get('/events/{name}', [ListingController::class, 'show'] );
// Route::get('/addtocart/{id}', [ListingController::class, 'cart'] );


// so what exactly do we want to do first?
// Click to view something with the code.
//
// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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
