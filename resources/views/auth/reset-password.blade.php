{{-- resources/views/auth/reset-password.blade.php --}}
@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 -mt-16">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900">🔐</h1>
                <h2 class="mt-4 text-3xl font-bold text-gray-900">Set new password</h2>
                <p class="mt-2 text-gray-600">Create a strong password for your account</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" id="password" name="password" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                            Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection