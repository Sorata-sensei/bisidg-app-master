<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student ;
class AuthController extends Controller
{
    public function index()
    {
            if (auth()->check()) {
                if(auth()->user()->role == 'admin') {
                    return redirect('/admin/dashboard');
                } elseif(auth()->user()->role == 'superadmin') {
                    return redirect('/admin/dashboard');    
                } elseif(auth()->user()->role == 'masteradmin') {
                    return redirect('/admin/dashboard');
                }
                  
                // if(auth()->user()->role == '') 
                //     return redirect('/admin/dashboard');
                
            }
        return view('auth.login');
    }

    public function loginDosen(Request $request)
    {
         $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function loginMahasiswa(Request $request)
    {
        $request->validate([
            'nim' => ['required', 'string'],
        ]);

        $nim = $request->input('nim');
        $mahasiswa = student::where('nim', $nim)->first();

        if (! $mahasiswa) {
            return back()->with('error', 'NIM tidak ditemukan.')->withInput();
        }

        // Opsi sederhana: simpan session mahasiswa_id
        $request->session()->put('student_id', $mahasiswa->id);
        // (opsional) simpan juga nama atau role
        $request->session()->put('student_nama', $mahasiswa->nama_lengkap ?? null);
        $request->session()->put('nim', $mahasiswa->nim ?? null);

        return redirect()->intended('/student/personal');
    }
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}