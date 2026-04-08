<?php
// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request with comprehensive validation
     */
    public function register(Request $request)
    {
        // Comprehensive validation with strong password rules
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[\pL\s\-\']+$/u', // Letters, spaces, hyphens, apostrophes only
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns', // Strict email validation
                'max:150',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()      // Requires uppercase and lowercase
                    ->numbers()         // Requires at least one number
                    ->symbols()         // Requires at least one special character
                    ->uncompromised(3), // Check against breached password databases
            ],
            'terms' => ['required', 'accepted'],
        ], [
            'name.required' => 'Please enter your full name.',
            'name.regex' => 'Name can only contain letters, spaces, hyphens, and apostrophes.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'An account with this email already exists.',
            'password.required' => 'Please create a password.',
            'password.confirmed' => 'Password confirmation does not match.',
            'terms.accepted' => 'You must accept the terms and conditions.',
        ]);

        try {
            // Use database transaction for data integrity
            $user = DB::transaction(function () use ($validated, $request) {
                // Create the user - password is automatically hashed via model cast
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => strtolower($validated['email']), // Normalize email
                    'password' => $validated['password'],
                    'role' => User::ROLE_USER, // Default role
                    'last_login_at' => now(),
                    'last_login_ip' => $request->ip(),
                ]);

                return $user;
            });

            // Log the successful registration
            Log::info('New user registered', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);

            // Automatically log in the user
            Auth::login($user);

            // Regenerate session to prevent fixation
            $request->session()->regenerate();

            return redirect()->route('dashboard')
                ->with('success', 'Welcome! Your account has been created successfully.');

        } catch (\Exception $e) {
            // Log the error without exposing details to user
            Log::error('Registration failed', [
                'email' => $validated['email'],
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'Registration failed. Please try again later.']);
        }
    }
}
