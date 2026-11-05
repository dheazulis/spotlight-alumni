@extends('layouts.dashboard')

@section('title', 'Persetujuan Alumni Inspiratif')
@section('breadcrumb', 'Persetujuan Alumni Inspiratif')

@push('styles')
<style>
    .ai-page,
    .ai-page * {
        box-sizing: border-box;
    }

    .ai-page {
        color: var(--sd-ink, #23342b);
    }

    /* Header halaman */
    .ai-heading {
        margin-bottom: 22px;
    }

    .ai-eyebrow {
        margin: 0 0 8px;
        color: var(--sd-green-700, #287653);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .ai-heading h1 {
        margin: 0 0 8px;
        color: var(--sd-green-950, #123d2d);
        font-size: 26px;
        line-height: 1.3;
        font-weight: 750;
    }

    .ai-heading p {
        margin: 0;
        color: var(--sd-muted, #758279);
        font-size: 14px;
        line-height: 1.6;
    }

    /* Notifikasi */
    .ai-alert {
        margin-bottom: 17px;
        padding: 12px 15px;
        border: 1px solid #d5e9d9;
        border-radius: 9px;
        background: #eff7ef;
        color: #315f43;
        font-size: 13px;
        line-height: 1.6;
    }

    .ai-error-box {
        margin-bottom: 17px;
        padding: 13px 16px;
        border: 1px solid #f0d5d1;
        border-radius: 9px;
        background: #fff5f3;
        color: #a3473e;
        font-size: 13px;
    }

    .ai-error-box strong {
        color: #a3473e;
        font-size: 13px;
    }

    .ai-error-box p {
        margin: 7px 0 0;
        color: #a3473e;
        font-size: 13px;
        line-height: 1.5;
    }

    /* Panel utama */
    .ai-panel {
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--sd-line, #e3eae3);
        border-radius: 13px;
        box-shadow: var(--sd-shadow, 0 8px 24px rgba(24,59,42,.06));
    }

    .ai-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 18px 20px;
        border-bottom: 1px solid var(--sd-line, #e3eae3);
    }

    .ai-panel-head h2 {
        margin: 0 0 5px;
        color: var(--sd-green-950, #123d2d);
        font-size: 15px;
        font-weight: 750;
    }

    .ai-panel-head p {
        margin: 0;
        color: var(--sd-muted, #758279);
        font-size: 13px;
        line-height: 1.5;
    }

    .ai-total {
        flex-shrink: 0;
        padding: 7px 11px;
        border: 1px solid #dce9dd;
        border-radius: 20px;
        background: var(--sd-sage-100, #e8f1e9);
        color: var(--sd-green-900, #174b37);
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* Tabel */
    .ai-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .ai-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
        font-size: 13px;
    }

    .ai-table th {
        padding: 13px 14px;
        background: var(--sd-sage-50, #f4f7f2);
        color: #405448;
        border-bottom: 1px solid var(--sd-line, #e3eae3);
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .ai-table td {
        padding: 14px;
        border-bottom: 1px solid #edf1ed;
        color: #526258;
        font-size: 13px;
        line-height: 1.55;
        vertical-align: top;
    }

    .ai-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .ai-table tbody tr:hover {
        background: #fbfdfb;
    }

    .ai-alumni-name {
        display: block;
        margin-bottom: 4px;
        color: var(--sd-green-950, #123d2d);
        font-size: 13px;
        font-weight: 700;
    }

    .ai-muted {
        color: var(--sd-muted, #758279);
        font-size: 12px;
    }

    .ai-period {
        display: inline-block;
        margin-bottom: 6px;
        padding: 5px 9px;
        border: 1px solid #dce9dd;
        border-radius: 7px;
        background: var(--sd-sage-100, #e8f1e9);
        color: var(--sd-green-900, #174b37);
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .ai-message {
        min-width: 180px;
        max-width: 290px;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    /* Status */
    .ai-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .ai-status.menunggu {
        background: #fff5df;
        color: #946516;
    }

    .ai-status.disetujui {
        background: #eaf5eb;
        color: #347247;
    }

    .ai-status.ditolak {
        background: #fff0ed;
        color: #a3473e;
    }

    .ai-note {
        margin-top: 8px;
        padding: 9px 10px;
        border-left: 3px solid #c7d9c9;
        border-radius: 0 7px 7px 0;
        background: #f6f9f6;
        color: #66766b;
        font-size: 12px;
        line-height: 1.5;
        white-space: pre-line;
    }

    /* Kolom keputusan */
    .ai-action {
        min-width: 215px;
    }

    .ai-form {
        margin: 0 0 12px;
    }

    .ai-form:last-child {
        margin-bottom: 0;
    }

    .ai-form textarea {
        display: block;
        width: 100%;
        min-height: 75px;
        margin-bottom: 7px;
        padding: 9px 10px;
        resize: vertical;
        border: 1px solid #dfe7df;
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: var(--sd-ink, #23342b);
        font: 400 12px/1.5 Inter, ui-sans-serif, system-ui, sans-serif;
    }

    .ai-form textarea:focus {
        border-color: #8bb69a;
        box-shadow: 0 0 0 3px rgba(40,118,83,.10);
    }

    .ai-form textarea::placeholder {
        color: #87938a;
    }

    /* Tombol */
    .ai-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        min-height: 35px;
        padding: 8px 11px;
        border: 1px solid transparent;
        border-radius: 7px;
        color: #fff;
        font: 600 12px Inter, ui-sans-serif, system-ui, sans-serif;
        cursor: pointer;
        transition: .18s;
    }

    .ai-button-approve {
        background: var(--sd-green-800, #1d6045);
        border-color: var(--sd-green-800, #1d6045);
    }

    .ai-button-approve:hover {
        background: var(--sd-green-900, #174b37);
        border-color: var(--sd-green-900, #174b37);
    }

    .ai-button-reject {
        background: #fff0f0;
        border-color: #f2d6d6;
        color: #d14343;
    }

    .ai-button-reject:hover {
        background: #ffe5e5;
        color: #bd3030;
    }

    .ai-processed {
        color: #66766b;
        font-size: 12px;
        font-weight: 650;
    }

    /* Data kosong */
    .ai-empty {
        padding: 38px 20px !important;
        color: var(--sd-muted, #758279) !important;
        font-size: 13px !important;
        text-align: center;
    }

    /* Pagination */
    .ai-pagination {
        padding: 15px 20px;
        border-top: 1px solid var(--sd-line, #e3eae3);
    }

    .ai-pagination .pagination {
        margin: 0;
    }

    .ai-pagination .page-link {
        color: var(--sd-green-800, #1d6045);
        border-color: var(--sd-line, #e3eae3);
        font-size: 12px;
    }

    .ai-pagination .page-item.active .page-link {
        background: var(--sd-green-800, #1d6045);
        border-color: var(--sd-green-800, #1d6045);
        color: #fff;
    }

    @media(max-width:650px) {
        .ai-heading h1 {
            font-size: 22px;
        }

        .ai-panel-head {
            align-items: flex-start;
            flex-direction: column;
        }

        .ai-panel-head {
            padding: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="ai-page">

    <header class="ai-heading">
        <p class="ai-eyebrow">Verifikasi dan Publikasi</p>

        <h1>Persetujuan Alumni Inspiratif</h1>

        <p>
            Periksa pengajuan dari Pengelola Data sebelum alumni inspiratif
            ditampilkan pada halaman utama.
        </p>
    </header>

    @if (session('status'))
        <div class="ai-alert" role="status">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <section class="ai-error-box" role="alert">
            <strong>Pengajuan belum berhasil diproses.</strong>

            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </section>
    @endif

    <section class="ai-panel" aria-labelledby="ai-list-heading">

        <div class="ai-panel-head">
            <div>
                <h2 id="ai-list-heading">Daftar Pengajuan</h2>
                <p>
                    Pengajuan yang menunggu keputusan ditampilkan lebih dahulu.
                </p>
            </div>

            <span class="ai-total">
                {{ $pengajuan->total() }} pengajuan
            </span>
        </div>

        <div class="ai-table-wrap">
            <table class="ai-table">

                <thead>
                    <tr>
                        <th>Alumni</th>
                        <th>Periode Penayangan</th>
                        <th>Pengaju</th>
                        <th>Pesan Motivasi</th>
                        <th>Status</th>
                        <th>Keputusan Admin</th>
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

                            $nomorTriwulan = intdiv($bulanMulai - 1, 3) + 1;
                        @endphp

                        <tr>
                            <td>
                                <strong class="ai-alumni-name">
                                    {{ $item->alumni->name ?? 'Alumni tidak tersedia' }}
                                </strong>

                                <span>
                                    {{ $item->alumni->program_studi ?? '-' }}
                                </span>

                                <br>

                                <span class="ai-muted">
                                    Lulus {{ $item->alumni->tahun_lulus ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <span class="ai-period">
                                    Triwulan {{ $nomorTriwulan }}
                                </span>

                                <br>

                                <span class="ai-muted">
                                    {{ $namaBulan[$bulanMulai] }}
                                    –
                                    {{ $namaBulan[$bulanAkhir] }}
                                    {{ $item->tahun }}
                                </span>
                            </td>

                            <td>
                                {{ $item->pengaju->name ?? '-' }}
                            </td>

                            <td>
                                <div class="ai-message">
                                    {{ $item->alumni->pesan_motivasi ?? '-' }}
                                </div>
                            </td>

                            <td>
                                <span class="ai-status {{ $item->status }}">
                                    {{ ucfirst($item->status) }}
                                </span>

                                @if ($item->catatan_admin)
                                    <div class="ai-note">
                                        <strong>Catatan admin:</strong>
                                        <br>
                                        {{ $item->catatan_admin }}
                                    </div>
                                @endif
                            </td>

                            <td class="ai-action">

                                @if ($item->status === 'menunggu')

                                    <form
                                        class="ai-form"
                                        method="POST"
                                        action="{{ route('admin.alumni-inspiratif.approve', $item) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <textarea
                                            name="catatan_admin"
                                            maxlength="1000"
                                            placeholder="Catatan admin (opsional)"
                                        ></textarea>

                                        <button
                                            class="ai-button ai-button-approve"
                                            type="submit"
                                        >
                                            ✓ Setujui Pengajuan
                                        </button>
                                    </form>

                                    <form
                                        class="ai-form"
                                        method="POST"
                                        action="{{ route('admin.alumni-inspiratif.reject', $item) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <textarea
                                            name="catatan_admin"
                                            maxlength="1000"
                                            placeholder="Alasan penolakan (wajib)"
                                            required
                                        ></textarea>

                                        <button
                                            class="ai-button ai-button-reject"
                                            type="submit"
                                        >
                                            ✕ Tolak Pengajuan
                                        </button>
                                    </form>

                                @else

                                    <span class="ai-processed">
                                        Sudah diproses
                                    </span>

                                    @if ($item->diputuskan_pada)
                                        <p class="ai-muted">
                                            {{ $item->diputuskan_pada->format('d-m-Y H:i') }}
                                        </p>
                                    @endif

                                @endif

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td class="ai-empty" colspan="6">
                                Belum ada pengajuan Alumni Inspiratif.
                            </td>
                        </tr>

                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="ai-pagination">
            {{ $pengajuan->links() }}
        </div>

    </section>
</div>
@endsection