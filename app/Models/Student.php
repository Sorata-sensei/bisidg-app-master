<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Constants
    |--------------------------------------------------------------------------
    */
    const STATUS_ACTIVE   = 'Aktif';
    const DEFAULT_PROGRAM = 'Bisnis Digital';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'id_lecturer',
        'nama_lengkap',
        'nim',
        'nik',
        'nisn',
        'password',
        'email',
        'angkatan',
        'program_studi',
        'fakultas',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'alamat_lat',
        'alamat_lng',
        'no_telepon',
        'status_mahasiswa',
        'tanggal_masuk',
        'tanggal_lulus',
        'is_counseling',
        'tanggal_counseling',
        'notes',
        'foto',
        'ttd',
        'nama_orangtua',
        'nama_ibu_kandung',
        'is_edited',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden & Casts
    |--------------------------------------------------------------------------
    */
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'is_counseling' => 'boolean',
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'datetime',
        'tanggal_lulus' => 'datetime',
        'tanggal_counseling' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function dosenPA()
    {
        return $this->belongsTo(User::class, 'id_lecturer', 'id');
    }

    public function counselings()
    {
        return $this->hasMany(CardCounseling::class, 'id_student', 'id');
    }

    public function achievements()
    {
        return $this->hasMany(StudentAchievement::class, 'student_id', 'id');
    }

    public function finalProject()
    {
        return $this->hasOne(\App\Models\FinalProject::class, 'student_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */
    public function scopeByLecturer($query, $lecturerId)
    {
        return $query->where('id_lecturer', $lecturerId);
    }

    public function scopeByBatch($query, $batch)
    {
        return $query->where('angkatan', $batch);
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('angkatan', 'like', "%{$search}%");
            });
        }
        return $query;
    }
}