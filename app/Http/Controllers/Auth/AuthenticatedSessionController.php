<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stevebauman\Location\Facades\Location;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        // If redirected here because of session timeout, add a message
        $message = session('auth_timeout') ? 'Your session has expired. Please log in again.' : null;

        return view('Login', ['timeout_message' => $message]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {
            // Authenticate the user
            $request->authenticate();

            // Update security tracking after login
            $this->handleSuccessfulLogin($request);

            return redirect()->intended('/');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('auth_error', [
                    'type' => 'invalid_credentials',
                    'message' => $e->getMessage(),
                    'resolution' => 'Please check your email and password and try again.'
                ]);
        }
    }

    /**
     * Handle tasks after successful login.
     */
    private function handleSuccessfulLogin(Request $request)
    {
        $user = Auth::user();

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
                ], function ($message) use ($user) {
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
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'You have been logged out successfully.');
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
}
