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


Route::get('/', [ListingController::class, 'index'] )->name('login');;
Route::get('/payment', [PaymentController::class, 'index'] );
Route::get('/verifypayment/{reference}', [PaymentController::class, 'verify'] );

// View Admin Panel
Route::get('/dunia', [AdminController::class, 'index']);

// This stores the user. and note that admin is user here.
Route::post('/createnewadmin', [AdminController::class, 'storeuser']);

// This is the form that gives admin ability to make posts.
Route::get('/createpost', [AdminController::class, 'adminform']);

// This gives the admin the ability to edit posts.
Route::post('/creationsuccess', [AdminController::class, 'store']);

// Users can now Login
Route::post('/authenticated', [AdminController::class, 'authenticate']);

// Users can now Log out
Route::post('/logout', [AdminController::class, 'disauthenticate']);


// create ghon ghon
// Route::get('/alexadmin', function () {
//     return view('Admin');
// });

// Cart view.
Route::get('/cart', [ListingController::class, 'cartpage'] );

// Cart view.
Route::get('/checkout', [ListingController::class, 'payform'] );

// USER REGISTRATION
Route::get('/signup', function(){
    return view('Signup');
});

Route::get('/login', function(){
    return view('Login');
});

Route::get('/cartitem', function(){
    return view('checkout');
});

// Make the create admin button work



// delete fucntionality.
Route::get('/delete/{id}', [ListingController::class, 'delete'] );


 // View create

 Route::post('/addtocart', function (Request $request) {
    $productIds = $request->input('product_ids');
    $tableNames = $request->input('table_names');
    $quantities = $request->input('quantities');

    // Loop through the selected items and add them to the cart if quantity > 0
    foreach ($productIds as $index => $productId) {
        $quantity = $quantities[$index];
        if ($quantity > 0) {
            $product = mctlists::find($productId);
            $tableName = $tableNames[$index];

            if ($product) {
                $cart = new Cart;
                $realtn = explode(',', $tableName);
$namepart = trim($realtn[0]);
$priceparts = explode('.', trim($realtn[1])); // Split by period
$pricepart = $priceparts[0]; // Take the first part

                if (auth()->check()) {
                    // User is authenticated, store in the cart
                    $cart->cname = $namepart;
                    $cart->cprice = $pricepart;
                    $ctotalprice = $pricepart * $quantity;
                    $cart->ctotalprice = $ctotalprice;
                    $cart->clocation = $product->location;
                    $cart->cdescription = $product->description;
                    $cart->user_id = auth()->id();
                    $cart->cquantity = $quantity;
                    $cart->save();
                    return redirect('/checkout');
                } else {
                    // User is not authenticated, store in the session
                    $request->session()->put('tname', $namepart);
                    $request->session()->put('tprice', $pricepart);
                    $request->session()->put('totalprice', $pricepart * $quantity);
                }
            } else {
                // Handle the case when the product with the given ID is not found
                return redirect()->back()->with('error', 'Product not found.');
            }
        }
    }


    // Return to the previous page or a specific success page
    return redirect()->back()->with('success', 'Items added to cart successfully');
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
 Route::get('/event/{listonee}/ticket', function(mctlists $listonee){
    // dd($listonee->name);
    return view('Editpost', [
        'lexlist'=> $listonee
    ]);
 });


Route::get('/events/{name}', [ListingController::class, 'show'] );
// Route::get('/addtocart/{id}', [ListingController::class, 'cart'] );


// so what exactly do we want to do first?
// Click to view something with the code.
//
// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
