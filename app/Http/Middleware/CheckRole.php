<?php
// app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * 
     * Usage in routes: ->middleware('role:admin,manager')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();

        // Check if user has any of the required roles
        if (!$user->hasAnyRole($roles)) {
            // Log unauthorized access attempt
            \Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'required_roles' => $roles,
                'user_role' => $user->role,
                'path' => $request->path(),
                'ip' => $request->ip(),
            ]);

            abort(403, 'You do not have permission to access this resource.');
        }

        // Check if user account is active
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();

            return redirect()->route('login')
                ->with('error', 'Your account has been deactivated.');
        }

        return $next($request);
    }
}
