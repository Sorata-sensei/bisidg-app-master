<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama_lengkap',
        'nim',
        'password',
        'email',
        'angkatan',
        'program_studi',
        'fakultas',
        'jenis_kelamin',
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
        'id_lecturer',
        'foto',
        'ttd',
        'nama_orangtua',
    ];

    protected $hidden = [
        'password',
    ];
    protected $casts = [
        'is_edited' => 'boolean',
    ];

    public function dosenPA()
    {
        return $this->belongsTo(User::class, 'id_lecturer');
    }

    public function counselings()
    {
        return $this->hasMany(CardCounseling::class, 'id_student');
    }
}