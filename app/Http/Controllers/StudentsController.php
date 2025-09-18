<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\CardCounseling;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StudentsController extends Controller
{
    /**
     * Display a listing of the admin.students.
     */
    public function index(Request $request)
    {
        $counseling = CardCounseling::where('id_student', decrypt(session('student_id')))->count();
        return view('students.dashboard.index', compact('counseling'));
    }

    public function editDataIndex(Request $request)
    {
    $student = Student::with('dosenPA')->findOrFail(decrypt(session('student_id')));
        $isDefaultPassword = \Hash::check('Bisdig2025', $student->password);
        return view('students.personal.edit', compact('student', 'isDefaultPassword'));
    }

public function updateData(Request $request)
{
    $student = Student::findOrFail(decrypt(session('student_id')));

   $request->validate([
    'nama_orangtua' => 'nullable|string|max:255',
    'jenis_kelamin' => 'nullable',
    'tanggal_lahir' => 'nullable|date',
    'password'      => 'nullable|string|min:8|max:20',
    'alamat'        => 'nullable|string',
    'alamat_lat'    => 'nullable|numeric',
    'alamat_lng'    => 'nullable|numeric',
    'no_telepon'    => 'nullable|string|max:20',
    'no_telepon_orangtua' => 'nullable|string|max:20',
    'email'         => 'nullable|email|max:255',
    'foto'          => 'nullable|image|mimes:jpeg,png,jpg',
    'ttd'           => 'nullable|image|mimes:jpeg,png,jpg',
], [
    'nama_orangtua.string' => 'Parent’s name must be text.',
    'nama_orangtua.max'    => 'Parent’s name cannot be longer than 255 characters.',

    'jenis_kelamin.in'     => 'Please select a valid gender.',

    'tanggal_lahir.date'   => 'Please enter a valid birth date.',

    'password.min'         => 'Password must be at least 8 characters.',
    'password.max'         => 'Password cannot be longer than 20 characters.',

    'alamat.string'        => 'Address must be text.',
    'alamat_lat.numeric'   => 'Latitude must be a number.',
    'alamat_lng.numeric'   => 'Longitude must be a number.',

    'no_telepon.string'    => 'Phone number must be text.',
    'no_telepon.max'       => 'Phone number cannot be longer than 20 characters.',
    'no_telepon_orangtua.string' => 'Parent’s phone number must be text.',
    'no_telepon_orangtua.max'    => 'Parent’s phone number cannot be longer than 20 characters.',

    'email.email'          => 'Please enter a valid email address.',
    'email.max'            => 'Email cannot be longer than 255 characters.',

    'foto.image'           => 'Photo must be an image.',
    'foto.mimes'           => 'Photo must be a file of type: jpeg, png, jpg.',
    'ttd.image'            => 'Signature must be an image.',
    'ttd.mimes'            => 'Signature must be a file of type: jpeg, png, jpg.',
]);

//  return $request->all();
    try {
        DB::transaction(function () use ($request, $student) {

            if ( $request->filled('nama_orangtua')) {
                $student->nama_orangtua = $request->nama_orangtua;
            }
            if ( $request->filled('jenis_kelamin')) {
                $student->jenis_kelamin = $request->jenis_kelamin;
            }

            if ( $request->filled('tanggal_lahir')) {
                $student->tanggal_lahir = $request->tanggal_lahir;
            }

            if ( $request->filled('alamat')) {
                $student->alamat = $request->alamat;
            }

          if ($request->filled('alamat_lat') && $request->filled('alamat_lng')) {
                $student->alamat_lat = $request->input('alamat_lat');
                $student->alamat_lng = $request->input('alamat_lng');
            }


            if ( $request->filled('no_telepon')) {
                $student->no_telepon = $request->no_telepon;
            }

            if ($request->filled('no_telepon_orangtua')) {
                $student->no_telepon_orangtua = $request->no_telepon_orangtua;
            }

            if ( $request->filled('email')) {
                $student->email = $request->email;
            }

            if ($request->hasFile('foto')) {
                if ($student->foto && Storage::disk('public')->exists($student->foto)) {
                    Storage::disk('public')->delete($student->foto);
                }
                $student->foto = $request->file('foto')->store('students/foto/'.$student->id, 'public');
            }

            if ($request->hasFile('ttd')) {
                if ($student->ttd && Storage::disk('public')->exists($student->ttd)) {
                    Storage::disk('public')->delete($student->ttd);
                }
                $student->ttd = $request->file('ttd')->store('students/ttd/'.$student->id, 'public');
            }

            if ($request->filled('password')) {
                $student->password = bcrypt($request->password);
            }
            $student->is_edited = 0;
            $student->save();
        });

       return redirect()
    ->route('student.personal.editDataIndex', ['id' => $student->id])
    ->with('success', 'Data mahasiswa berhasil diperbarui.');


    } catch (\Exception $e) {
        \Log::error("Update data student gagal: " . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
    }
}


}