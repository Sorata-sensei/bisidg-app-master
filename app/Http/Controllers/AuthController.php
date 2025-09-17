<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student ;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    private function validateRecaptcha($token, $action)
{
    $secret = env('RECAPTCHA_SECRET_KEY');
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$token}");
    $result = json_decode($response, true);

    return $result['success'] == true && $result['score'] >= 0.5 && $result['action'] == $action;
}

    public function index()
    {
            if (Auth::guard('student')->check()) {
                return redirect('/student/personal');
            }
            if (auth()->check()) {
                if(auth()->user()->role == 'admin') {
                    return redirect('/admin/dashboard');
                } elseif(auth()->user()->role == 'superadmin') {
                    return redirect('/admin/dashboard');    
                } elseif(auth()->user()->role == 'masteradmin') {
                    return redirect('/admin/dashboard');
                }
                  
               
            }
        return view('auth.login');
    }

    public function loginDosen(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
        'g-recaptcha-response' => 'required|string',
    ]);

    // validasi captcha
    if (!$this->validateRecaptcha($request->input('g-recaptcha-response'), 'login_dosen')) {
        return back()->withErrors(['captcha' => 'Captcha validation failed, try again.'])->withInput();
    }

    if (auth()->attempt($request->only('email', 'password'))) {
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
        'nim' => 'required|string',
        'password' => 'required|string',
        'g-recaptcha-response' => 'required|string',
    ]);

    // validasi captcha
    if (!$this->validateRecaptcha($request->input('g-recaptcha-response'), 'login_mahasiswa')) {
        return back()->withErrors(['captcha' => 'Captcha validation failed, try again.'])->withInput();
    }

    $credentials = $request->only('nim', 'password');

    if (Auth::guard('student')->attempt($credentials)) {
        $request->session()->regenerate();

        $student = Auth::guard('student')->user();
        $request->session()->put('student_id', encrypt($student->id));
        $request->session()->put('path_pic', $student->foto ?? '0');
        $request->session()->put('student_nama', $student->nama_lengkap ?? null);
        $request->session()->put('nim', $student->nim ?? null);

        return redirect()->intended('/student/personal');
    }

    return back()->with('error', 'NIM atau Password salah.')->withInput();
}


    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}