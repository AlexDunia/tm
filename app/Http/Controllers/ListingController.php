<?php

namespace App\Http\Controllers;

use App\Models\mctlists;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

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

    public function index()
    {
        if (Auth::check()) {
            $status = Auth::user()->isadmin;
            // $welcomeData = mctlists::latest()->get();
            if ($status == 1) {
                return view('Adminpanel',[
                    'heading' => 'My Laravel Application',
                    'welcome' => mctlists::latest()->get(),
                    // 'welcome' => mctlists::latest()->filter(request(['search']))->get()

                ]);
            }
            else if ($status == 0) {
                // $carts = Cart::get();
                // return view('Cartuser', [
                //     'mycart' => $carts,
                // ]);

                return view('welcome',[
                    'heading' => 'My Laravel Application',
                    'welcome' => mctlists::latest()->simplePaginate(5),
                    // 'welcome' => mctlists::latest()->get(),
                    // 'welcome' => mctlists::latest()->filter(request(['search']))->get()

                ]);
            }
        }

        $cartItemCount = Cart::count();
        $welcome = mctlists::all();

        return view('welcome', [
            'heading' => 'My Laravel Application',
            // 'welcome' => mctlists::latest()->get(),
            'welcome' => mctlists::latest()->simplePaginate(5),
            // 'welcome' => mctlists::latest()->filter(request(['search']))->get(),

            'cartItemCount' => $cartItemCount,
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
                ->get();

            // Check if the query result is empty

            if ($products->isEmpty()) {
                $si = trim($request->input('name'));
                // Return a different view for no results found
                return view('Snf', compact('si'));
            }

            return view('search', compact('products', 'si'));
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
        $sr = mctlists::where('description', $category)->get();
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



    public function payform()
    {
        if (Auth::check()) {
            $latestCartItem = auth()->user()->relatewithcart()->latest()->first();
            // dd($latestCartItem);

            return view('Checkout', [
                'mycart' => $latestCartItem,
            ]);
        }
        $tname = session()->get('tname');
        $tprice = session()->get('tprice');
        $totalprice = session()->get('totalprice');
        $tquantity = session()->get('tquantity');
        $eventname = session()->get('eventname');
        return view('Checkout', compact('tname', 'tprice', 'totalprice', 'tquantity', 'eventname'));
    }

    public function trypayment()
    {

        return view('trypay');
    }




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

    public function show($name) {
        return view('listone', [
            'listonee' => mctlists::where('name', $name)->first()
        ]);
    }

    // public function show($id) {
    //     $listonee = mctlists::find($id);

    //     if (!empty($listonee->description)) {
    //         // If the 'description' field is not empty, return a different view.
    //         return view('expiredlistone', [
    //             'listonee' => $listonee
    //         ]);
    //     } else {
    //         // If the 'description' field is empty, return the original view.
    //         return view('listone', [
    //             'listonee' => $listonee
    //         ]);
    //     }
    // }


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
