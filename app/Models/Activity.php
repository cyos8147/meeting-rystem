<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'advisor_id',
        'meeting_date',
        'discussion_content',
        'evidence',
        'status',
        'advisor_comment',
    ];

    protected $casts = [
        'evidence' => 'array', // ใช้สำหรับจัดการ JSON
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function advisor()
    {
        return $this->belongsTo(Advisor::class);
    }
}
