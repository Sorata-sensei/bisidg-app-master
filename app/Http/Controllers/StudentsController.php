<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use App\Models\CardCounseling;
use Illuminate\Support\Facades\Auth;
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
         $isDefaultPassword = \Hash::check('Bisdig2025', $student->password);
         return view('students.personal.edit', compact('student','isDefaultPassword'));
    }

    public function updateData(Request $request)
{
    $student = Student::findOrFail(decrypt(session('student_id')));

   $request->validate([
    'nama_orangtua' => 'nullable|string|max:255',
    'tanggal_lahir' => 'nullable|date',
    'password' => 'nullable|string|min:8',
    'alamat'        => 'nullable|string',
    'no_telepon'    => 'nullable|string|max:20',
    'email'         => 'nullable|email|max:255',
    'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    'ttd'           => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
], [
    // Custom messages
    'password.min'        => 'Password must be at least 8 characters.',
    'password.max'        => 'Password cannot be longer than 20 characters.',
    'password.string'     => 'Password must be a valid text.',
    'email.email'         => 'Please enter a valid email address.',
    'foto.image'          => 'The photo must be an image.',
    'foto.mimes'          => 'The photo must be a file of type: jpeg, png, jpg.',
    'foto.max'            => 'The photo may not be larger than 2MB.',
    'ttd.image'           => 'The signature must be an image.',
    'ttd.mimes'           => 'The signature must be a file of type: jpeg, png, jpg.',
    'ttd.max'             => 'The signature may not be larger than 2MB.',
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
    $student->password =  bcrypt($request->password);
    $student->save();

    return redirect()
        ->route('student.personal.editDataIndex', $student->id)
        ->with('success', 'Data mahasiswa berhasil diperbarui.');
}



}