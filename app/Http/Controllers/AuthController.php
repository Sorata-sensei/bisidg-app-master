<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Halaman login utama
     */
    public function index()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.personal.index');
        }

        if (Auth::check()) {
            $role = Auth::user()->role;
            if (in_array($role, ['admin', 'superadmin', 'masteradmin'])) {
                return redirect()->route('dashboard.admin.index');
            }
        }

        return view('auth.login');
    }

    /**
     * Login untuk Dosen/Admin
     */
    public function loginDosen(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard.admin.index'));
        }

        return back()
            ->withErrors(['email' => 'Email atau Password tidak sesuai.'])
            ->onlyInput('email');
    }

    /**
     * Login untuk Mahasiswa
     */
    public function loginMahasiswa(Request $request)
    {
        $credentials = $request->validate([
            'nim'      => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('student')->attempt($credentials)) {
            $request->session()->regenerate();

            $student = Auth::guard('student')->user();
            $request->session()->put([
                'student_id'    => encrypt($student->id),
                'path_pic'      => $student->foto ?? '0',
                'student_nama'  => $student->nama_lengkap ?? null,
                'nim'           => $student->nim ?? null,
            ]);

            return redirect()->intended(route('student.personal.index'));
        }

        return back()
            ->with('error', 'NIM atau Password salah.')
            ->withInput();
    }

    /**
     * Logout user (dosen/admin/mahasiswa)
     */
    public function logout(Request $request)
    {
        if (Auth::guard('student')->check()) {
            Auth::guard('student')->logout();
        } else {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}