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
            $welcomeData = mctlists::all();
            if ($status == 1) {
                return view('Adminpanel',[
                    'heading' => 'My Laravel Application',
                    'welcome' => $welcomeData,
                ]);
            }
            else if ($status == 0) {
                // $carts = Cart::get();
                // return view('Cartuser', [
                //     'mycart' => $carts,
                // ]);

                return view('welcome',[
                    'heading' => 'My Laravel Application',
                    'welcome' => $welcomeData,
                ]);
            }
        }

        $cartItemCount = Cart::count();
        $welcome = mctlists::all();

        return view('welcome', [
            'heading' => 'My Laravel Application',
            'welcome' => $welcome,
            'cartItemCount' => $cartItemCount,
        ]);
    }


    public function show($id) {
        return view('listone', [
            'listonee' => mctlists::find($id)
        ]);
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
