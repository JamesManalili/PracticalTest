{{-- resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 -mt-16">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900">🔑</h1>
                <h2 class="mt-4 text-3xl font-bold text-gray-900">Reset password</h2>
                <p class="mt-2 text-gray-600">Enter your email to receive a reset link</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                @if(session('status'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                            placeholder="you@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Send Reset Link
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-600">
                    Remember your password?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">Sign in</a>
                </p>
            </div>
        </div>
    </div>
@endsection