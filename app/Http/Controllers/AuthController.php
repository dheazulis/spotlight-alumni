<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Membuat CAPTCHA berupa kombinasi huruf dan angka.
        $captcha = strtoupper(Str::random(5));

        session([
            'captcha_answer' => $captcha,
        ]);

        return view('auth.login', [
            'captchaQuestion' => $captcha,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha_answer' => ['required', 'string', 'size:5'],
        ], [
            'captcha_answer.required' => 'CAPTCHA wajib diisi.',
            'captcha_answer.string' => 'CAPTCHA harus berupa teks.',
            'captcha_answer.size' => 'CAPTCHA harus terdiri dari 5 karakter.',
        ]);

        // Memeriksa jawaban CAPTCHA dari session.
        $jawabanBenar = session('captcha_answer');

        if (
            $jawabanBenar === null ||
            strtoupper(trim($credentials['captcha_answer'])) !== $jawabanBenar
        ) {
            return back()
                ->withErrors([
                    'captcha_answer' => 'CAPTCHA salah. Silakan coba kode baru.',
                ])
                ->onlyInput('username');
        }

        // CAPTCHA hanya boleh digunakan satu kali.
        session()->forget('captcha_answer');

        $user = User::where('name', $credentials['username'])->first();

        if (
            !$user ||
            !Auth::attempt([
                'email' => $user->email,
                'password' => $credentials['password'],
            ])
        ) {
            return back()
                ->withErrors([
                    'username' => 'Username atau password salah.',
                ])
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Setiap login baru, alumni wajib melakukan verifikasi OTP lagi.
        if ($user->role === 'alumni') {
            $request->session()->put('otp_verified_this_session', false);

            DB::table('email_otps')
                ->where('user_id', $user->id)
                ->delete();

            return redirect()
                ->route('otp.form')
                ->with(
                    'status',
                    'Silakan kirim OTP ke email terdaftar untuk melanjutkan.'
                );
        }

        $request->session()->forget('otp_verified_this_session');

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}