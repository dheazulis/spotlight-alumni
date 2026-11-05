<?php

namespace App\Http\Controllers;

use App\Models\AlumniInspiratif;
use App\Models\User;
use Illuminate\Http\Request;

class AlumniInspiratifController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->role === 'pengelola_data', 403);

        $alumni = User::where('role', 'alumni')
            ->whereNotNull('foto_profil')
            ->whereNotNull('program_studi')
            ->whereNotNull('tahun_lulus')
            ->whereNotNull('status_setelah_lulus')
            ->whereNotNull('pesan_motivasi')
            ->where('foto_profil', '!=', '')
            ->where('program_studi', '!=', '')
            ->where('status_setelah_lulus', '!=', '')
            ->where('pesan_motivasi', '!=', '')
            ->orderBy('name')
            ->get();

        $pengajuan = AlumniInspiratif::with('alumni', 'peninjau')
            ->where('pengaju_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('pengelola.alumni-inspiratif.index', compact(
            'alumni',
            'pengajuan'
        ));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'pengelola_data', 403);

        $validated = $request->validate([
            'alumni_id' => ['required', 'integer', 'exists:users,id'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:2100'],
        ]);

        $calonAlumni = User::where('role', 'alumni')
            ->where('id', $validated['alumni_id'])
            ->whereNotNull('foto_profil')
            ->whereNotNull('program_studi')
            ->whereNotNull('tahun_lulus')
            ->whereNotNull('status_setelah_lulus')
            ->whereNotNull('pesan_motivasi')
            ->where('foto_profil', '!=', '')
            ->where('program_studi', '!=', '')
            ->where('status_setelah_lulus', '!=', '')
            ->where('pesan_motivasi', '!=', '')
            ->first();

        if (!$calonAlumni) {
            return back()
                ->withInput()
                ->withErrors([
                    'alumni_id' => 'Alumni harus melengkapi profil dan pesan motivasi terlebih dahulu.',
                ]);
        }

        // Menentukan rentang bulan dalam periode tiga bulan.
        $bulanMulai = intdiv($validated['bulan'] - 1, 3) * 3 + 1;
        $bulanAkhir = $bulanMulai + 2;

        // Cek apakah alumni sudah diajukan pada periode yang sama.
        $sudahDiajukan = AlumniInspiratif::where(
            'alumni_id',
            $validated['alumni_id']
        )
            ->where('tahun', $validated['tahun'])
            ->whereBetween('bulan', [$bulanMulai, $bulanAkhir])
            ->exists();

        if ($sudahDiajukan) {
            return back()
                ->withInput()
                ->withErrors([
                    'alumni_id' => 'Alumni ini sudah pernah diajukan pada periode tiga bulan tersebut.',
                ]);
        }

        AlumniInspiratif::create([
            'alumni_id' => $validated['alumni_id'],
            'pengaju_id' => auth()->id(),
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
            'status' => 'menunggu',
        ]);

        return redirect()
            ->route('pengelola.alumni-inspiratif.index')
            ->with('status', 'Pengajuan Alumni Inspiratif berhasil dikirim ke admin.');
    }
}
