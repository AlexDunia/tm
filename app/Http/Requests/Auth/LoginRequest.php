<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Use consistent generic error to prevent account enumeration
        $errorMessage = 'Sign-in unsuccessful. Please verify your information.';

        // First check if user exists before attempting login
        // This is for security logging but response will be generic
        $user = User::where('email', $this->input('email'))->first();

        if (!$user) {
            // Log the failed attempt for security monitoring without revealing to user
            Log::warning('Login attempt with non-existent email', [
                'email' => $this->input('email'),
                'ip' => $this->ip(),
                'user_agent' => $this->userAgent()
            ]);

            // Track non-existent account attempts in session
            $nonExistentAttempts = $this->session()->get('non_existent_attempts', 0) + 1;
            $this->session()->put('non_existent_attempts', $nonExistentAttempts);

            // Increment rate limiter
            RateLimiter::hit($this->throttleKey());

            // Throw a generic error that doesn't reveal if the email exists
            throw ValidationException::withMessages([
                'email' => [$errorMessage],
            ]);
        }

        // Clear the non-existent account counter
        $this->session()->forget('non_existent_attempts');

        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Increment the rate limiter
            RateLimiter::hit($this->throttleKey());

            // Same generic error for wrong password
            throw ValidationException::withMessages([
                'email' => [$errorMessage],
            ]);
        }

        // On successful login, regenerate the session ID to prevent session fixation
        $this->session()->regenerate();

        // Reset rate limiter on successful login
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ])->status(429);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
