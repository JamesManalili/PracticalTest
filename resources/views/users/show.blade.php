{{-- resources/views/users/show.blade.php --}}
@extends('layouts.app')

@section('title', $user->name)
@section('header', 'User Profile')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8">
                <div class="flex items-center">
                    <div
                        class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center text-white text-3xl font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="ml-6">
                        <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                        <p class="text-blue-100">{{ $user->email }}</p>
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-white/20 text-white capitalize">
                                {{ $user->role }}
                            </span>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Details --}}
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->address ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F j, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $user->last_login_at?->format('F j, Y g:i A') ?? 'Never' }}
                            @if($user->last_login_ip)
                                <span class="text-gray-400">({{ $user->last_login_ip }})</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                {{-- Actions --}}
                <div class="mt-6 pt-6 border-t border-gray-200 flex items-center justify-end space-x-3">
                    <a href="{{ route('users.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Back to Users
                    </a>
                    <a href="{{ route('users.edit', $user) }}"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection