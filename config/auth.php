<?php

return [

    /*
    |---------------------------------------------------------------------------
    | Authentication Defaults
    |---------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'), // You can set the default guard in .env
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |---------------------------------------------------------------------------
    | Authentication Guards
    |---------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | We have added custom guards for 'admin', 'student', and 'advisor'.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins', // Custom provider for admin
        ],

        'student' => [
            'driver' => 'session',
            'provider' => 'students', // Custom provider for students
        ],

        'advisor' => [
            'driver' => 'session',
            'provider' => 'advisors', // Custom provider for advisors
        ],
    ],

    /*
    |---------------------------------------------------------------------------
    | User Providers
    |---------------------------------------------------------------------------
    |
    | This section defines how the users are actually retrieved out of your
    | database or other storage systems used by the application. We define
    | custom providers for 'admins', 'students', and 'advisors'.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class, // Custom model for admin
        ],

        'students' => [
            'driver' => 'eloquent',
            'model' => App\Models\Student::class, // Custom model for student
        ],

        'advisors' => [
            'driver' => 'eloquent',
            'model' => App\Models\Advisor::class, // Custom model for advisor
        ],
    ],

    /*
    |---------------------------------------------------------------------------
    | Resetting Passwords
    |---------------------------------------------------------------------------
    |
    | These configuration options specify the behavior of Laravel's password
    | reset functionality, including the table utilized for token storage
    | and the user provider that is invoked to retrieve users.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'admins' => [
            'provider' => 'admins', // Admin password reset provider
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'students' => [
            'provider' => 'students', // Student password reset provider
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'advisors' => [
            'provider' => 'advisors', // Advisor password reset provider
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |---------------------------------------------------------------------------
    | Password Confirmation Timeout
    |---------------------------------------------------------------------------
    |
    | This option defines the amount of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];
