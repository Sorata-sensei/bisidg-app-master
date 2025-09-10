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


class LoginController extends Controller
{
    protected $discordBot;

    public function __construct(DiscordBotService $discordBot)
    {
        $this->discordBot = $discordBot;
    }

    public function index(Request $request)
    {
        
    $checkOTP = Otp::where('user_id', 1) 
            ->whereDate('expires_at', '>', Carbon::now())
            ->first();


        if (empty($checkOTP)) {
            $otp = $this->generateOtp(6); // Misalnya, 6 karakter

            Otp::create([
                'otp' => $otp,
                'user_id' => 1, // Ganti dengan ID user yang sesuai
                'created_at' => now(),
                'expires_at' => now()->addDays(1), // Contoh masa berlaku 1 hari
            ]);

            $toEmail = 'indraasrori@gmail.com';
            $subject = 'OTP';
            $message = $otp;

            // Kirim email
            Mail::raw($message, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)
                        ->subject($subject);
            });
           
        }                
         
    
        $rolecheck = User::where('email', '=', $request->email)->first(); 
        // Cek apakah pengguna sudah terautentikasi dan ada OTP di session
        if (Auth::check() && Session::get('otp') != null) {
            if($rolecheck == 'wibu'){
              return redirect()->intended('/anime/dashboard')->withSuccess('Okaerinasai, Master! 🎮⚔️ Selamat menjelajahi dunia isekai. 🗺️✨');
            }else{
                return redirect()->intended('/admin/dashboard')->withSuccess('Login sukses, selamat datang! 😎');
            }
        } else {
            return view('admin.login.login')->withError('Sesi anda sudah habis silahkan masuk kembali');
        }
        return view('admin.login.login')->withSuccess('Selamat Datang 😎');
    }

    public function login(Request $request)
    {
        return $request->all();
        // Validasi input
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();

        // Cek OTP
        $checkOTP = Otp::where('otp', $request->otp)
            ->where('user_id', $user->id)
            ->first();
        
        // Cek apakah user ada dan memiliki izin
        if ($user === null || !in_array($user->role, ['superadmin', 'wibu'])) {
            return redirect()->back()->withErrors('Waduh, kamu ga punya izin buat akses halaman ini! 😅');
        }

        // Cek apakah OTP valid
        if ($checkOTP == null) {
            return redirect()->back()->withErrors('Waduh, ada yang tidak valid nih, coba cek lagi 😅');
        }
        $rolecheck = User::where('email', '=', $request->email)->first(); 
        // Coba autentikasi
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Session::put('otp', $checkOTP->otp);
            
            // Kirim informasi ke Discord
            $browserInfo = $this->getBrowserInfo();
            $embed = $this->createEmbedMessage($browserInfo, $request);
            $this->discordBot->sendMessage(1318122322653216821, "Ada Yang Masuk Admin", $embed);
            if($rolecheck->role == 'wibu'){
               
              return redirect()->intended('anime/dashboard')->withSuccess('Okaerinasai, Master! 🎮⚔️ Selamat menjelajahi dunia isekai. 🗺️✨');

            }else{
                return redirect()->intended('/admin/dashboard')->withSuccess('Login sukses, selamat datang! ' . $user->name . ' 😎');
            }
        }

        return redirect("/login")->withErrors('Login details are not valid');
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