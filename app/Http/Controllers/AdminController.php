<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\mctlists;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Stevebauman\Location\Facades\Location;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //

    public function index(){
        return view('Adminpanel');
    }

    public function adminform(){
        return view('Adminedit');
    }

    // Show the advanced event creation form (admin only)
    public function showEventForm()
    {
        // Temporarily removed auth check for testing
        return view('admin-event-create');
    }

    // Store event with ticket types (admin only)
    public function storeEvent(Request $request)
    {
        // Log the beginning of the request
        \Log::info("Starting storeEvent request");
        \Log::info("Request method: " . $request->method());
        \Log::info("Is Ajax: " . ($request->ajax() ? 'Yes' : 'No'));
        \Log::info("Wants JSON: " . ($request->wantsJson() ? 'Yes' : 'No'));

        // Check storage permission
        try {
            \Log::info("Testing storage permission for public disk");
            $storagePathPublic = storage_path('app/public');
            \Log::info("Public storage path: " . $storagePathPublic);

            if (!file_exists($storagePathPublic)) {
                \Log::warning("Public storage directory does not exist. Attempting to create it.");
                mkdir($storagePathPublic, 0755, true);
            }

            $isWritable = is_writable($storagePathPublic);
            \Log::info("Public storage is writable: " . ($isWritable ? 'Yes' : 'No'));

            if (!$isWritable) {
                \Log::error("Storage directory is not writable!");
            }
        } catch (\Exception $e) {
            \Log::error("Error checking storage permissions: " . $e->getMessage());
        }

        try {
            // Debug information
            if (Auth::check()) {
                $user = Auth::user();
                $isAdmin = $user->isadmin ?? 'null';
                \Log::info("User is logged in. User ID: {$user->id}, isAdmin: {$isAdmin}");
            } else {
                \Log::info("User is not logged in");
            }

            // Log the request data (except for files)
            $requestData = $request->except(['image', 'heroimage']);
            \Log::info("Request data: " . json_encode($requestData));

            // Log if files are present
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                \Log::info("Image file present: " . $imageFile->getClientOriginalName() . ", size: " . $imageFile->getSize() . ", mime: " . $imageFile->getMimeType());
            } else {
                \Log::info("Image file not present");
            }

            if ($request->hasFile('heroimage')) {
                $heroImageFile = $request->file('heroimage');
                \Log::info("Hero image file present: " . $heroImageFile->getClientOriginalName() . ", size: " . $heroImageFile->getSize() . ", mime: " . $heroImageFile->getMimeType());
            } else {
                \Log::info("Hero image file not present");
            }

            // Validate event data
            \Log::info("Beginning validation");
            $eventData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'location' => 'required|string|max:255',
                'date' => 'required|string',
                'time' => 'nullable|string',
                'enddate' => 'nullable|string',
                'category' => 'nullable|string',
                'startingprice' => 'nullable|string',
                'earlybirds' => 'nullable|string',
                'tableone' => 'nullable|string',
                'tabletwo' => 'nullable|string',
                'tablethree' => 'nullable|string',
                'tablefour' => 'nullable|string',
                'tablefive' => 'nullable|string',
                'tablesix' => 'nullable|string',
                'tableseven' => 'nullable|string',
                'tableeight' => 'nullable|string',
                'herolink' => 'required|string',
                'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5120', // Increased max size and using file validation
                'heroimage' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5120', // Increased max size and using file validation
            ]);
            \Log::info("Validation passed");

            // Format time to handle ranges (e.g., "2:00 PM - 11:00 PM")
            if (isset($eventData['time']) && !empty($eventData['time'])) {
                // If the time contains a range (with hyphen), just take the start time
                if (strpos($eventData['time'], '-') !== false) {
                    $timeRange = explode('-', $eventData['time']);
                    $eventData['time'] = trim($timeRange[0]);
                }
            }

            // Handle file uploads
            if ($request->hasFile('image')) {
                try {
                    \Log::info("Processing image file upload");
                    $file = $request->file('image');

                    // Manual file check
                    if (!$file->isValid()) {
                        \Log::error("Uploaded image file is not valid");
                        throw new \Exception("The uploaded image file is invalid");
                    }

                    // Store with custom name to avoid conflicts
                    $filename = 'image_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploadedimage', $filename, 'public');

                    if (!$path) {
                        \Log::error("Failed to store image file");
                        throw new \Exception("Failed to store image file");
                    }

                    $eventData['image'] = $path;
                    \Log::info("Image stored at: " . $path);
                } catch (\Exception $e) {
                    \Log::error("Error storing image file: " . $e->getMessage());
                    throw $e;
                }
            }

            if ($request->hasFile('heroimage')) {
                try {
                    \Log::info("Processing hero image file upload");
                    $file = $request->file('heroimage');

                    // Manual file check
                    if (!$file->isValid()) {
                        \Log::error("Uploaded hero image file is not valid");
                        throw new \Exception("The uploaded hero image file is invalid");
                    }

                    // Store with custom name to avoid conflicts
                    $filename = 'heroimage_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('herouploadedimage', $filename, 'public');

                    if (!$path) {
                        \Log::error("Failed to store hero image file");
                        throw new \Exception("Failed to store hero image file");
                    }

                    $eventData['heroimage'] = $path;
                    \Log::info("Hero image stored at: " . $path);
                } catch (\Exception $e) {
                    \Log::error("Error storing hero image file: " . $e->getMessage());
                    throw $e;
                }
            }

            // Create the event using a database transaction to ensure data integrity
            \Log::info("Beginning database transaction");
            DB::beginTransaction();

            try {
                // Debug the event data
                \Log::info("Creating event with data: " . json_encode($eventData));

                // Reset the PostgreSQL sequence if needed
                DB::statement("SELECT setval(pg_get_serial_sequence('mctlists', 'id'), (SELECT MAX(id) FROM mctlists) + 1, false)");
                \Log::info("Reset PostgreSQL sequence for mctlists table");

                // Create the event without explicit ID to let PostgreSQL handle sequence
                $event = mctlists::create($eventData);

                \Log::info("Event created with ID: " . $event->id);

                // Process ticket types if they exist
                if ($request->has('tickets')) {
                    \Log::info("Processing ticket types");
                    $ticketCount = 0;

                    foreach ($request->tickets as $ticketData) {
                        // Skip empty ticket entries
                        if (empty($ticketData['name']) || empty($ticketData['price'])) {
                            \Log::info("Skipping empty ticket entry");
                            continue;
                        }

                        \Log::info("Creating ticket: " . json_encode($ticketData));
                        // Create ticket type
                        TicketType::create([
                            'mctlists_id' => $event->id,
                            'name' => $ticketData['name'],
                            'price' => $ticketData['price'],
                            'description' => $ticketData['description'] ?? null,
                            'capacity' => !empty($ticketData['capacity']) ? $ticketData['capacity'] : null,
                            'sales_start' => !empty($ticketData['sales_start']) ? $ticketData['sales_start'] : null,
                            'sales_end' => !empty($ticketData['sales_end']) ? $ticketData['sales_end'] : null,
                            'is_active' => $ticketData['is_active'] ?? 1,
                            'sold' => 0
                        ]);
                        $ticketCount++;
                    }
                    \Log::info("Created $ticketCount tickets");
                }

                DB::commit();
                \Log::info("Database transaction committed - Event created successfully");

                // Check if the request is AJAX
                if ($request->ajax() || $request->wantsJson()) {
                    \Log::info("Returning JSON response for AJAX request");
                    return response()->json([
                        'success' => true,
                        'message' => 'Event created successfully!',
                        'redirect' => '/',
                        'event_id' => $event->id
                    ]);
                }

                // Regular form submission response
                \Log::info("Returning redirect response for regular form submission");
                return redirect('/')->with('message', 'Event created successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error("Error in database transaction: " . $e->getMessage());
                \Log::error($e->getTraceAsString());
                throw $e;
            }
        } catch (\Exception $e) {
            \Log::error("Main try-catch caught exception: " . $e->getMessage());
            \Log::error($e->getTraceAsString());

            // Check if the request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create event: ' . $e->getMessage()
                ], 500);
            }

            // Regular form submission response
            return back()->with('error', 'Failed to create event: ' . $e->getMessage())
                        ->withInput();
        }
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

                // Remove the explicit ID assignment that causes sequence conflicts
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
                    $message->subject("Welcome to Tixdemand, " . $adminUser);
                });
            } catch (\Exception $e) {
                // Log the error but continue with the registration process
                // We don't want email sending failures to prevent user registration
                \Log::error('Failed to send welcome email: ' . $e->getMessage());
            }
        }

        // Log the user in
        Auth()->login($adminuserr);

        return redirect('/')->with('message', 'Account created successfully! Welcome to Tixdemand.');
    }

    public function authenticate(Request $request)
    {
        // Implement rate limiting for login attempts
        $key = 'login.' . $request->ip();
        $maxAttempts = 5; // Maximum login attempts
        $decayMinutes = 1; // Time window in minutes

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->status(429);
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

            // Log the failed attempt for security monitoring
            \Log::warning('Login attempt with non-existent email', [
                'email' => $formFields['email'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Track non-existent account attempts in session
            $nonExistentAttempts = $request->session()->get('non_existent_attempts', 0) + 1;
            $request->session()->put('non_existent_attempts', $nonExistentAttempts);

            // After 2 attempts with non-existent accounts, redirect to signup
            if ($nonExistentAttempts >= 2) {
                $request->session()->forget('non_existent_attempts');
                return redirect()->route('signup')
                    ->with('message', 'We couldn\'t find an account with that email. We\'ve redirected you to our signup page.')
                    ->withInput(['email' => $formFields['email']]);
            }

            throw ValidationException::withMessages([
                'email' => 'Account not found. Please check your email or create a new account.',
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
                        $message->subject('Security Alert: New Login Detected on Your Tixdemand Account');
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

        // Return with error message
        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
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
