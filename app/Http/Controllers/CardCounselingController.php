<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\CardCounseling;
use Illuminate\Http\Request;

class CardCounselingController extends Controller
{public function show($id_student)
{
    // 1️⃣ Ambil data mahasiswa beserta dosen PA-nya
    $student = Student::with('dosenPA')->findOrFail($id_student);

    // 2️⃣ Pastikan mahasiswa yang login memang yang diminta
    if ($student->id !== session('student_id')) {
        return redirect()->back()
            ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }

    // 3️⃣ Validasi kelengkapan data (foto dan tanda tangan)
    if ($student->ttd == null || $student->foto == null) {
        return redirect()->route('student.personal.editDataIndex', $student->id)
            ->with('error', 'Silakan lengkapi data pribadi Anda (foto dan tanda tangan) sebelum mengakses layanan konsultasi akademik.');
    }

    // 4️⃣ Ambil semua riwayat counseling (bukan JSON lagi, tapi multiple rows)
    $history = $student->counselings()->orderBy('tanggal')->get();

    // 5️⃣ Kirim ke view
    return view('students.counseling.add_form_student', compact('student', 'history'));
}



      public function store(Request $request, $id_student)
    {
        $student = Student::findOrFail($id_student);

        $request->validate([
            'semester' => 'required|numeric',
            'sks'      => 'required|numeric',
            'ip'       => 'nullable',
            'tanggal'  => 'required|date',
            'komentar' => 'nullable|string',
        ]);

        
        $student->is_counseling = '0'; 
        $student->save();
        // Simpan record baru
        CardCounseling::create([
            'id_student' => $id_student,
            'semester'   => $request->semester,
            'sks'        => $request->sks,
            'ip'         => $request->ip,
            'tanggal'    => $request->tanggal,
            'komentar'   => $request->komentar,
        ]);

        return redirect()
            ->route('student.counseling.show', $id_student)
            ->with('success', 'Data konsultasi berhasil ditambahkan');
    }

}