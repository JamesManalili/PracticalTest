{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Secure Auth System') }} - @yield('title', 'Dashboard')</title>

        {{-- Tailwind CSS via CDN (use Vite in production) --}}
        <script src="[cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>

        {{-- Alpine.js for interactivity --}}
        <script defer src="[cdn.jsdelivr.net](https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js)"></script>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @stack('styles')
    </head>

    <body class="bg-gray-100 min-h-screen">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

            {{-- Sidebar --}}
            @auth
                <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                    class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto">
                    {{-- Logo --}}
                    <div class="flex items-center justify-between h-16 px-6 bg-gray-800">
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold">
                            🔐 SecureAuth
                        </a>
                        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Navigation --}}
                    <nav class="mt-6 px-4">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('users.index') }}"
                            class="flex items-center px-4 py-3 mt-2 rounded-lg {{ request()->routeIs('users*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Users
                        </a>

                        @if(auth()->user()->isAdmin())
                            <div class="mt-8 pt-4 border-t border-gray-700">
                                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin</p>
                                {{-- Admin-specific menu items --}}
                            </div>
                        @endif
                    </nav>

                    {{-- User Info --}}
                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gray-800">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center text-white font-medium">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate capitalize">{{ auth()->user()->role }}</p>
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- Sidebar Overlay --}}
                <div x-show="sidebarOpen" @click="sidebarOpen = false"
                    class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" x-cloak></div>
            @endauth

            {{-- Main Content --}}
            <div class="flex-1 flex flex-col overflow-hidden">
                {{-- Top Header --}}
                @auth
                    <header class="bg-white shadow-sm border-b border-gray-200">
                        <div class="flex items-center justify-between h-16 px-4 sm:px-6">
                            {{-- Mobile menu button --}}
                            <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>

                            <h1 class="text-lg font-semibold text-gray-800 hidden sm:block">
                                @yield('header', 'Dashboard')
                            </h1>

                            {{-- User Menu --}}
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                    class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                    <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50" x-cloak>
                                    <a href="{{ route('users.show', auth()->user()) }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        My Profile
                                    </a>
                                    <a href="{{ route('users.edit', auth()->user()) }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        Settings
                                    </a>
                                    <hr class="my-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </header>
                @endauth

                {{-- Page Content --}}
                <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                            x-transition
                            class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between">
                            <span>{{ session('success') }}</span>
                            <button @click="show = false" class="text-green-700 hover:text-green-900">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('error') || $errors->has('error'))
                        <div x-data="{ show: true }" x-show="show"
                            class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center justify-between">
                            <span>{{ session('error') ?? $errors->first('error') }}</span>
                            <button @click="show = false" class="text-red-700 hover:text-red-900">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>

</html>