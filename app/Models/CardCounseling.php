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
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student');
    }
     
}