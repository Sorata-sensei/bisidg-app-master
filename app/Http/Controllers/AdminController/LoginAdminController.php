<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use Browser;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\DiscordBotService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\Otp;
use Illuminate\Support\Facades\Log;
use App\Mail\SendEmail;
use Exception;
class LoginAdminController extends Controller
{
    protected $discordBot;

    public function __construct(DiscordBotService $discordBot)
    {
        $this->discordBot = $discordBot;
    }

    private function getBrowserInfo(): array
    {
        $browser = Browser::detect();
        return [
            'name' => $browser->browserFamily(),
            'version' => $browser->browserVersion(),
            'platform' => $browser->platformName(),
        ];
    }

    private function createEmbedMessage(array $browserInfo, Request $request): array
    {
        $currentDateTime = Carbon::now()->translatedFormat('l, d F Y');

        return [
            'title' => 'Informasi Pengunjung',
            'description' => "Pengunjung baru telah terdeteksi.",
            'fields' => [
                [
                    'name' => 'Browser',
                    'value' => $browserInfo['name'],
                    'inline' => true,
                ],
                [
                    'name' => 'Platform',
                    'value' => $browserInfo['platform'],
                    'inline' => true,
                ],
                [
                    'name' => 'IP address',
                    'value' => $request->ip(),
                    'inline' => true,
                ],
            ],
            'color' => hexdec("B02A37"),
            'footer' => [
                'text' => "Tanggal $currentDateTime",
            ],
        ];
    }
    private function generateOtp($length = 6)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $otp;
    }
        public function index(Request $request)
    {
        // Cek apakah ada OTP yang masih berlaku untuk user dengan ID 1
        $checkOTP = Otp::where('user_id', 1)
            ->whereDate('expires_at', '>', Carbon::now())
            ->first();

        // Jika tidak ada OTP yang aktif, buat OTP baru dan kirim via email
        if (empty($checkOTP)) {
            $otp = $this->generateOtp(6); // Generate OTP 6 karakter

            Otp::create([
                'otp' => $otp,
                'user_id' => 1, // Ganti sesuai ID user yang tepat
                'created_at' => now(),
                'expires_at' => now()->addDays(1), // Masa berlaku 1 hari
            ]);

            
            $data = [
                'name' => 'Anwar Fauzi',
                'otp' =>  $otp,
            ];
            Mail::to('muhammadanwarfauzi1999@gmail.com')->send(new SendEmail($data));
            
        }

        // Ambil data user berdasarkan email yang dikirim pada request
        $rolecheck = User::where('email', '=', $request->email)->first();

        // Cek apakah pengguna sudah login dan terdapat OTP di session
        if (Auth::check() && Session::get('otp') != null) {
            // Cek role user dengan email yang diinput
            if ($rolecheck && $rolecheck->role == 'wibu') {
                return redirect()->intended('/anime/dashboard')
                    ->withSuccess('Okaerinasai, Master! 🎮⚔️ Selamat menjelajahi dunia isekai. 🗺️✨');
            } else {
                return redirect()->intended('/admin/dashboard')
                    ->withSuccess('Login sukses, selamat datang! 😎');
            }
        } else {
            // Jika session OTP tidak ada atau belum login, arahkan ke halaman login dengan pesan error
            return view('admin.login.login')->withError('Sesi anda sudah habis silahkan masuk kembali');
        }

        // Default menampilkan halaman login dengan pesan selamat datang
        return view('admin.login.login')->withSuccess('Selamat Datang 😎');
    }
    public function login(Request $request)
    {
      
        // Validasi input
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();
        
        // Cek apakah user ada dan memiliki izin
        if ($user === null || !in_array($user->role, ['superadmin', 'wibu'])) {
            return redirect()->back()->withErrors('Waduh, kamu ga punya izin buat akses halaman ini! 😅');
        }
       
        $rolecheck = User::where('email', '=', $request->email)->first(); 
   
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
           
            $browserInfo = $this->getBrowserInfo();
            $embed = $this->createEmbedMessage($browserInfo, $request);
            $this->discordBot->sendMessage(1318122322653216821, "Ada Yang Masuk Admin", $embed);
            if($rolecheck->role == 'wibu'){
               
              return redirect()->intended('/login/otp')->withSuccess('Okaerinasai, Master! 🎮⚔️ Selamat menjelajahi dunia isekai. 🗺️✨');

            }else{
                return redirect()->intended('/login/otp')->withSuccess('Login sukses, selamat datang! ' . $user->name . ' 😎');
            }
        }

        return redirect("/login")->withErrors('Login details are not valid');
    }


    public function otp(Request $request)
{
    $rolecheck = User::where('email', '=', $request->email)->first(); 
        if (Auth::check() && Session::get('otp') != null) {
            // Cek role user dengan email yang diinput
            if ($rolecheck && $rolecheck->role == 'wibu') {
                return redirect()->intended('/anime/dashboard')
                    ->withSuccess('Okaerinasai, Master! 🎮⚔️ Selamat menjelajahi dunia isekai. 🗺️✨');
            } else {
                return redirect()->intended('/admin/dashboard')
                    ->withSuccess('Login sukses, selamat datang! 😎');
            }
        } 
        return view('admin.login.otps')->withSuccess('Selamat Datang 😎');
   
}

  

    public function storeotp(Request $request){

        $validated = $request->validate([
            'otps' => 'required|min:6',
        ]);

        $otpscheck =Otp::where('otp', $request->otps)
        ->where('user_id', $request->user()->id)
        ->first();
        $rolecheck = User::where('email', '=', $request->email)->first(); 
        if ($otpscheck) {
            Session::put('otp',  $otpscheck->otp);
            if ($rolecheck && $rolecheck->role == 'wibu') {
                return redirect()->intended('/anime/dashboard')
                    ->withSuccess('Okaerinasai, Master! 🎮⚔️ Selamat menjelajahi dunia isekai. 🗺️✨');
            } else {
                return redirect()->intended('/admin/dashboard')
                    ->withSuccess('Login sukses, selamat datang! 😎');
            }
        } else {
           return redirect()->back();
        }
        $rolecheck = User::where('email', '=', $request->email)->first(); 
        if (Auth::check() && Session::get('otp') != null) {
            // Cek role user dengan email yang diinput
            if ($rolecheck && $rolecheck->role == 'wibu') {
                return redirect()->intended('/anime/dashboard')
                    ->withSuccess('Okaerinasai, Master! 🎮⚔️ Selamat menjelajahi dunia isekai. 🗺️✨');
            } else {
                return redirect()->intended('/admin/dashboard')
                    ->withSuccess('Login sukses, selamat datang! 😎');
            }
        } 
    }
    public function logout(Request $request)
    {
        // Logout pengguna
        Auth::logout();

        // Hapus OTP dari session
        Session::forget('otp');

        // Hapus semua session
        $request->session()->invalidate();

        // Regenerasi token CSRF
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Yay! Kamu udah berhasil logout! 🎉 Sampai jumpa lagi! 👋');
    }
}