<?php
// app/Http/Requests/UpdateUserRequest.php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');
        $currentUser = Auth::user();

        // Users can update themselves
        if ($currentUser->id === $user->id) {
            return true;
        }

        // Managers can update non-admin users
        return $currentUser->isManager();
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $currentUser = Auth::user();
        $isEditingSelf = $currentUser->id === $user->id;

        $rules = [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[\pL\s\-\']+$/u',
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\d\s\-\+\(\)]+$/',
            ],
            'address' => [
                'nullable',
                'string',
                'max:500',
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
            ],
        ];

        // Password is optional on update
        if ($this->filled('password')) {
            $rules['password'] = [
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ];
        }

        // Role and status can only be changed by managers/admins editing others
        if (!$isEditingSelf && $currentUser->isManager()) {
            $allowedRoles = $this->getAllowedRoles();

            $rules['role'] = [
                'required',
                'string',
                'in:' . implode(',', array_keys($allowedRoles)),
            ];

            $rules['is_active'] = ['boolean'];
        }

        return $rules;
    }

    protected function getAllowedRoles(): array
    {
        $roles = User::getRoles();

        if (Auth::user()->hasRole('manager')) {
            unset($roles['admin'], $roles['manager']);
        }

        return $roles;
    }
}
