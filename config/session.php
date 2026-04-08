<?php
// config/session.php (key security settings)

return [
    /*
     * Session driver - using database for better security and scalability
     * Database driver allows session management across multiple servers
     */
    'driver' => env('SESSION_DRIVER', 'database'),

    /*
     * Session lifetime in minutes
     * 120 minutes (2 hours) is a reasonable balance between security and UX
     */
    'lifetime' => env('SESSION_LIFETIME', 120),

    /*
     * Expire session on browser close
     * Set to true for high-security applications
     */
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
     * Encrypt session data
     * Adds extra layer of security for sensitive session data
     */
    'encrypt' => true,

    /*
     * Session cookie settings
     */
    'cookie' => env('SESSION_COOKIE', 'secure_auth_session'),

    /*
     * Cookie path - restrict to application path
     */
    'path' => '/',

    /*
     * Cookie domain - null means current domain only
     */
    'domain' => env('SESSION_DOMAIN'),

    /*
     * HTTPS only cookies
     * CRITICAL: Set to true in production
     */
    'secure' => env('SESSION_SECURE_COOKIE', true),

    /*
     * HTTP only - prevents JavaScript access to cookies
     * CRITICAL: Always keep true to prevent XSS cookie theft
     */
    'http_only' => true,

    /*
     * SameSite cookie attribute
     * 'lax' provides good CSRF protection while allowing normal navigation
     * 'strict' for maximum security (may break some OAuth flows)
     */
    'same_site' => 'lax',
];
