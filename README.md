# Secure PHP Authentication & CRUD System

A secure Laravel 11 application featuring user authentication, role-based access control, and full CRUD functionality for user management.

## Features

### Authentication

* User registration with strong password validation
* Secure login with rate limiting (5 attempts, 5-minute lockout)
* Session-based authentication with secure cookie handling
* Password reset via email
* Remember me functionality
* Automatic session regeneration (prevents fixation attacks)

### Security

* CSRF protection on all forms
* XSS prevention (input sanitization and output escaping via Blade)
* SQL injection prevention (Eloquent ORM / prepared statements)
* Password hashing with bcrypt (password_hash / password_verify)
* Secure session configuration (HTTP-only, secure cookies, SameSite)
* Rate limiting on login and password reset
* Input validation (server-side with FormRequest, client-side JavaScript)

### User Management (CRUD)

* Create new users (managers/admins only)
* View user list with search, filtering, and pagination
* Update user information
* Soft delete users with confirmation
* Toggle user active/inactive status
* Role-based access control (Admin, Manager, User)

### Additional Features

* CSRF protection (Laravel default)
* Password reset via email
* Role-based access control
* Last login tracking
* Password strength validation
* Soft deletes for data recovery

## Requirements

* PHP 8.2+
* Composer
* MySQL 8.0+ or MariaDB 10.4+
* Node.js and NPM (optional, for asset compilation)

## Installation

1. Clone the repository

   ```bash
   git clone https://github.com/JamesManalili/PracticalTest
   cd PracticalTest
   ```

2. Install dependencies

   ```bash
   composer install
   ```

3. Copy environment file

   ```bash
   cp .env.example .env
   ```

4. Configure your database in `.env`

5. Generate application key

   ```bash
   php artisan key:generate
   ```

6. Run migrations

   ```bash
   php artisan migrate
   ```

7. Start the server

   ```bash
   php artisan serve
   ```

## Overview

This system demonstrates secure implementation of authentication and user management using Laravel best practices. It includes proper validation, protection against common web vulnerabilities, and structured role-based access control.
