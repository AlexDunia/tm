<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    /**
     * Show the form to request a password reset link
     */
    public function showForgotForm()
    {
        return view('forgotpassword');
    }

    /**
     * Send a password reset link to the given user
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No account found with this email address',
        ]);

        $token = Str::random(64);
        $email = $request->email;

        // Store the token in the password_resets table
        DB::table('password_resets')->where('email', $email)->delete();

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Generate the reset URL
        $resetUrl = url('reset-password/' . $token . '?email=' . urlencode($email));

        // Send the email
        Mail::send('emails.reset-password', ['resetUrl' => $resetUrl], function($message) use ($email) {
            $message->to($email)
                   ->subject('Reset Your Password');
        });

        return back()->with('status', 'Password reset link has been sent to your email');
    }

    /**
     * Show the password reset form
     */
    public function showResetForm(Request $request, $token)
    {
        return view('resetpassword', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Reset the user's password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
                            ->where([
                                'email' => $request->email,
                                'token' => $request->token
                            ])
                            ->first();

        if (!$updatePassword) {
            return back()->withErrors(['error' => 'Invalid token!']);
        }

        // Check if token is expired (60 minutes)
        $tokenCreatedAt = Carbon::parse($updatePassword->created_at);
        if (Carbon::now()->diffInMinutes($tokenCreatedAt) > 60) {
            return back()->withErrors(['error' => 'Token has expired!']);
        }

        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('/login')->with('status', 'Your password has been reset successfully!');
    }
}
