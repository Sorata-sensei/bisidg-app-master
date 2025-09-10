<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'id_lecturer',
        'nama_lengkap',
        'nama_orangtua',
        'foto',
        'ttd',
        'nim',
        'angkatan',
        'program_studi',
        'fakultas',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'no_telepon',
        'email',
        'status_mahasiswa',
        'tanggal_masuk',
        'tanggal_lulus',
        'notes',
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