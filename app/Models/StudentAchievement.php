<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAchievement extends Model
{
    protected $fillable = [
        'student_id',
        'event',
        'achievement',
        'level',
        'certificate',
    ];

    /**
     * Relationship to Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
