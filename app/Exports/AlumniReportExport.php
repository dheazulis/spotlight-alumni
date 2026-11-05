<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class AlumniReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $kategori;
    protected $nilai;
    protected $nomor = 0;

    public function __construct($kategori = 'keseluruhan', $nilai = null)
    {
        $this->kategori = $kategori;
        $this->nilai = $nilai;
    }

    public function collection(): Collection
    {
        $query = User::where('role', 'alumni');

        // Laporan khusus status pekerjaan
        if ($this->kategori === 'status') {
            return $query
                ->selectRaw("
                    COALESCE(status_setelah_lulus, 'Belum diisi') as status_pekerjaan,
                    COUNT(*) as total
                ")
                ->groupBy('status_pekerjaan')
                ->orderBy('status_pekerjaan')
                ->get();
        }

        // Filter berdasarkan program studi
        if ($this->kategori === 'prodi' && $this->nilai) {
            $query->where('program_studi', $this->nilai);
        }

        // Filter berdasarkan tahun lulus
        if ($this->kategori === 'tahun' && $this->nilai) {
            $query->where('tahun_lulus', $this->nilai);
        }

        return $query->orderBy('name')->get();
    }

    public function headings(): array
    {
        if ($this->kategori === 'status') {
            return [
                'No',
                'Status Pekerjaan',
                'Jumlah Alumni',
            ];
        }

        return [
            'No',
            'NIM',
            'Nama Alumni',
            'Email',
            'Program Studi',
            'Tahun Masuk',
            'Tahun Lulus',
            'Nomor HP',
            'Status Setelah Lulus',
        ];
    }

    public function map($alumni): array
    {
        $this->nomor++;

        // Format khusus laporan status pekerjaan
        if ($this->kategori === 'status') {
            return [
                $this->nomor,
                $alumni->status_pekerjaan,
                $alumni->total,
            ];
        }

        // Format laporan alumni lainnya
        return [
            $this->nomor,
            $alumni->nim,
            $alumni->name,
            $alumni->email,
            $alumni->program_studi,
            $alumni->tahun_masuk,
            $alumni->tahun_lulus,
            $alumni->nomor_hp,
            $alumni->status_setelah_lulus,
        ];
    }

    public function title(): string
    {
        if ($this->kategori === 'status') {
            return 'Status Pekerjaan Alumni';
        }

        return 'Data Alumni';
    }
}
