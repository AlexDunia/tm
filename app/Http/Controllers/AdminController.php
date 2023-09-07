<?php

namespace App\Http\Controllers;
use auth;
use App\Models\User;
use App\Models\mctlists;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Stevebauman\Location\Facades\Location;
use Illuminate\Validation\ValidationException;

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
            'email'=>['required', 'email', Rule::unique('users', 'email')],
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

        $Formfield['ipaddress'] = $request->ip();

        $Formfield['password'] = bcrypt($Formfield['password']);

        $adminuserr = User::create($Formfield);

        $adminUser = $adminuserr->firstname;

        if($adminuserr){
            Mail::send('userwelcome', ['firstname' => $adminUser], function ($message) use ($request, $adminUser) {
                $message->to($request->email);
                $message->subject("Welcome to Tixdemand, " . $adminUser);
            });

        }

        Auth()->login($adminuserr);

        return redirect('/');
    }



    public function authenticate(Request $request)
{
    $formFields = $request->validate([
        'email' => ['required', 'email'],
        'password' => 'required',
    ]);


    $ip = $request->ip();
    // Get the user's IP address from the request

    // Get location data based on the user's IP address
    $ld = Location::get($ip);
    if (!$ld) {
        // Handle the case where location data couldn't be obtained
        // For example, you can set default values or show an error message.
        $ld = (object) [
            'ip' => 'N/A',
            'countryName' => 'N/A',
            'countryCode' => 'N/A',
            'regionCode' => 'N/A',
            'cityName' => 'N/A',
            // Add more default properties as needed
        ];
    }

    if (auth()->attempt($formFields)) {
        $request->session()->regenerate();
        $user = auth()->user();

        // Check if the device is new
        $deviceIp = $ip;
        $existingDevice = User::where('id', auth()->user()->id)
            ->where('ipaddress', $deviceIp)
            ->first();

        // Send the login notification email here
        if (!$existingDevice) {
            // Send the login notification email only for new devices
            Mail::send('logindeviceinfo', ['firstname' => $user->firstname, 'locationData' => $ld], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Login attempt on your account');
            });
        }
        return redirect('/');
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
