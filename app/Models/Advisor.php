<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advisor extends Authenticatable
{
    use Notifiable, SoftDeletes, HasFactory;

    /**
     * The guard name for this model.
     * This must match your authentication guard in the config/auth.php file.
     *
     * @var string
     */
    // protected $guard = 'advisor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'metric_number',
        'phone_number',
        'max_students',
        'male_count',
        'female_count',
        'profile_image',
        'program_id', 
        'profile_image', 
    ];

    /**
     * The name of the column for the "remember me" token.
     *
     * @var string
     */
    protected $rememberTokenName = 'remember_token';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array<int, string>
     */
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
    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = bcrypt($value);
    // }

    /**
     * Relationship with Students.
     * An advisor can have many students.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Relationship with Program.
     * An advisor belongs to one program.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Scope to filter advisors by program.
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
        : asset('images/default-profile.png'); // รูปภาพเริ่มต้น
}

}
