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


Route::get('/', [ListingController::class, 'index'] )->name('wc');;
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


// create ghon ghon
// Route::get('/alexadmin', function () {
//     return view('Admin');
// });

// Cart view.
Route::get('/cart', [ListingController::class, 'cartpage'] );



// USER REGISTRATION
Route::get('/signup', function(){
    return view('Signup');
});

Route::get('/login', function(){
    return view('Login');
});

// Make the create admin button work



// delete fucntionality.
Route::get('/delete/{id}', [ListingController::class, 'delete'] );




 // View create

 Route::post('/addtocart/{id}', function (Request $request, $id) {
     // First of all, find the product with the given $id

     $product = mctlists::find($id);

     if ($product) {
         // If the product is found, add it to the cart
         $cart = new Cart;
         $cart->cname = $product->name;
         $cart->clocation = $product->location;
        //  $cart->cdescription = $product->description;
        $descriptionParts = explode(',', $product->description);
        $secondPart = trim($descriptionParts[1]);
        $cart->cdescription = $secondPart;
         $cart->cquantity = $request->quantity;
         $cart->save();

         // Now, count the items in the cart and pass it to the view
        //  $cartItemCount = Cart::count();

         // Return the view with the $cartItemCount variable
         return redirect('/');
     } else {
         // Handle the case when the product with the given $id is not found
         return redirect('/')->with('error', 'Product not found.');
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
