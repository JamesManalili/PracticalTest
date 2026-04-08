{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 -mt-16">
    <div class="max-w-md w-full">
        {{-- Logo & Title --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900">🔐</h1>
            <h2 class="mt-4 text-3xl font-bold text-gray-900">Welcome back</h2>
            <p class="mt-2 text-gray-600">Sign in to your account</p>
        </div>
        
        {{-- Login Form --}}
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6" id="loginForm">
                @csrf
                
                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email address
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                        placeholder="you@example.com"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="relative mt-1">
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            required
                            autocomplete="current-password"
                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Forgot password?
                    </a>
                </div>
                
                {{-- Submit Button --}}
                <button 
                    type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                >
                    Sign in
                </button>
            </form>
            
            {{-- Register Link --}}
            <p class="mt-6 text-center text-sm text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Create one
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
    
    // Client-side validation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        if (!email || !password) {
            e.preventDefault();
            alert('Please fill in all fields.');
        }
    });
</script>
@endpush
