<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AlumniManagementController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(
            in_array(auth()->user()->role, ['admin', 'pengelola_data'], true),
            403
        );

        $kataKunci = trim((string) $request->query('cari', ''));
        $jurusan = $request->query('jurusan', '');
        $tahunLulus = $request->query('tahun_lulus', '');
        $status = $request->query('status', '');

        $query = User::where('role', 'alumni')
            ->when($kataKunci !== '', function ($query) use ($kataKunci) {
                $query->where(function ($q) use ($kataKunci) {
                    $q->where('name', 'like', "%{$kataKunci}%")
                        ->orWhere('email', 'like', "%{$kataKunci}%")
                        ->orWhere('nim', 'like', "%{$kataKunci}%")
                        ->orWhere('program_studi', 'like', "%{$kataKunci}%");
                });
            })
            ->when($jurusan !== '', fn ($q) => $q->where('program_studi', $jurusan))
            ->when($tahunLulus !== '', fn ($q) => $q->where('tahun_lulus', $tahunLulus))
            ->when($status !== '', fn ($q) => $q->where('status_setelah_lulus', $status));

        $alumni = $query->latest()->paginate(10)->withQueryString();

        $daftarJurusan = User::where('role', 'alumni')
            ->whereNotNull('program_studi')->where('program_studi', '<>', '')
            ->distinct()->orderBy('program_studi')->pluck('program_studi');

        $daftarTahunLulus = User::where('role', 'alumni')
            ->whereNotNull('tahun_lulus')->distinct()->orderByDesc('tahun_lulus')
            ->pluck('tahun_lulus');

        $daftarStatus = User::where('role', 'alumni')
            ->whereNotNull('status_setelah_lulus')->where('status_setelah_lulus', '<>', '')
            ->distinct()->orderBy('status_setelah_lulus')->pluck('status_setelah_lulus');

        return view('admin.alumni.index', compact(
            'alumni', 'kataKunci', 'jurusan', 'tahunLulus', 'status',
            'daftarJurusan', 'daftarTahunLulus', 'daftarStatus'
        ));
    }
}
