<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\AlumniReportExport;
use Maatwebsite\Excel\Facades\Excel;

class AlumniReportController extends Controller
{
    private function cekAksesLaporan(): void
    {
        abort_unless(
            auth()->check() &&
            in_array(auth()->user()->role, ['admin', 'pengelola_data']),
            403,
            'Kamu tidak memiliki akses ke laporan alumni.'
        );
    }

    private function ambilDataStatistik(?Builder $query = null): array
    {
        $query = $query ?? User::where('role', 'alumni');

        $jumlahAlumni = (clone $query)->count();

        $berdasarkanProdi = (clone $query)
            ->whereNotNull('program_studi')
            ->where('program_studi', '!=', '')
            ->select('program_studi', DB::raw('COUNT(*) as total'))
            ->groupBy('program_studi')
            ->orderBy('program_studi')
            ->get();

        $berdasarkanTahunLulus = (clone $query)
            ->whereNotNull('tahun_lulus')
            ->select('tahun_lulus', DB::raw('COUNT(*) as total'))
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus')
            ->get();

        $berdasarkanStatus = (clone $query)
            ->whereNotNull('status_setelah_lulus')
            ->where('status_setelah_lulus', '!=', '')
            ->select('status_setelah_lulus', DB::raw('COUNT(*) as total'))
            ->groupBy('status_setelah_lulus')
            ->orderBy('status_setelah_lulus')
            ->get();

        return compact(
            'jumlahAlumni',
            'berdasarkanProdi',
            'berdasarkanTahunLulus',
            'berdasarkanStatus'
        );
    }

    public function index()
    {
        $this->cekAksesLaporan();

        $data = $this->ambilDataStatistik();
        $data['tanggalCetak'] = now()->translatedFormat('d F Y');

        return view('admin.laporan-alumni.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $this->cekAksesLaporan();

        $request->validate([
            'kategori' => 'required|in:keseluruhan,prodi,tahun,status',
            'nilai' => 'required_unless:kategori,keseluruhan,status',
            'chart_prodi' => 'nullable|string',
            'chart_tahun' => 'nullable|string',
            'chart_status' => 'nullable|string',
            'chart_horizontal' => 'nullable|string',
            'chart_horizontal_tahun' => 'nullable|string',
            'chart_horizontal_status' => 'nullable|string',
        ]);

        $kategori = $request->kategori;
        $nilaiPilihan = '';

        // Laporan status pekerjaan menggunakan seluruh kelompok status alumni.
        $query = User::where('role', 'alumni');

        $jenisLaporan = 'Laporan Keseluruhan Alumni';
        $namaFile = 'laporan-statistik-alumni';

        if ($kategori === 'prodi') {
            $nilaiPilihan = $request->nilai;

            $query->where('program_studi', $nilaiPilihan);

            $jenisLaporan = 'Laporan Alumni Berdasarkan Program Studi';
            $namaFile = 'laporan-alumni-prodi';

        } elseif ($kategori === 'tahun') {
            $nilaiPilihan = $request->nilai;

            $query->where('tahun_lulus', $nilaiPilihan);

            $jenisLaporan = 'Laporan Alumni Berdasarkan Tahun Lulus';
            $namaFile = 'laporan-alumni-tahun';

        } elseif ($kategori === 'status') {
            $jenisLaporan = 'Laporan Status Pekerjaan Alumni';
            $namaFile = 'laporan-status-pekerjaan-alumni';
        }

        if (!$query->exists()) {
            return back()->with(
                'error',
                'Data laporan yang dipilih tidak ditemukan.'
            );
        }

        $data = $this->ambilDataStatistik($query);

        $data['tanggalCetak'] = now()->translatedFormat('d F Y');
        $data['jenisLaporan'] = $jenisLaporan;
        $data['nilaiPilihan'] = $nilaiPilihan;
        $data['kategori'] = $kategori;

        // Menggunakan nama input grafik yang sudah ada di halaman.
        // Diagram lingkaran
        $data['chartProdiImage'] = $request->input('chart_prodi');
        $data['chartTahunImage'] = $request->input('chart_tahun');
        $data['chartStatusImage'] = $request->input('chart_status');

        // Grafik batang horizontal
        $data['chartHorizontalProdiImage'] = $request->input('chart_horizontal_prodi');
        $data['chartHorizontalTahunImage'] = $request->input('chart_horizontal_tahun');
        $data['chartHorizontalStatusImage'] = $request->input('chart_horizontal_status');

        // Lokasi logo FST untuk ditampilkan di setiap PDF.
        $data['logoFst'] = public_path('images/logo-fst.png');

        $pdf = Pdf::loadView('admin.laporan-alumni.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download($namaFile . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $this->cekAksesLaporan();

        $request->validate([
            'kategori' => 'required|in:keseluruhan,prodi,tahun,status',
            'nilai' => 'required_unless:kategori,keseluruhan,status',
        ]);

        $kategori = $request->kategori;
        $nilai = $request->nilai;

        $query = User::where('role', 'alumni');

        if ($kategori === 'prodi') {
            $query->where('program_studi', $nilai);

        } elseif ($kategori === 'tahun') {
            $query->where('tahun_lulus', $nilai);
        }

        // Kategori status mengambil seluruh data status pekerjaan,
        // bukan memfilter hanya satu status.
        if (!$query->exists()) {
            return back()->with(
                'error',
                'Data laporan yang dipilih tidak ditemukan.'
            );
        }

        $namaFile = match ($kategori) {
            'prodi' => 'laporan-alumni-prodi.xlsx',
            'tahun' => 'laporan-alumni-tahun.xlsx',
            'status' => 'laporan-status-pekerjaan-alumni.xlsx',
            default => 'laporan-statistik-alumni.xlsx',
        };

        return Excel::download(
            new AlumniReportExport($kategori, $nilai),
            $namaFile
        );
    }
}