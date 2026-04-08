<?php
// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Maximum login attempts before lockout
     */
    protected int $maxAttempts = 5;

    /**
     * Lockout duration in seconds
     */
    protected int $decaySeconds = 300; // 5 minutes

    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request with rate limiting and security checks
     */
    public function login(Request $request)
    {
        // Validate input - server-side validation is critical
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:150'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        // Check rate limiting to prevent brute force attacks
        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => [
                    "Too many login attempts. Please try again in {$seconds} seconds."
                ],
            ])->status(429);
        }

        // Attempt authentication
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Clear rate limiter on successful login
            RateLimiter::clear($throttleKey);

            // Regenerate session ID to prevent session fixation attacks
            $request->session()->regenerate();

            // Update user's last login info
            $user = Auth::user();

            // Check if user account is active
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();

                throw ValidationException::withMessages([
                    'email' => ['Your account has been deactivated. Please contact support.'],
                ]);
            }

            // Record login details for security auditing
            $user->updateLastLogin($request->ip());

            // Flash success message
            session()->flash('success', "Welcome back, {$user->name}!");

            // Redirect to intended page or dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Increment rate limiter on failed attempt
        RateLimiter::hit($throttleKey, $this->decaySeconds);

        // Generic error message to prevent user enumeration
        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Generate throttle key for rate limiting
     * Uses email + IP to prevent distributed attacks
     */
    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}
