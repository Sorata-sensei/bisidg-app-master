<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use App\Models\CardCounseling;
class StudentsController extends Controller
{
    /**
     * Display a listing of the admin.students.
     */
    public function index(Request $request)
        {
            
            $counseling = CardCounseling::where('id_student',decrypt(session('student_id')))->count();
            return view('students.dashboard.index',compact('counseling'));
        }
    
    public function editDataIndex(Request $request)
    {
         $student = Student::with('dosenPA')->findOrFail(decrypt(session('student_id')));
         return view('students.personal.edit', compact('student'));
    }

    public function updateData(Request $request)
{
    $student = Student::findOrFail(decrypt(session('student_id')));

    $request->validate([
        'nama_orangtua' => 'nullable|string|max:255',
        'tanggal_lahir' => 'nullable|date',
        'alamat' => 'nullable|string',
        'no_telepon' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'ttd' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Update hanya kalau masih kosong
    if (empty($student->nama_orangtua) && $request->filled('nama_orangtua')) {
        $student->nama_orangtua = $request->nama_orangtua;
    }

    if (empty($student->tanggal_lahir) && $request->filled('tanggal_lahir')) {
        $student->tanggal_lahir = $request->tanggal_lahir;
    }

    if (empty($student->alamat) && $request->filled('alamat')) {
        $student->alamat = $request->alamat;
    }

    if (empty($student->no_telepon) && $request->filled('no_telepon')) {
        $student->no_telepon = $request->no_telepon;
    }

    if (empty($student->email) && $request->filled('email')) {
        $student->email = $request->email;
    }

    // Upload Foto (replace kalau ada file baru)
    if ($request->hasFile('foto')) {
        if ($student->foto && Storage::disk('public')->exists($student->foto)) {
            Storage::disk('public')->delete($student->foto);
        }

        $fotoPath = $request->file('foto')->storeAs(
            'students/foto/' . $student->id,
            'foto.' . $request->file('foto')->getClientOriginalExtension(),
            'public'
        );

        $student->foto = $fotoPath;
    }

    // Upload TTD (replace kalau ada file baru)
    if ($request->hasFile('ttd')) {
        if ($student->ttd && Storage::disk('public')->exists($student->ttd)) {
            Storage::disk('public')->delete($student->ttd);
        }

        $ttdPath = $request->file('ttd')->storeAs(
            'students/ttd/' . $student->id,
            'ttd.' . $request->file('ttd')->getClientOriginalExtension(),
            'public'
        );

        $student->ttd = $ttdPath;
    }

    $student->save();

    return redirect()
        ->route('student.personal.editDataIndex', $student->id)
        ->with('success', 'Data mahasiswa berhasil diperbarui.');
}



}