
@extends('layouts.dashboard')

@section('title', 'Pengajuan Alumni Inspiratif')

@section('breadcrumb', 'Pengajuan Alumni Inspiratif')

@push('styles')
<style>
    .pai-page {
        color: #243247;
    }

    .pai-heading {
        margin-bottom: 24px;
    }

    .pai-heading h1 {
        margin: 0 0 8px;
        color: #123d2d;
        font-size: 25px;
        font-weight: 700;
    }

    .pai-heading p {
        margin: 0;
        color: #64748b;
        line-height: 1.6;
    }

    .pai-card {
        background: #fff;
        padding: 25px;
        margin-bottom: 24px;
        border-radius: 12px;
        box-shadow: 0 3px 12px #123d2d12;
    }

    .pai-card h2 {
        margin-top: 0;
        color: #123d2d;
        font-size: 19px;
    }

    .pai-page label {
        display: block;
        margin: 16px 0 7px;
        font-weight: bold;
    }

    .pai-page select,
    .pai-page input {
        width: 100%;
        padding: 12px;
        border: 1px solid #dce9df;
        border-radius: 7px;
        font-size: 14px;
    }

    .pai-button {
        width: 100%;
        padding: 12px;
        margin-top: 20px;
        background: #123d2d;
        color: white;
        border: 0;
        border-radius: 7px;
        cursor: pointer;
        font-weight: bold;
    }

    .pai-button:hover {
        background: #244c7b;
    }

    .pai-button:disabled {
        background: #94a3b8;
        cursor: not-allowed;
    }

    .pai-success {
        background: #e3f7e9;
        color: #176534;
        padding: 12px;
        border-radius: 7px;
        margin-bottom: 18px;
    }

    .pai-error {
        color: #b42318;
        font-size: 13px;
        margin-top: 6px;
    }

    .pai-hint {
        color: #64748b;
        font-size: 13px;
        line-height: 1.7;
    }

    .pai-period-info {
        background: #f5f7f3;
        color: #123d2d;
        border-radius: 8px;
        padding: 14px;
        margin: 16px 0;
        line-height: 1.7;
        font-size: 14px;
    }

    .pai-table-wrap {
        overflow-x: auto;
    }

    .pai-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    .pai-table th,
    .pai-table td {
        text-align: left;
        padding: 12px;
        border-bottom: 1px solid #e5eaf1;
        vertical-align: top;
    }

    .pai-table th {
        background: #f5f7f3;
        color: #123d2d;
    }

    .pai-status {
        font-weight: bold;
    }

    .pai-status.menunggu {
        color: #a16207;
    }

    .pai-status.disetujui {
        color: #15803d;
    }

    .pai-status.ditolak {
        color: #b91c1c;
    }

    @media (max-width: 600px) {
        .pai-card {
            padding: 18px;
        }
    }
</style>
@endpush

@section('content')
<div class="pai-page">

    <div class="pai-heading">
        <h1>Pengajuan Alumni Inspiratif</h1>
        <p>Ajukan alumni inspiratif untuk periode penayangan setiap tiga bulan.</p>
    </div>

    @if (session('status'))
        <div class="pai-success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="pai-card">
            <strong>Pengajuan belum berhasil.</strong>
            <p class="pai-hint">
                Periksa kembali informasi yang ditandai pada formulir.
            </p>
        </div>
    @endif

    <section class="pai-card">
        <h2>Form Pengajuan</h2>

        <p class="pai-hint">
            Pilih alumni yang sudah melengkapi foto, program studi, tahun lulus,
            status setelah lulus, dan pesan motivasi. Pengajuan akan diperiksa
            oleh admin sebelum ditampilkan di halaman utama.
        </p>

        <div class="pai-period-info">
            <strong>Pembagian periode penayangan</strong><br>
            Triwulan 1: Januari–Maret<br>
            Triwulan 2: April–Juni<br>
            Triwulan 3: Juli–September<br>
            Triwulan 4: Oktober–Desember
        </div>

        <form action="{{ route('pengelola.alumni-inspiratif.store') }}" method="POST">
            @csrf

            <label for="alumni_id">Pilih Alumni</label>
            <select id="alumni_id" name="alumni_id" required>
                <option value="">-- Pilih Alumni --</option>

                @foreach ($alumni as $item)
                    <option
                        value="{{ $item->id }}"
                        {{ old('alumni_id') == $item->id ? 'selected' : '' }}
                    >
                        {{ $item->name }} — {{ $item->program_studi }}
                        (Lulus {{ $item->tahun_lulus }})
                    </option>
                @endforeach
            </select>

            @error('alumni_id')
                <div class="pai-error">{{ $message }}</div>
            @enderror

            @if ($alumni->isEmpty())
                <p class="pai-hint">
                    Belum ada alumni yang memenuhi persyaratan pengajuan.
                </p>
            @endif

            <label for="bulan">Periode Penayangan</label>
            <select id="bulan" name="bulan" required>
                <option value="">-- Pilih Periode --</option>

                @foreach ([
                    1 => 'Triwulan 1 (Januari–Maret)',
                    4 => 'Triwulan 2 (April–Juni)',
                    7 => 'Triwulan 3 (Juli–September)',
                    10 => 'Triwulan 4 (Oktober–Desember)',
                ] as $angka => $namaPeriode)
                    <option
                        value="{{ $angka }}"
                        {{ (int) old('bulan', intdiv(now()->month - 1, 3) * 3 + 1) === $angka ? 'selected' : '' }}
                    >
                        {{ $namaPeriode }}
                    </option>
                @endforeach
            </select>

            @error('bulan')
                <div class="pai-error">{{ $message }}</div>
            @enderror

            <p class="pai-hint">
                Periode disimpan menggunakan bulan awal triwulan:
                Januari, April, Juli, atau Oktober.
            </p>

            <label for="tahun">Tahun Penayangan</label>
            <input
                type="number"
                id="tahun"
                name="tahun"
                min="2020"
                max="2100"
                value="{{ old('tahun', now()->year) }}"
                required
            >

            @error('tahun')
                <div class="pai-error">{{ $message }}</div>
            @enderror

            <button
                type="submit"
                class="pai-button"
                {{ $alumni->isEmpty() ? 'disabled' : '' }}
            >
                Kirim Pengajuan ke Admin
            </button>
        </form>
    </section>

    <section class="pai-card">
        <h2>Riwayat Pengajuan Saya</h2>

        <p class="pai-hint">
            Riwayat pengajuan tetap tersimpan meskipun periode penayangannya
            telah berakhir.
        </p>

        <div class="pai-table-wrap">
            <table class="pai-table">
                <thead>
                    <tr>
                        <th>Nama Alumni</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Catatan Admin</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pengajuan as $item)
                        @php
                            $bulanMulai = intdiv($item->bulan - 1, 3) * 3 + 1;
                            $bulanAkhir = $bulanMulai + 2;

                            $namaBulan = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ];
                        @endphp

                        <tr>
                            <td>
                                {{ $item->alumni->name ?? 'Alumni tidak tersedia' }}
                            </td>

                            <td>
                                Triwulan {{ intdiv($bulanMulai - 1, 3) + 1 }}<br>
                                <span class="pai-hint">
                                    {{ $namaBulan[$bulanMulai] }}
                                    – {{ $namaBulan[$bulanAkhir] }}
                                    {{ $item->tahun }}
                                </span>
                            </td>

                            <td>
                                <span class="pai-status {{ $item->status }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>

                            <td>
                                {{ $item->catatan_admin ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Belum ada pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection
