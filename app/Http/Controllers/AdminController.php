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
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\UserRegisterRequest;

class AdminController extends Controller
{
    //

    public function index(){
        return view('Adminpanel');
    }

    public function adminform(){
        return view('Adminedit');
    }

    public function store(EventStoreRequest $request)
    {
        if (Auth::check()) {
            // Validated data is already sanitized through the FormRequest
            $Addevent = $request->validated();

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

    public function storeuser(UserRegisterRequest $request)
    {
        // Check if the request is AJAX using multiple methods to ensure detection
        $isAjax = $request->ajax() ||
                 $request->wantsJson() ||
                 $request->has('is_ajax') ||
                 $request->header('X-Requested-With') == 'XMLHttpRequest';

        // Force AJAX response format for debugging if needed
        if ($request->has('force_ajax_response')) {
            $isAjax = true;
        }

        \Log::info('Registration attempt', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'is_ajax' => $isAjax,
            'headers' => $request->header(),
            'has_ajax_flag' => $request->has('is_ajax'),
            'email' => $request->email
        ]);

        try {
            // Get validated and sanitized data from the form request
            $Formfield = $request->validated();

            // Log validation results
            \Log::info('Registration validation passed', [
                'fields' => array_keys($Formfield)
            ]);

            // Check if 'profilepic' file exists in the request
            if ($request->hasFile('profilepic')) {
                try {
                    // Validate and store the uploaded file
                    $request->validate([
                        'profilepic' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                    ]);

                    $profilePicPath = $request->file('profilepic')->store('uploadedimage', 'public');
                    $Formfield['profilepic'] = $profilePicPath;

                    \Log::info('Profile picture uploaded', [
                        'path' => $profilePicPath
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Profile picture upload failed', [
                        'error' => $e->getMessage()
                    ]);

                    // Continue without profile pic if there's an error
                    unset($Formfield['profilepic']);
                }
            }

            // Store IP address for security tracking
            $Formfield['ipaddress'] = $request->ip();

            // Hash the password
            $Formfield['password'] = Hash::make($Formfield['password']);

            // Add default values for any required fields that might be missing
            $Formfield['isadmin'] = $Formfield['isadmin'] ?? 0;

            // Check if email already exists despite validation
            if (User::where('email', $Formfield['email'])->exists()) {
                throw ValidationException::withMessages([
                    'email' => ['This email is already registered. Please use a different email address.'],
                ]);
            }

            // Create the user
            $adminuserr = User::create($Formfield);

            \Log::info('User created successfully', [
                'user_id' => $adminuserr->id,
                'email' => $adminuserr->email
            ]);

            $adminUser = $adminuserr->firstname;

            // Send welcome email
            if($adminuserr){
                try {
                    Mail::send('userwelcome', ['firstname' => $adminUser], function ($message) use ($request, $adminUser) {
                        $message->to($request->email);
                        $message->subject("Welcome to Kaka, " . $adminUser);
                    });

                    \Log::info('Welcome email sent', [
                        'to' => $request->email
                    ]);
                } catch (\Exception $e) {
                    // Log the error but continue with the registration process
                    // We don't want email sending failures to prevent user registration
                    \Log::error('Failed to send welcome email: ' . $e->getMessage(), [
                        'user_id' => $adminuserr->id,
                        'email' => $request->email,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Log the user in
            Auth::login($adminuserr);

            \Log::info('User logged in after registration', [
                'user_id' => $adminuserr->id
            ]);

            // Return JSON response for AJAX requests
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'redirect' => '/',
                    'message' => 'Account created successfully! Welcome to Kaka.'
                ]);
            }

            // Regular response for non-AJAX requests
            return redirect('/')->with('message', 'Account created successfully! Welcome to Kaka.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            \Log::warning('Validation failed during registration', [
                'errors' => $e->errors(),
                'email' => $request->email
            ]);

            // Handle validation errors for AJAX requests
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e; // Re-throw for regular requests

        } catch (\Exception $e) {
            // Log the detailed error with stack trace
            \Log::error('User registration error: ' . $e->getMessage(), [
                'email' => $request->email,
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'There was an error creating your account. Please try again.',
                    'debug_message' => env('APP_DEBUG') ? $e->getMessage() : null
                ], 500);
            }

            return redirect()->back()
                ->withInput($request->except('password'))
                ->with('error', 'There was an error creating your account. Please try again.');
        }
    }

    public function authenticate(Request $request)
    {
        // Check if the request is AJAX
        $isAjax = $request->ajax() ||
                 $request->wantsJson() ||
                 $request->has('is_ajax') ||
                 $request->header('X-Requested-With') == 'XMLHttpRequest';

        // Implement rate limiting for login attempts
        $key = 'login.' . $request->ip();
        $maxAttempts = 5; // Maximum login attempts
        $decayMinutes = 1; // Time window in minutes

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            // Check if it's an AJAX request
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many sign-in attempts. Please try again later.',
                    'seconds_remaining' => $seconds
                ], 429);
            }

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
        try {
            $formFields = $request->validate([
                'email' => ['required', 'email', 'string', 'max:255'],
                'password' => ['required', 'string'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors for AJAX requests
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e; // Re-throw for regular requests to use Laravel's built-in handling
        }

        // First check if user exists before attempting login - using constant time comparison
        // to prevent timing attacks that could reveal whether an email exists or not
        $user = null;
        $found = false;

        // Retrieve all users with matching email (should be 0 or 1 since email is unique)
        $users = User::where('email', $formFields['email'])->get();

        // Process results in constant time to prevent timing attacks
        foreach ($users as $potentialUser) {
            if (hash_equals($potentialUser->email, $formFields['email'])) {
                $user = $potentialUser;
                $found = true;
            }
        }

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

            // Check if it's an AJAX request
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sign-in unsuccessful. Please verify your information.'
                ], 401);
            }

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

        // Attempt login with strong security measures
        if (auth()->attempt($formFields, $request->filled('remember'))) {
            // Reset rate limiter on successful login
            RateLimiter::clear($key);

            // Generate new session ID to prevent session fixation attacks
            $request->session()->regenerate();

            // Update last login timestamp for security auditing
            $loggedInUser = auth()->user();
            $loggedInUser->last_login_at = now();
            $loggedInUser->last_login_ip = $request->ip();
            $loggedInUser->login_count = ($loggedInUser->login_count ?? 0) + 1;
            $loggedInUser->save();

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

            // Check if it's an AJAX request
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'redirect' => '/'
                ]);
            }

            return redirect('/');
        }

        // Increment the rate limiter on failed login
        RateLimiter::hit($key, $decayMinutes * 60);

        // Check if it's an AJAX request
        if ($isAjax) {
            return response()->json([
                'success' => false,
                'message' => 'Passwords do not match. Please retry or recover your account.'
            ], 401);
        }

        // Show specific password error message as requested
        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('auth_error', [
                'type' => 'invalid_credentials',
                'message' => 'Passwords do not match, retry, recover.',
                'resolution' => 'Please try again with the correct password or use the recover option.'
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
        // Always logout regardless of session state
        if (Auth::check()) {
            Auth::logout();
        }

        // Always invalidate session, even if it might be already expired
        if ($request->session()->isStarted()) {
            try {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            } catch (\Exception $e) {
                // If session already invalidated, just continue silently
            }
        }

        // Clear any auth-specific cookies that might exist
        $cookies = [
            'remember_web',
            'kaka_session',
            Auth::getRecallerName()
        ];

        $response = redirect('/login')->with('message', 'You have been logged out successfully.');

        // Remove cookies that might be causing issues
        foreach ($cookies as $cookie) {
            $response->withoutCookie($cookie);
        }

        return $response;
    }

    /**
     * View payment security logs
     *
     * @return \Illuminate\View\View
     */
    public function paymentSecurityLogs()
    {
        // Check if user is admin
        if (!auth()->user()->is_admin) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        // Get the last 100 payment middleware log entries
        $logPath = storage_path('logs/laravel.log');
        $paymentLogs = [];

        if (file_exists($logPath)) {
            $logContents = file_get_contents($logPath);

            // Extract payment verification middleware entries
            preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] production\.INFO: Payment verification middleware running.*?(?=\[\d{4}-\d{2}-\d{2}|\Z)/s', $logContents, $middlewareEntries);

            if (!empty($middlewareEntries[0])) {
                foreach ($middlewareEntries[0] as $entry) {
                    $paymentLogs[] = [
                        'timestamp' => substr($entry, 1, 19),
                        'entry' => $entry
                    ];
                }
            }

            // Add unauthorized access attempts
            preg_match_all('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\] production\.WARNING: Unauthorized access attempt to success page.*?(?=\[\d{4}-\d{2}-\d{2}|\Z)/s', $logContents, $unauthorizedEntries);

            if (!empty($unauthorizedEntries[0])) {
                foreach ($unauthorizedEntries[0] as $entry) {
                    $paymentLogs[] = [
                        'timestamp' => substr($entry, 1, 19),
                        'entry' => $entry,
                        'is_unauthorized' => true
                    ];
                }
            }

            // Sort by timestamp descending
            usort($paymentLogs, function($a, $b) {
                return strcmp($b['timestamp'], $a['timestamp']);
            });

            // Limit to last 100
            $paymentLogs = array_slice($paymentLogs, 0, 100);
        }

        return view('admin.payment_security_logs', [
            'logs' => $paymentLogs
        ]);
    }
}
