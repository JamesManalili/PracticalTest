{{-- resources/views/users/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit User')
@section('header', 'Edit User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">
            Edit: {{ $user->name }}
            @if($user->id === auth()->id())
                <span class="text-sm font-normal text-blue-600">(Your Profile)</span>
            @endif
        </h2>
        
        <form method="POST" action="{{ route('users.update', $user) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $user->name) }}"
                    required
                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $user->email) }}"
                    required
                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Password (optional on edit) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        New Password <span class="text-gray-400">(leave blank to keep current)</span>
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation"
                        class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
            </div>
            
            {{-- Role (only if authorized to change) --}}
            @if(isset($roles) && count($roles) > 1)
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role *</label>
                <select 
                    id="role" 
                    name="role"
                    required
                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror"
                >
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endif
            
            {{-- Phone --}}
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="{{ old('phone', $user->phone) }}"
                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                >
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Address --}}
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea 
                    id="address" 
                    name="address"
                    rows="3"
                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror"
                >{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Active Status (only for managers/admins editing others) --}}
            @if(auth()->user()->isManager() && $user->id !== auth()->id())
            <div>
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        value="1"
                        {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    >
                    <span class="ml-2 text-sm text-gray-700">Active account</span>
                </label>
            </div>
            @endif
            
            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
