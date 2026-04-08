<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display paginated list of users with search and filters
     */
    public function index(Request $request)
    {
        // Build query with eager loading prevention of N+1
        $query = User::query();

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('is_active', $request->boolean('status'));
        }

        // Sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        // Whitelist allowed sort fields to prevent SQL injection
        $allowedSorts = ['name', 'email', 'role', 'created_at', 'last_login_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        // Paginate results
        $users = $query->paginate(15)->withQueryString();

        return view('users.index', [
            'users' => $users,
            'roles' => User::getRoles(),
            'filters' => $request->only(['search', 'role', 'status', 'sort', 'direction']),
        ]);
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Only admins and managers can create users
        $this->authorizeRole(['admin', 'manager']);

        return view('users.create', [
            'roles' => $this->getAllowedRolesForCreation(),
        ]);
    }

    /**
     * Store a newly created user
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorizeRole(['admin', 'manager']);

        try {
            $user = DB::transaction(function () use ($request) {
                $data = $request->validated();

                // Handle avatar upload if present
                if ($request->hasFile('avatar')) {
                    $data['avatar'] = $request->file('avatar')
                        ->store('avatars', 'public');
                }

                return User::create($data);
            });

            Log::info('User created', [
                'created_by' => Auth::id(),
                'user_id' => $user->id,
            ]);

            return redirect()->route('users.index')
                ->with('success', "User '{$user->name}' created successfully.");

        } catch (\Exception $e) {
            Log::error('User creation failed', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create user. Please try again.']);
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Users can view their own profile, managers can view users, admins can view all
        $this->authorizeViewUser($user);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing a user
     */
    public function edit(User $user)
    {
        $this->authorizeEditUser($user);

        return view('users.edit', [
            'user' => $user,
            'roles' => $this->getAllowedRolesForEdit($user),
        ]);
    }

    /**
     * Update the specified user
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorizeEditUser($user);

        try {
            DB::transaction(function () use ($request, $user) {
                $data = $request->validated();

                // Handle avatar upload
                if ($request->hasFile('avatar')) {
                    // Delete old avatar if exists
                    if ($user->avatar) {
                        \Storage::disk('public')->delete($user->avatar);
                    }
                    $data['avatar'] = $request->file('avatar')
                        ->store('avatars', 'public');
                }

                // Remove password if not being updated
                if (empty($data['password'])) {
                    unset($data['password']);
                }

                $user->update($data);
            });

            Log::info('User updated', [
                'updated_by' => Auth::id(),
                'user_id' => $user->id,
            ]);

            return redirect()->route('users.index')
                ->with('success', "User '{$user->name}' updated successfully.");

        } catch (\Exception $e) {
            Log::error('User update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update user. Please try again.']);
        }
    }

    /**
     * Remove the specified user (soft delete)
     */
    public function destroy(User $user)
    {
        // Only admins can delete users
        $this->authorizeRole(['admin']);

        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        // Prevent deleting the last admin
        if ($user->isAdmin() && User::role('admin')->count() <= 1) {
            return back()->withErrors(['error' => 'Cannot delete the last administrator.']);
        }

        try {
            $userName = $user->name;
            $user->delete(); // Soft delete

            Log::info('User deleted', [
                'deleted_by' => Auth::id(),
                'user_id' => $user->id,
            ]);

            return redirect()->route('users.index')
                ->with('success', "User '{$userName}' deleted successfully.");

        } catch (\Exception $e) {
            Log::error('User deletion failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Failed to delete user. Please try again.']);
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        $this->authorizeRole(['admin', 'manager']);

        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot deactivate your own account.']);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "User '{$user->name}' has been {$status}.");
    }

    /**
     * Check if current user has required role
     */
    protected function authorizeRole(array $roles): void
    {
        if (!Auth::user()->hasAnyRole($roles)) {
            abort(403, 'You do not have permission to perform this action.');
        }
    }

    /**
     * Check if current user can view target user
     */
    protected function authorizeViewUser(User $user): void
    {
        $currentUser = Auth::user();

        // Users can always view themselves
        if ($currentUser->id === $user->id) {
            return;
        }

        // Managers and admins can view others
        if (!$currentUser->isManager()) {
            abort(403, 'You do not have permission to view this user.');
        }
    }

    /**
     * Check if current user can edit target user
     */
    protected function authorizeEditUser(User $user): void
    {
        $currentUser = Auth::user();

        // Users can edit their own profile
        if ($currentUser->id === $user->id) {
            return;
        }

        // Only managers and admins can edit others
        if (!$currentUser->isManager()) {
            abort(403, 'You do not have permission to edit this user.');
        }

        // Managers cannot edit admins
        if ($currentUser->hasRole('manager') && $user->isAdmin()) {
            abort(403, 'You cannot edit administrator accounts.');
        }
    }

    /**
     * Get roles available for creation based on current user's role
     */
    protected function getAllowedRolesForCreation(): array
    {
        $currentUser = Auth::user();
        $roles = User::getRoles();

        // Managers can only create users
        if ($currentUser->hasRole('manager')) {
            unset($roles['admin'], $roles['manager']);
        }

        return $roles;
    }

    /**
     * Get roles available for editing based on current user and target user
     */
    protected function getAllowedRolesForEdit(User $user): array
    {
        $currentUser = Auth::user();

        // Users editing themselves can't change their role
        if ($currentUser->id === $user->id) {
            return [$user->role => User::getRoles()[$user->role]];
        }

        return $this->getAllowedRolesForCreation();
    }
}
