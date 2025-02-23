<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Importing the Authenticatable class

class Admin extends Authenticatable
{
    // // Specify the guard for this model
    // protected $guard = 'admin'; // This should match your auth guard name

    // Define the attributes that are mass assignable
    protected $fillable = ['username', 'password', 'email'];

    // For the remember_token column used in authentication
    protected $rememberTokenName = 'remember_token';

    // Optionally, you can define any additional fields that should be cast
    protected $casts = [
        'email_verified_at' => 'datetime', // Example if you use email verification
    ];
}
