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
        return redirect()->route('login')->with('message', 'Password Reset Link Sent To Your Email! Click to Change Now.');
    }


    public function payform()
    {
        if (Auth::check()) {
            // The items in the cart, once it has a different name, the previous items, they must have the samm name
            // one image, one name, differenct eventtype and diffrent quantity and diferent prices.
            $latestCartItem = auth()->user()->relatewithcart()->latest()->first();
            // $latestCartItem = auth()->user()->relatewithcart()->get();
            // dd($latestCartItem);

            return view('Checkout', [
                'mycart' => $latestCartItem,
            ]);
        }
        $tname = session()->get('tname');
        $timage = session()->get('timage');
        $tprice = session()->get('tprice');
        $totalprice = session()->get('totalprice');
        $tquantity = session()->get('tquantity');
        $eventname = session()->get('eventname');
        return view('Checkout', compact('tname', 'tprice', 'totalprice', 'tquantity', 'eventname', 'timage'));
    }


    public function index(Request $request)
    {
        // Mock data for the welcome view (no DB)
        $mockWelcome = collect([
            (object)[
                'name' => 'Event One',
                'herolink' => 'Hero Link 1',
                'heroimage' => 'default1.jpg',
                'image' => 'default1.jpg',
                'location' => 'Location 1',
                'date' => now(),
            ],
            (object)[
                'name' => 'Event Two',
                'herolink' => 'Hero Link 2',
                'heroimage' => 'default2.jpg',
                'image' => 'default2.jpg',
                'location' => 'Location 2',
                'date' => now()->addDay(),
            ],
            (object)[
                'name' => 'Event Three',
                'herolink' => 'Hero Link 3',
                'heroimage' => 'default3.jpg',
                'image' => 'default3.jpg',
                'location' => 'Location 3',
                'date' => now()->addDays(2),
            ],
            (object)[
                'name' => 'Event Four',
                'herolink' => 'Hero Link 4',
                'heroimage' => 'default4.jpg',
                'image' => 'default4.jpg',
                'location' => 'Location 4',
                'date' => now()->addDays(3),
            ],
            (object)[
                'name' => 'Event Five',
                'herolink' => 'Hero Link 5',
                'heroimage' => 'default5.jpg',
                'image' => 'default5.jpg',
                'location' => 'Location 5',
                'date' => now()->addDays(4),
            ],
        ]);

        // Set up metadata so the collection behaves like a paginator
        $mockWelcome->links = function() {
            return '';
        };

        return view('welcome', [
            'heading' => 'My Laravel Application',
            'welcome' => $mockWelcome,
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

        // Generate a random token securely
        $token = Str::random(64);

        // Validate the generated token
        if (!is_string($token) || !preg_match('/^[A-Za-z0-9]+$/', $token) || strlen($token) !== 64) {
            // Invalid token generated, handle the error (e.g., log it)
            throw ValidationException::withMessages([
                'token' => 'Invalid token generated.',
            ]);
        }

        // Now add data into the table data created by Laravel
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Next, send an email to the email address

        // Mail::to($request->email)->send(new EmailTemplate($token));

        Mail::send('Emailtemplate', ['token' => $token], function ($message) use ($request){
            $message->to($request->email);
            $message->subject('Reset password');
        });

        // return view('Login');

        // return view('Login')->with('message', 'Nothing was added to the cart. Please add items with a quantity greater than zero.');
        return redirect()->route('logg')->with('message', 'Password Reset Link Sent To Your Email! Click to Change Now.');
    }

    public function resetpassword($token){
        // So we actually have to pass the token of the previous form because we will use that
        // in the new database
    return view('Resetpassword', compact('token'));
    }

    public function resetpasswordpost(Request $request){
// start with checking if the requests in the form are entered
    $request->validate([
        "email" => "required|email|exists:users",
        "password" => "required|string|min:6|confirmed",
        "password_confirmation" => "required"
    ]);

    // Next is to search the database, store it inside a vriable and and see if it mathces the input
     $updatepassword = DB::table('password_resets')
     ->where([
        "email" => $request->email,
        "token" => $request->token,
     ]);

    //  Now we want to use th variable that searches
     if(!$updatepassword){
        return view('Signup');
     };

    //  if it is the case, however, update the password
     User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
     return view('login');
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
                  // If the 'description' field is empty, return the original view.
            return view('listone', [
                'listonee' => $listonee
            ]);
        }
    }


    // The next controller shows the cart page.
    // After you have done authentication,. its going to show according to users email.
        public function cartpage(){
        $carts = Cart::get();
        // $welcomeData = mctlists::all();
        return view('Cartuser', [
            // 'heading' => 'My laravel application',
            'mycart' => $carts,
        ]);
    }


    // Delete
    public function delete($id){
    //    Question now is, what do you want to achieve?
    $cdelete = Cart::find($id);
    $cdelete->delete();
    return redirect()->back();
    }

}
