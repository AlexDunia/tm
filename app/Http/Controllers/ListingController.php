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
            $username = Auth::user()->name;
            $welcomeData = mctlists::all();
            if ($username == 'duniaadmin') {
                return view('Adminpanel',[
                    'heading' => 'My Laravel Application',
                    'welcome' => $welcomeData,
                ]);
            } else if ($username == 'dunia') {
                $carts = Cart::get();
                // $welcomeData = mctlists::all();
                return view('Cartuser', [
                    // 'heading' => 'My laravel application',
                    'mycart' => $carts,
                ]);
            }
        }

        $cartItemCount = Cart::count();
        $welcomeData = mctlists::all();

        return view('welcome', [
            'heading' => 'My Laravel Application',
            'welcome' => $welcomeData,
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

    public function adminform(){
        return view('Adminedit');
    }

    public function store(Request $request)
    {
        if (Auth::check()) {
            $username = Auth::user()->name;
            if ($username == 'duniaadmin') {
                $Addevent = $request->validate([
                    'name' => 'required',
                    'description' => 'required',
                    'location' => 'required',
                    'date' => 'required',
                    'herolink' => 'required',
                ]);

                // Now for the file image upload, quite staright forward.
                if($request->hasFile('image')){
                    $Addevent['image'] = $request->file('image')->store('uploadedimage', 'public');
                };

                if($request->hasFile('heroimage')){
                    $Addevent['heroimage'] = $request->file('heroimage')->store('herouploadedimage', 'public');
                };

                $Addevent['id'] = auth()->id();
                mctlists::create($Addevent);

                return redirect('/');
            }
        } else {
            // return redirect()->route('Adminedit');
            return view('Adminedit');
        }

    }

}
