<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AlumniProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'alumni') {
            abort(403, 'Akses hanya untuk alumni.');
        }

        if (!$user->otp_verified_at) {
            return redirect()
                ->route('otp.form')
                ->with('status', 'Verifikasi OTP terlebih dahulu.');
        }

        return view('alumni.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'alumni') {
            abort(403, 'Akses hanya untuk alumni.');
        }

        if (!$user->otp_verified_at) {
            return redirect()
                ->route('otp.form')
                ->with('status', 'Verifikasi OTP terlebih dahulu.');
        }

        $validated = $request->validate([
            'tentang_saya' => ['nullable', 'string', 'max:3000'],
            'pesan_motivasi' => ['nullable', 'string', 'max:1000'],
            'program_studi' => ['nullable', 'string', 'max:255'],
            'tahun_masuk' => ['nullable', 'integer', 'min:1990', 'max:' . date('Y')],
            'tahun_lulus' => [
                'nullable',
                'integer',
                'min:1990',
                'max:' . (date('Y') + 10),
                function ($attribute, $value, $fail) use ($request) {
                    $tahunMasuk = $request->input('tahun_masuk');

                    if (
                        $value !== null &&
                        $tahunMasuk !== null &&
                        $value < $tahunMasuk
                    ) {
                        $fail('Tahun lulus tidak boleh lebih awal dari tahun masuk.');
                    }
                },
            ],
            'nomor_hp' => ['nullable', 'string', 'max:20'],
            'status_setelah_lulus' => [
                'nullable',
                'in:Bekerja,Wirausaha,Melanjutkan Studi,Belum Bekerja',
            ],
            'foto_profil' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $user->update(
            collect($validated)->except('foto_profil')->all()
        );

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $path = $request->file('foto_profil')
                ->store('foto-profil', 'public');

            $user->foto_profil = $path;
            $user->save();
        }

        return redirect()
            ->route('alumni.profile')
            ->with('status', 'Profil berhasil diperbarui.');
    }

    // Menampilkan halaman ganti password
    public function showChangePassword(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'alumni') {
            abort(403, 'Akses hanya untuk alumni.');
        }

        if (!$user->otp_verified_at) {
            return redirect()
                ->route('otp.form')
                ->with('status', 'Verifikasi OTP terlebih dahulu.');
        }

        return view('alumni.change-password');
    }

    // Memproses perubahan password
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'alumni') {
            abort(403, 'Akses hanya untuk alumni.');
        }

        if (!$user->otp_verified_at) {
            return redirect()
                ->route('otp.form')
                ->with('status', 'Verifikasi OTP terlebih dahulu.');
        }

        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()
            ->route('alumni.profile')
            ->with('status', 'Password berhasil diperbarui.');
    }
}
