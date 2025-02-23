<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Authenticatable
{
    use Notifiable, SoftDeletes, HasFactory;

    protected $fillable = [
        'metric_number',
        'name',
        'gender',
        'race', // Added race field
        'semester',
        'advisor_id',
        'password',
        'program_id',
        'phone_number',
        'email',
        'profile_image',
    ];

    protected $rememberTokenName = 'remember_token';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mutator for setting the password.
     * Automatically hashes the password when set.
     *
     * @param string $value
     * @return void
     */
    // Uncomment if automatic password hashing is needed
    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = bcrypt($value);
    // }

    /**
     * Relationship with Advisor.
     * A student belongs to one advisor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function advisor()
    {
        return $this->belongsTo(Advisor::class);
    }

    /**
     * Relationship with Activities.
     * A student can have many activities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Relationship with Program.
     * A student belongs to one program.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Scope to filter students by program.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $program
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByProgram($query, $program)
    {
        return $query->whereHas('program', function ($q) use ($program) {
            $q->where('name', $program);
        });
    }

    /**
     * Accessor for the full profile image URL.
     *
     * @return string|null
     */
    public function getProfileImageUrlAttribute()
    {
        return $this->profile_image 
            ? asset('storage/' . $this->profile_image) 
            : null;
    }
}
