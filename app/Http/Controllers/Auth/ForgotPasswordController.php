<?php
// app/Http/Controllers/Auth/ForgotPasswordController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ]);

        // Rate limiting for password reset requests
        $throttleKey = 'password-reset:' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Please wait {$seconds} seconds before requesting another reset link.",
            ]);
        }

        RateLimiter::hit($throttleKey, 300); // 5 minute window

        // Send the reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Always show success message to prevent user enumeration
        // Even if email doesn't exist, we show the same message
        return back()->with(
            'status',
            'If an account exists with that email, you will receive a password reset link shortly.'
        );
    }
}
