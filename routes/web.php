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
use App\Http\Controllers\ListingController;
use App\Http\Controllers\PaymentController;
// use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\MainadminController;
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


Route::get('/', [ListingController::class, 'index'])->name('login');
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
});
  // This stores the user. and note that admin is user here.
   Route::post('/createnewadmin', [AdminController::class, 'storeuser']);
    // Add other routes as needed
});

// With this, auth and non auth users can send emails
Route::post('/formsent', [ListingController::class, 'contactsend']);

// This is the form that gives admin ability to make posts.
Route::get('/createpost', [AdminController::class, 'adminform']);

// This gives the admin the ability to edit posts.
Route::post('/creationsuccess', [AdminController::class, 'store']);

// Users can now Login
Route::post('/authenticated', [AdminController::class, 'authenticate']);

// Users can now Log out
// Route::post('/logout', [AdminController::class, 'disauthenticate']);
Route::match(['get', 'post'], '/logout', [AdminController::class, 'disauthenticate']);

// create ghon ghon
// Route::get('/alexadmin', function () {
//     return view('Admin');
// });

// Cart view.
Route::get('/cart', [ListingController::class, 'cartpage'] );
Route::get('/trypayment', [ListingController::class, 'trypayment'] );
Route::post('/tryverify/{reference}', [ListingController::class, 'tryverify'] );

// Cart view.
Route::get('/checkout', [ListingController::class, 'payform'] )->name('checkout');
// Route::get('/checkoutnew', [ListingController::class, 'payform'] );

Route::get('/success', [PaymentController::class, 'success'] )->name('success');

Route::get('/notfound', [ListingController::class, 'notfound'] );
Route::get('/forgotpassword', [ListingController::class, 'forgotpassword'] )->name('fp');
Route::post('/forgotpasswordpost', [ListingController::class, 'forgotpasswordpost'] )->name('fpp');
Route::get('/resetpassword{token}', [ListingController::class, 'resetpassword'] )->name('rp');
Route::post('/resetpasswordpost', [ListingController::class, 'resetpasswordpost'] )->name('rpp');

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





// delete fucntionality.
Route::get('/delete/{id}', [ListingController::class, 'delete'] );


Route::post('/addtocart', function (Request $request) {
    // Validate the incoming request data
    $request->validate([
        'product_ids' => 'required|array',
        'table_names' => 'required|array',
        'quantities' => 'required|array',
    ]);

    $addedToCart = false; // Flag to check if anything is added to the cart

    foreach ($request->product_ids as $key => $productId) {
        $quantity = $request->quantities[$key];

        if ($quantity > 0) { // Only add items with quantity greater than zero
            $cartItem = new Cart();
            $product = mctlists::find($productId);
            $nameandprice = $request->table_names[$key];
            $nameandpricesplit = explode(',', $nameandprice);
            $namepart = trim($nameandpricesplit[0]);
            $pricepart = trim($nameandpricesplit[1]);

            $cartItem->cname = $product->name;
            $cartItem->eventname = $namepart;
            $cartItem->cprice = $pricepart;
            $ctotalprice = $pricepart * $quantity;
            $cartItem->cquantity = $quantity;
            $cartItem->ctotalprice = $ctotalprice;
            $cartItem->clocation = $product->location;
            $cartItem->cdescription = $product->image;

            if (auth()->check()) {
                $cartItem->user_id = auth()->id();
                $cartItem->save();
            } else {
                // Store data in the session for unauthenticated users
                $request->session()->put('tname', $namepart);
                $request->session()->put('tprice', $pricepart);
                $request->session()->put('tquantity', $quantity);
                $request->session()->put('eventname', $product->name);
                $request->session()->put('totalprice', $ctotalprice);
            }
            $addedToCart = true; // Set the flag to true if something is added to the cart
        }
    }
    if ($addedToCart) {
        if (auth()->check()) {
            return redirect()->route('checkout')->with('message', 'Products added to cart successfully');
        } else {
            // Redirect to a different page or display a message for unauthenticated users
            return redirect()->route('checkout');
        }
    } else {
        return redirect()->back()->with('message', 'At least one quantity must be added before proceeding');
    }
});




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
