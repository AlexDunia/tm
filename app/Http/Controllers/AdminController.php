<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\mctlists;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Stevebauman\Location\Facades\Location;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

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
            // Allow any authenticated user to create an event
            $Addevent = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'location' => 'required',
                'date' => 'required',
                'herolink' => 'required',
                'image' => 'required|url',
                'heroimage' => 'required|url',
            ]);

            // No need to process file uploads as we're now using direct URLs
            // Just directly use the provided image URLs
            $Addevent['id'] = auth()->id();

            // Additional admin-only fields or features can be added here
            if (Auth::user()->isadmin == 1) {
                // Add any admin-only fields or special features here
                // For example:
                // $Addevent['is_featured'] = $request->is_featured ?? 0;
                // $Addevent['admin_approved'] = true;
            } else {
                // For non-admin users, set default values or restrictions
                // For example:
                // $Addevent['admin_approved'] = false;
                // $Addevent['is_featured'] = 0;
            }

            // Create the event
            $event = mctlists::create($Addevent);

            // Check if we should create ticket types
            if ($request->has('create_ticket_types') && $request->has('ticket_name')) {
                $ticketNames = $request->ticket_name;
                $ticketPrices = $request->ticket_price;
                $ticketDescriptions = $request->ticket_description;
                $ticketCapacities = $request->ticket_capacity;
                $ticketSalesStarts = $request->ticket_sales_start;
                $ticketSalesEnds = $request->ticket_sales_end;

                foreach ($ticketNames as $index => $name) {
                    if (!empty($name) && isset($ticketPrices[$index])) {
                        \App\Models\TicketType::create([
                            'mctlists_id' => $event->id,
                            'name' => $name,
                            'price' => $ticketPrices[$index],
                            'description' => $ticketDescriptions[$index] ?? '',
                            'capacity' => !empty($ticketCapacities[$index]) ? $ticketCapacities[$index] : null,
                            'sales_start' => !empty($ticketSalesStarts[$index]) ? $ticketSalesStarts[$index] : now(),
                            'sales_end' => !empty($ticketSalesEnds[$index]) ? $ticketSalesEnds[$index] : now()->addMonths(2),
                            'is_active' => true,
                            'sold' => 0
                        ]);
                    }
                }
            }

            return redirect('/')->with('message', 'Event created successfully!');
        } else {
            // Redirect to login if user is not authenticated
            return redirect('/login')->with('message', 'Please login to create an event');
        }
    }

    public function storeuser(Request $request)
    {
        // Validate the form data with stronger password requirements
        $Formfield = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => [
                'required',
                'string',
                'min:8',             // minimum 8 characters
                'regex:/[a-z]/',     // at least one lowercase letter
                'regex:/[A-Z]/',     // at least one uppercase letter
                'regex:/[0-9]/',     // at least one number
                'regex:/[@$!%*#?&]/' // at least one special character
            ],
        ], [
            'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        // Check if 'profilepic' file exists in the request
        if ($request->hasFile('profilepic')) {
            // Validate and store the uploaded file
            $request->validate([
                'profilepic' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $profilePicPath = $request->file('profilepic')->store('uploadedimage', 'public');
            $Formfield['profilepic'] = $profilePicPath;
        }

        // Store IP address for security tracking
        $Formfield['ipaddress'] = $request->ip();

        // Hash the password
        $Formfield['password'] = Hash::make($Formfield['password']);

        // Create the user
        $adminuserr = User::create($Formfield);

        $adminUser = $adminuserr->firstname;

        // Send welcome email
        if($adminuserr){
            try {
                Mail::send('userwelcome', ['firstname' => $adminUser], function ($message) use ($request, $adminUser) {
                    $message->to($request->email);
                    $message->subject("Welcome to Kaka, " . $adminUser);
                });
            } catch (\Exception $e) {
                // Log the error but continue with the registration process
                // We don't want email sending failures to prevent user registration
                \Log::error('Failed to send welcome email: ' . $e->getMessage());
            }
        }

        // Log the user in
        Auth::login($adminuserr);

        return redirect('/')->with('message', 'Account created successfully! Welcome to Kaka.');
    }

    public function authenticate(Request $request)
    {
        // Implement rate limiting for login attempts
        $key = 'login.' . $request->ip();
        $maxAttempts = 5; // Maximum login attempts
        $decayMinutes = 1; // Time window in minutes

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            // Use session for consistent error handling with security-conscious messaging
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('auth_error', [
                    'type' => 'rate_limit',
                    'message' => "Too many sign-in attempts. Please try again later.",
                    'resolution' => 'Please wait or reset your password.'
                ]);
        }

        // Validate form input
        $formFields = $request->validate([
            'email' => ['required', 'email', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        // First check if user exists before attempting login
        $user = User::where('email', $formFields['email'])->first();

        if (!$user) {
            // Increment the rate limiter on failed login
            RateLimiter::hit($key, $decayMinutes * 60);

            // Log the failed attempt for security monitoring with detailed info for admins only
            \Log::warning('Login attempt with non-existent email', [
                'email' => $formFields['email'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Track non-existent account attempts in session
            $nonExistentAttempts = $request->session()->get('non_existent_attempts', 0) + 1;
            $request->session()->put('non_existent_attempts', $nonExistentAttempts);

            // After multiple attempts with non-existent accounts, redirect to signup
            // but use a generic message that doesn't reveal the email doesn't exist
            if ($nonExistentAttempts >= 2) {
                $request->session()->forget('non_existent_attempts');
                return redirect()->route('signup')
                    ->withInput(['email' => $formFields['email']])
                    ->with('auth_error', [
                        'type' => 'invalid_credentials',
                        'message' => 'Sign-in unsuccessful. Please verify your information.',
                        'resolution' => 'You may need to create an account or recover access.'
                    ]);
            }

            // Generic error that doesn't reveal if the email exists or not
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('auth_error', [
                    'type' => 'invalid_credentials',
                    'message' => 'Sign-in unsuccessful. Please verify your information.',
                    'resolution' => 'Please check your email and password and try again.'
                ]);
        }

        // Clear the non-existent account counter if user exists
        $request->session()->forget('non_existent_attempts');

        // Get IP and location data for security
        $ip = $request->ip();

        // Get location data based on the user's IP address
        try {
            $ld = Location::get($ip);
            if (!$ld) {
                $ld = (object) [
                    'ip' => $ip,
                    'countryName' => 'Unknown',
                    'countryCode' => 'Unknown',
                    'regionCode' => 'Unknown',
                    'cityName' => 'Unknown',
                ];
            }
        } catch (\Exception $e) {
            $ld = (object) [
                'ip' => $ip,
                'countryName' => 'Unknown',
                'countryCode' => 'Unknown',
                'regionCode' => 'Unknown',
                'cityName' => 'Unknown',
            ];
        }

        // Attempt login
        if (auth()->attempt($formFields, $request->filled('remember'))) {
            // Reset rate limiter on successful login
            RateLimiter::clear($key);

            // Generate new session ID to prevent session fixation attacks
            $request->session()->regenerate();

            $user = auth()->user();

            // Check if the device is new by comparing IP addresses
            $deviceIp = $ip;
            $existingDevice = User::where('id', $user->id)
                ->where('ipaddress', $deviceIp)
                ->first();

            // Send the login notification email for new devices
            if (!$existingDevice) {
                try {
                    // Enhanced device information for better security awareness
                    $deviceInfo = [
                        'browser' => $this->getBrowserInfo($request->userAgent()),
                        'device_type' => $this->getDeviceType($request->userAgent()),
                        'ip' => $ip,
                        'time' => now()->format('F j, Y \a\t g:i a'),
                        'locationData' => $ld
                    ];

                    Mail::send('logindeviceinfo', [
                        'firstname' => $user->firstname,
                        'locationData' => $ld,
                        'deviceInfo' => $deviceInfo
                    ], function ($message) use ($request, $user) {
                        $message->to($user->email);
                        $message->subject('Security Alert: New Sign-in Detected on Your Kaka Account');
                    });

                    // Update user's IP address
                    User::where('id', $user->id)->update(['ipaddress' => $deviceIp]);
                } catch (\Exception $e) {
                    // Log the error but continue with the login process
                    \Log::error('Failed to send login notification email: ' . $e->getMessage());
                }
            }

            return redirect('/');
        }

        // Increment the rate limiter on failed login
        RateLimiter::hit($key, $decayMinutes * 60);

        // Use consistent generic error message that doesn't give away if email exists
        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('auth_error', [
                'type' => 'invalid_credentials',
                'message' => 'Sign-in unsuccessful. Please verify your information.',
                'resolution' => 'Please check your email and password and try again.'
            ]);
    }

    /**
     * Get browser information from user agent
     */
    private function getBrowserInfo($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            return 'Microsoft Edge';
        } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
            return 'Internet Explorer';
        } else {
            return 'Unknown Browser';
        }
    }

    /**
     * Get device type from user agent
     */
    private function getDeviceType($userAgent)
    {
        if (strpos($userAgent, 'Mobile') !== false) {
            return 'Mobile';
        } elseif (strpos($userAgent, 'Tablet') !== false) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }

    public function disauthenticate(Request $request){
        // logout here
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'You have been logged out successfully.');
    }
}
