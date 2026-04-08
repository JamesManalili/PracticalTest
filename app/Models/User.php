<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Role constants for type-safe role checking
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     * Explicitly listing fields prevents mass assignment vulnerabilities
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'avatar',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * Prevents sensitive data from appearing in JSON/array outputs
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     * Ensures proper type handling for these fields
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ automatic hashing
        'is_active' => 'boolean',
    ];

    /**
     * Get available roles for dropdown/validation
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_USER => 'User',
        ];
    }

    /**
     * Check if user has admin role
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user has manager role or higher
     */
    public function isManager(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_MANAGER]);
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Scope to filter active users only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by role
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Update last login information
     */
    public function updateLastLogin(string $ipAddress): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress,
        ]);
    }

    /**
     * Get the user's display name with role badge
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->role})";
    }
}
