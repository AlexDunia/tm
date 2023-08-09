<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\mctlists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //

    public function index(){
        return view('Adminpanel');
    }

    public function adminform(){
        return view('Adminedit');
    }

    public function store(Request $request)
    {
        if (Auth::check()) {
            $status = Auth::user()->isadmin;
            if ($status == 1) {
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
            return view('Adminpanel');
        }

    }

    public function storeuser(Request $request)
    {
        // Validate the form data, excluding 'profilepic'
        $Formfield = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email'=>['required', 'email'],
            'password' => 'required',
        ]);

        // Check if 'profilepic' file exists in the request
        if ($request->hasFile('profilepic')) {
            // Validate and store the uploaded file
            $request->validate([
                'profilepic' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust mime types and max file size as needed
            ]);

            $profilePicPath = $request->file('profilepic')->store('uploadedimage', 'public');
            $Formfield['profilepic'] = $profilePicPath;
        }

        $Formfield['password'] = bcrypt($Formfield['password']);

        $adminuser = User::create($Formfield);

        Auth()->login($adminuser);

        return redirect('/');
    }



    public function authenticate(Request $request){
        // dd($request);
        $Formfield = $request->validate([
            'email'=>['required', 'email'],
            'password'=>'required',
            // 'date'=>'required',
        ]);

        if (auth()->attempt($Formfield)) {
          $request->session()->regenerate();
          return redirect('/');
            // $profilePicPath = $request->file('profilepic')->store('uploadedimage', 'public');
            // $Formfield['profilepic'] = $profilePicPath;
        }

        return back();
    }

    public function disauthenticate(Request $request){
        // logout here
      auth()->logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();
        return back();
    }
}
