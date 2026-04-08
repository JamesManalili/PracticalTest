{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Welcome Card --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg p-6 text-white">
            <h2 class="text-2xl font-bold">Welcome back, {{ $user->name }}! 👋</h2>
            <p class="mt-2 text-blue-100">
                Last login: {{ $stats['lastLogin'] }}
            </p>
        </div>

        {{-- Stats Cards (for managers/admins) --}}
        @if($user->isManager())
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Total Users --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['totalUsers'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                {{-- Active Users --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Active Users</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['activeUsers'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                {{-- New This Month --}}
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">New This Month</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['newThisMonth'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Quick Actions & Recent Users --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('users.index') }}"
                        class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="ml-3 font-medium text-gray-700">View Users</span>
                    </a>

                    @if($user->isManager())
                        <a href="{{ route('users.create') }}"
                            class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span class="ml-3 font-medium text-gray-700">Add User</span>
                        </a>
                    @endif

                    <a href="{{ route('users.edit', $user) }}"
                        class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="ml-3 font-medium text-gray-700">Settings</span>
                    </a>

                    <a href="{{ route('users.show', $user) }}"
                        class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="ml-3 font-medium text-gray-700">My Profile</span>
                    </a>
                </div>
            </div>

            {{-- Recent Users (managers/admins only) --}}
            @if($user->isManager() && $recentUsers->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                        <a href="{{ route('users.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
                    </div>
                    <div class="space-y-4">
                        @foreach($recentUsers as $recentUser)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium">
                                        {{ substr($recentUser->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $recentUser->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $recentUser->email }}</p>
                                    </div>
                                </div>
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full {{ $recentUser->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $recentUser->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection