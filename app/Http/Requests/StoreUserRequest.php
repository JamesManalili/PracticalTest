<?php
// app/Http/Requests/StoreUserRequest.php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->isManager();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $allowedRoles = $this->getAllowedRoles();

        return [
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
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
            'role' => [
                'required',
                'string',
                'in:' . implode(',', array_keys($allowedRoles)),
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
                'max:2048', // 2MB max
            ],
            'is_active' => [
                'boolean',
            ],
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the user\'s full name.',
            'name.regex' => 'Name can only contain letters, spaces, hyphens, and apostrophes.',
            'email.required' => 'Please enter an email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Please create a password for this user.',
            'role.required' => 'Please select a role for this user.',
            'role.in' => 'Invalid role selected.',
            'phone.regex' => 'Please enter a valid phone number.',
            'avatar.max' => 'Avatar image must be less than 2MB.',
        ];
    }

    /**
     * Get allowed roles based on current user
     */
    protected function getAllowedRoles(): array
    {
        $roles = User::getRoles();

        if (Auth::user()->hasRole('manager')) {
            unset($roles['admin'], $roles['manager']);
        }

        return $roles;
    }
}
