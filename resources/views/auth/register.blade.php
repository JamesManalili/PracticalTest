{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 -mt-16">
        <div class="max-w-md w-full">
            {{-- Logo & Title --}}
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900">🔐</h1>
                <h2 class="mt-4 text-3xl font-bold text-gray-900">Create account</h2>
                <p class="mt-2 text-gray-600">Join us today</p>
            </div>

            {{-- Registration Form --}}
            <div class="bg-white rounded-xl shadow-lg p-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-6" id="registerForm">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Full name
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name"
                            autofocus
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="John Doe">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email address
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                            placeholder="you@example.com">
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
                            <input type="password" id="password" name="password" required autocomplete="new-password"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                                placeholder="••••••••" onkeyup="checkPasswordStrength(this.value)">
                        </div>
                        {{-- Password Strength Indicator --}}
                        <div class="mt-2">
                            <div class="flex gap-1">
                                <div id="strength-1" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                                <div id="strength-2" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                                <div id="strength-3" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                                <div id="strength-4" class="h-1 w-1/4 bg-gray-200 rounded"></div>
                            </div>
                            <p id="strength-text" class="mt-1 text-xs text-gray-500"></p>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <ul class="mt-2 text-xs text-gray-500 space-y-1">
                            <li id="req-length" class="flex items-center">
                                <span class="mr-1">○</span> At least 8 characters
                            </li>
                            <li id="req-upper" class="flex items-center">
                                <span class="mr-1">○</span> Uppercase letter
                            </li>
                            <li id="req-lower" class="flex items-center">
                                <span class="mr-1">○</span> Lowercase letter
                            </li>
                            <li id="req-number" class="flex items-center">
                                <span class="mr-1">○</span> Number
                            </li>
                            <li id="req-special" class="flex items-center">
                                <span class="mr-1">○</span> Special character
                            </li>
                        </ul>
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirm password
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            autocomplete="new-password"
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="••••••••">
                    </div>

                    {{-- Terms --}}
                    <div>
                        <label class="flex items-start">
                            <input type="checkbox" name="terms" required
                                class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 @error('terms') border-red-500 @enderror">
                            <span class="ml-2 text-sm text-gray-600">
                                I agree to the
                                <a href="#" class="text-blue-600 hover:underline">Terms of Service</a>
                                and
                                <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                            </span>
                        </label>
                        @error('terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Create account
                    </button>
                </form>

                {{-- Login Link --}}
                <p class="mt-6 text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Password strength checker with visual feedback
        function checkPasswordStrength(password) {
            let strength = 0;
            const requirements = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };

            // Update requirement indicators
            Object.keys(requirements).forEach(req => {
                const el = document.getElementById('req-' + req);
                if (requirements[req]) {
                    el.classList.add('text-green-600');
                    el.querySelector('span').textContent = '✓';
                    strength++;
                } else {
                    el.classList.remove('text-green-600');
                    el.querySelector('span').textContent = '○';
                }
            });

            // Update strength bars
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-400', 'bg-green-600'];
            const texts = ['Very weak', 'Weak', 'Fair', 'Strong', 'Very strong'];

            for (let i = 1; i <= 4; i++) {
                const bar = document.getElementById('strength-' + i);
                bar.className = 'h-1 w-1/4 rounded ' + (i <= strength ? colors[strength - 1] : 'bg-gray-200');
            }

            document.getElementById('strength-text').textContent = password ? texts[strength - 1] || '' : '';
        }
    </script>
@endpush