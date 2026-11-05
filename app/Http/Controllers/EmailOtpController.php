<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class EmailOtpController extends Controller
{
    public function send(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'alumni') {
            abort(403);
        }

        // Sudah diverifikasi dalam sesi login saat ini.
        if ($request->session()->get('otp_verified_this_session', false)) {
            return redirect('/dashboard');
        }

        $key = 'otp-send:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors([
                'otp' => 'Terlalu banyak permintaan OTP. Coba lagi beberapa menit lagi.',
            ]);
        }

        RateLimiter::hit($key, 300);

        $otp = (string) random_int(100000, 999999);

        // Hapus OTP sebelumnya agar hanya kode terbaru yang berlaku.
        DB::table('email_otps')
            ->where('user_id', $user->id)
            ->delete();

        DB::table('email_otps')->insert([
            'user_id' => $user->id,
            'otp_hash' => Hash::make($otp),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
            'verified_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            Mail::raw(
                "Kode OTP Spotlight Alumni FST kamu adalah: {$otp}\n\n"
                . "Kode berlaku selama 5 menit. Jangan bagikan kode ini kepada siapa pun.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Kode Verifikasi Spotlight Alumni FST');
                }
            );
        } catch (\Throwable $e) {
            DB::table('email_otps')
                ->where('user_id', $user->id)
                ->delete();

            report($e);

            return back()->withErrors([
                'otp' => 'Email OTP gagal dikirim. Periksa konfigurasi email.',
            ]);
        }

        return redirect('/verify-otp')
            ->with('status', 'OTP sudah dikirim ke email terdaftar.');
    }

    public function showVerify(Request $request)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'alumni') {
            abort(403);
        }

        if ($request->session()->get('otp_verified_this_session', false)) {
            return redirect('/dashboard');
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        if (!$user || $user->role !== 'alumni') {
            abort(403);
        }

        // Gunakan status sesi, bukan otp_verified_at dari database.
        if ($request->session()->get('otp_verified_this_session', false)) {
            return redirect('/dashboard');
        }

        $record = DB::table('email_otps')
            ->where('user_id', $user->id)
            ->whereNull('verified_at')
            ->first();

        if (
            !$record ||
            now()->greaterThanOrEqualTo($record->expires_at)
        ) {
            DB::table('email_otps')
                ->where('user_id', $user->id)
                ->delete();

            return back()->withErrors([
                'otp' => 'OTP tidak tersedia atau sudah kedaluwarsa. Minta OTP baru.',
            ]);
        }

        if ($record->attempts >= 5) {
            DB::table('email_otps')
                ->where('id', $record->id)
                ->delete();

            return back()->withErrors([
                'otp' => 'Batas percobaan tercapai. Minta OTP baru.',
            ]);
        }

        if (!Hash::check($request->otp, $record->otp_hash)) {
            $attempts = $record->attempts + 1;

            if ($attempts >= 5) {
                DB::table('email_otps')
                    ->where('id', $record->id)
                    ->delete();

                return back()->withErrors([
                    'otp' => 'Batas percobaan tercapai. Minta OTP baru.',
                ]);
            }

            DB::table('email_otps')
                ->where('id', $record->id)
                ->update([
                    'attempts' => $attempts,
                    'updated_at' => now(),
                ]);

            return back()->withErrors([
                'otp' => 'Kode OTP salah. Sisa percobaan: '
                    . (5 - $attempts) . ' kali.',
            ]);
        }

        DB::transaction(function () use ($user, $record) {
            $locked = DB::table('email_otps')
                ->where('id', $record->id)
                ->lockForUpdate()
                ->first();

            if (
                !$locked ||
                $locked->verified_at !== null ||
                now()->greaterThanOrEqualTo($locked->expires_at) ||
                $locked->attempts >= 5
            ) {
                abort(422, 'OTP sudah tidak berlaku. Silakan minta OTP baru.');
            }

            DB::table('email_otps')
                ->where('id', $record->id)
                ->update([
                    'verified_at' => now(),
                    'updated_at' => now(),
                ]);

            // Catat waktu verifikasi terakhir di database.
            $user->forceFill([
                'otp_verified_at' => now(),
            ])->save();
        });

        // Izinkan akses selama sesi login saat ini saja.
        $request->session()->put('otp_verified_this_session', true);

        return redirect('/dashboard')
            ->with('status', 'Email berhasil diverifikasi.');
    }
}
