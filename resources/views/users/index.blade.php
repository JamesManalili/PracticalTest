{{-- resources/views/users/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Users')
@section('header', 'User Management')

@section('content')
    <div class="space-y-6">
        {{-- Header with Create Button --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Users</h2>
                <p class="mt-1 text-sm text-gray-500">Manage user accounts and permissions</p>
            </div>

            @if(auth()->user()->isManager())
                <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add User
                </a>
            @endif
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form method="GET" action="{{ route('users.index') }}" class="flex flex-col sm:flex-row gap-4">
                {{-- Search --}}
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search by name, email, or phone..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>

                {{-- Role Filter --}}
                <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Roles</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ ($filters['role'] ?? '') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                {{-- Status Filter --}}
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="1" {{ ($filters['status'] ?? '') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ ($filters['status'] ?? '') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                    Filter
                </button>

                @if(array_filter($filters))
                    <a href="{{ route('users.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Users Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Last Login
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $user->name }}
                                                @if($user->id === auth()->id())
                                                    <span class="ml-1 text-xs text-blue-600">(You)</span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $user->role === 'manager' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $user->role === 'user' ? 'bg-gray-100 text-gray-800' : '' }}
                                    ">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->last_login_at?->diffForHumans() ?? 'Never' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        {{-- View --}}
                                        <a href="{{ route('users.show', $user) }}" class="text-gray-600 hover:text-gray-900" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        {{-- Toggle Status (managers/admins, not self) --}}
                                        @if(auth()->user()->isManager() && $user->id !== auth()->id())
                                            <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="{{ $user->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}" title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        @if($user->is_active)
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        @endif
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Delete (admins only, not self) --}}
                                        @if(auth()->user()->isAdmin() && $user->id !== auth()->id())
                                            <button 
                                                type="button"
                                                onclick="confirmDelete({{ $user->id }}, '{{ e($user->name) }}')"
                                                class="text-red-600 hover:text-red-900"
                                                title="Delete"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        @if(array_filter($filters))
                                            Try adjusting your search or filter criteria.
                                        @else
                                            Get started by creating a new user.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeDeleteModal()"></div>

            <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>

                <div class="mt-4 text-center">
                    <h3 class="text-lg font-medium text-gray-900">Delete User</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Are you sure you want to delete <span id="deleteUserName" class="font-semibold"></span>? This action cannot be undone.
                    </p>
                </div>

                <div class="mt-6 flex justify-center space-x-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="button" onclick="submitDelete()" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let deleteUserId = null;

        function confirmDelete(userId, userName) {
            deleteUserId = userId;
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteUserId = null;
        }

        function submitDelete() {
            if (deleteUserId) {
                document.getElementById('delete-form-' + deleteUserId).submit();
            }
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
@endpush
