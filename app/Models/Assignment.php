<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    // Specify the table if it is different from the plural form of the model name
    // protected $table = 'assignments';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'student_id', 'advisor_id', 'assigned_at'
    ];

    // Define the relationship with the Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Define the relationship with the Advisor
    public function advisor()
    {
        return $this->belongsTo(Advisor::class);
    }
}
