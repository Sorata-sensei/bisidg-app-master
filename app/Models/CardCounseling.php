<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CardCounseling extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_student',
        'semester',
        'sks',
        'ip',
        'tanggal',
        'komentar',
        'failed_courses'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student');
    }
     protected $casts = [
        'failed_courses' => 'array', // otomatis decode JSON ke array
    ];

    public function failed_courses_objects()
    {
        return Course::whereIn('id', $this->failed_courses ?? [])->get();
    }
}