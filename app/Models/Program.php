<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type'];

    // ความสัมพันธ์กับ Student และ Advisor
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function advisors()
    {
        return $this->hasMany(Advisor::class);
    }
}
