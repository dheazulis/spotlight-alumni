@extends('layouts.dashboard')

@section('title', 'Lowongan Kerja')
@section('breadcrumb', 'Lowongan Kerja')

@push('styles')
<style>
    .job-page,
    .job-page * {
        box-sizing: border-box;
    }

    .job-page {
        color: var(--sd-ink, #23342b);
    }

    /* Header halaman */
    .job-heading {
        margin-bottom: 22px;
    }

    .job-eyebrow {
        margin: 0 0 8px;
        color: var(--sd-green-700, #287653);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
    }

    .job-heading h1 {
        margin: 0 0 8px;
        color: var(--sd-green-950, #123d2d);
        font-size: 26px;
        line-height: 1.3;
        font-weight: 750;
    }

    .job-heading p {
        margin: 0;
        color: var(--sd-muted, #758279);
        font-size: 14px;
        line-height: 1.6;
    }

    /* Notifikasi */
    .job-alert {
        margin-bottom: 17px;
        padding: 12px 15px;
        border: 1px solid #d5e9d9;
        border-radius: 9px;
        background: #eff7ef;
        color: #315f43;
        font-size: 13px;
        line-height: 1.6;
    }

    .job-error-box {
        margin-bottom: 17px;
        padding: 13px 16px;
        border: 1px solid #f0d5d1;
        border-radius: 9px;
        background: #fff5f3;
        color: #a3473e;
        font-size: 13px;
    }

    .job-error-box p {
        margin: 7px 0 0;
        color: #a3473e;
        font-size: 13px;
        line-height: 1.5;
    }

    /* Panel utama */
    .job-panel {
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--sd-line, #e3eae3);
        border-radius: 13px;
        box-shadow: var(--sd-shadow, 0 8px 24px rgba(24,59,42,.06));
    }

    .job-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 18px 20px;
        border-bottom: 1px solid var(--sd-line, #e3eae3);
    }

    .job-panel-head h2 {
        margin: 0 0 5px;
        color: var(--sd-green-950, #123d2d);
        font-size: 15px;
        font-weight: 750;
    }

    .job-panel-head p {
        margin: 0;
        color: var(--sd-muted, #758279);
        font-size: 13px;
        line-height: 1.5;
    }

    .job-total {
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

    /* Pencarian */
    .job-search-wrap {
        padding: 16px 20px;
        border-bottom: 1px solid var(--sd-line, #e3eae3);
    }

    .job-search {
        display: block;
        width: 100%;
        min-height: 42px;
        padding: 10px 13px;
        border: 1px solid #dfe7df;
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: var(--sd-ink, #23342b);
        font: 400 13px Inter, ui-sans-serif, system-ui, sans-serif;
    }

    .job-search:focus {
        border-color: #8bb69a;
        box-shadow: 0 0 0 3px rgba(40,118,83,.10);
    }

    .job-search::placeholder {
        color: #87938a;
    }

    /* Daftar lowongan */
    .job-list {
        width: 100%;
    }

    .job-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .job-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
        font-size: 13px;
    }

    .job-table th {
        padding: 13px 14px;
        background: var(--sd-sage-50, #f4f7f2);
        color: #405448;
        border-bottom: 1px solid var(--sd-line, #e3eae3);
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .job-table td {
        padding: 14px;
        border-bottom: 1px solid #edf1ed;
        color: #526258;
        font-size: 13px;
        line-height: 1.55;
        vertical-align: top;
    }

    .job-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .job-table tbody tr:hover {
        background: #fbfdfb;
    }

    .job-position {
        display: block;
        margin-bottom: 4px;
        color: var(--sd-green-950, #123d2d);
        font-size: 13px;
        font-weight: 700;
    }

    .job-company {
        display: block;
        color: #526258;
        font-size: 13px;
    }

    .job-muted {
        color: var(--sd-muted, #758279);
        font-size: 12px;
    }

    .job-meta {
        display: flex;
        flex-direction: column;
        gap: 5px;
        min-width: 145px;
    }

    .job-meta span {
        color: #66766b;
        font-size: 12px;
        line-height: 1.5;
    }

    /* Status */
    .job-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .job-status.menunggu {
        background: #fff5df;
        color: #946516;
    }

    .job-status.disetujui {
        background: #eaf5eb;
        color: #347247;
    }

    .job-status.ditolak {
        background: #fff0ed;
        color: #a3473e;
    }

    /* Kolom keputusan */
    .job-action {
        min-width: 190px;
    }

    .job-form {
        margin: 0 0 10px;
    }

    .job-form:last-child {
        margin-bottom: 0;
    }

    /* Tombol */
    .job-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        min-height: 35px;
        padding: 8px 11px;
        border: 1px solid transparent;
        border-radius: 7px;
        font: 600 12px Inter, ui-sans-serif, system-ui, sans-serif;
        cursor: pointer;
        transition: .18s;
    }

    .job-button-approve {
        background: var(--sd-green-800, #1d6045);
        border-color: var(--sd-green-800, #1d6045);
        color: #fff;
    }

    .job-button-approve:hover {
        background: var(--sd-green-900, #174b37);
        border-color: var(--sd-green-900, #174b37);
    }

    .job-button-reject {
        background: #fff0f0;
        border-color: #f2d6d6;
        color: #d14343;
    }

    .job-button-reject:hover {
        background: #ffe5e5;
        color: #bd3030;
    }

    /* Detail lowongan */
    .job-detail {
        min-width: 180px;
    }

    .job-detail summary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border: 1px solid #dce9dd;
        border-radius: 7px;
        background: var(--sd-sage-50, #f4f7f2);
        color: var(--sd-green-800, #1d6045);
        font-size: 12px;
        font-weight: 650;
        cursor: pointer;
        list-style: none;
        white-space: nowrap;
    }

    .job-detail summary::-webkit-details-marker {
        display: none;
    }

    .job-detail[open] summary {
        margin-bottom: 10px;
        background: var(--sd-sage-100, #e8f1e9);
    }

    .job-detail-content {
        min-width: 210px;
        padding: 12px;
        border: 1px solid var(--sd-line, #e3eae3);
        border-radius: 8px;
        background: #fbfdfb;
    }

    .job-detail-content h3 {
        margin: 0 0 10px;
        color: var(--sd-green-950, #123d2d);
        font-size: 13px;
        font-weight: 750;
    }

    .job-detail-content p {
        margin: 0 0 9px;
        color: #526258;
        font-size: 12px;
        line-height: 1.6;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .job-detail-content p:last-child {
        margin-bottom: 0;
    }

    .job-detail-content strong {
        color: #405448;
        font-weight: 700;
    }

    .job-detail-content a {
        color: var(--sd-green-700, #287653);
        font-weight: 650;
        text-decoration: none;
    }

    .job-detail-content a:hover {
        text-decoration: underline;
    }

    /* Form penolakan */
    .job-reject-form {
        margin-top: 13px;
        padding-top: 12px;
        border-top: 1px solid var(--sd-line, #e3eae3);
    }

    .job-reject-form label {
        display: block;
        margin: 0 0 7px;
        color: #405448;
        font-size: 12px;
        font-weight: 700;
    }

    .job-reject-form textarea {
        display: block;
        width: 100%;
        min-height: 75px;
        margin-bottom: 8px;
        padding: 9px 10px;
        resize: vertical;
        border: 1px solid #dfe7df;
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: var(--sd-ink, #23342b);
        font: 400 12px/1.5 Inter, ui-sans-serif, system-ui, sans-serif;
    }

    .job-reject-form textarea:focus {
        border-color: #8bb69a;
        box-shadow: 0 0 0 3px rgba(40,118,83,.10);
    }

    .job-reject-form textarea::placeholder {
        color: #87938a;
    }

    /* Sudah diproses */
    .job-processed {
        color: #66766b;
        font-size: 12px;
        font-weight: 650;
    }

    /* Data kosong */
    .job-empty {
        padding: 38px 20px !important;
        color: var(--sd-muted, #758279) !important;
        font-size: 13px !important;
        text-align: center;
    }

    .job-empty strong {
        display: block;
        margin-bottom: 6px;
        color: var(--sd-green-950, #123d2d);
        font-size: 14px;
    }

    /* Kembali */
    .job-back {
        display: inline-flex;
        margin: 18px 0 5px;
        color: var(--sd-green-700, #287653);
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
    }

    .job-back:hover {
        color: var(--sd-green-900, #174b37);
    }

    @media(max-width:650px) {
        .job-heading h1 {
            font-size: 22px;
        }

        .job-panel-head {
            align-items: flex-start;
            flex-direction: column;
            padding: 16px;
        }

        .job-search-wrap {
            padding: 14px 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="job-page">

    <header class="job-heading">
        <p class="job-eyebrow">Verifikasi dan Publikasi</p>

        <h1>Lowongan Kerja</h1>

        <p>
            Periksa pengajuan lowongan kerja dari alumni sebelum
            ditampilkan pada halaman Info Loker.
        </p>
    </header>

    @if (session('status'))
        <div class="job-alert" role="status">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="job-error-box" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <section class="job-error-box" role="alert">
            <strong>Pengajuan belum berhasil diproses.</strong>

            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </section>
    @endif

    <section class="job-panel" aria-labelledby="job-list-heading">

        <div class="job-panel-head">
            <div>
                <h2 id="job-list-heading">Daftar Pengajuan Lowongan</h2>
                <p>
                    Periksa informasi lowongan dan tentukan keputusan publikasinya.
                </p>
            </div>

            <span class="job-total">
                {{ $lowongans->count() }} pengajuan
            </span>
        </div>

        <div class="job-search-wrap">
            <input
                id="jobSearch"
                class="job-search"
                type="search"
                placeholder="Cari nama perusahaan, posisi, atau kata kunci..."
                aria-label="Cari lowongan kerja"
            >
        </div>

        <div class="job-table-wrap">
            <table class="job-table">

                <thead>
                    <tr>
                        <th>Lowongan</th>
                        <th>Informasi Pekerjaan</th>
                        <th>Status</th>
                        <th>Detail</th>
                        <th>Keputusan Admin</th>
                    </tr>
                </thead>

                <tbody id="jobList">
                    @forelse ($lowongans as $lowongan)

                        @php
                            $searchText = strtolower(implode(' ', array_filter([
                                $lowongan->posisi,
                                $lowongan->nama_perusahaan,
                                $lowongan->bidang_karier,
                                $lowongan->lokasi,
                                $lowongan->jenis_pekerjaan ?? null,
                            ])));
                        @endphp

                        <tr class="job-row" data-search="{{ $searchText }}">

                            <td>
                                <strong class="job-position">
                                    {{ $lowongan->posisi }}
                                </strong>

                                <span class="job-company">
                                    {{ $lowongan->nama_perusahaan }}
                                </span>
                            </td>

                            <td>
                                <div class="job-meta">
                                    @if ($lowongan->jenis_pekerjaan ?? false)
                                        <span>
                                            Jenis: {{ $lowongan->jenis_pekerjaan }}
                                        </span>
                                    @elseif ($lowongan->bidang_karier)
                                        <span>
                                            Bidang: {{ $lowongan->bidang_karier }}
                                        </span>
                                    @endif

                                    @if ($lowongan->lokasi)
                                        <span>
                                            Lokasi: {{ $lowongan->lokasi }}
                                        </span>
                                    @endif

                                    @if ($lowongan->tanggal_tutup)
                                        <span>
                                            Batas lamaran:
                                            {{ $lowongan->tanggal_tutup->format('d-m-Y') }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <span class="job-status {{ $lowongan->status }}">
                                    {{ ucfirst($lowongan->status) }}
                                </span>
                            </td>

                            <td>
                                <details class="job-detail">
                                    <summary>Lihat Detail</summary>

                                    <div class="job-detail-content">
                                        <h3>Detail Lowongan</h3>

                                        <p>
                                            <strong>Deskripsi</strong><br>
                                            {{ $lowongan->deskripsi }}
                                        </p>

                                        @if ($lowongan->kualifikasi)
                                            <p>
                                                <strong>Kualifikasi</strong><br>
                                                {{ $lowongan->kualifikasi }}
                                            </p>
                                        @endif

                                        @if ($lowongan->kontak_lamaran)
                                            <p>
                                                <strong>Kontak lamaran</strong><br>
                                                {{ $lowongan->kontak_lamaran }}
                                            </p>
                                        @endif

                                        @if ($lowongan->tautan_lamaran)
                                            <p>
                                                <strong>Tautan lamaran</strong><br>
                                                <a
                                                    href="{{ $lowongan->tautan_lamaran }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    Buka tautan
                                                </a>
                                            </p>
                                        @endif

                                        @if ($lowongan->catatan_admin)
                                            <p>
                                                <strong>Catatan admin</strong><br>
                                                {{ $lowongan->catatan_admin }}
                                            </p>
                                        @endif

                                        <p>
                                            <strong>Pengaju</strong><br>
                                            {{ $lowongan->user?->name ?? 'Akun tidak tersedia' }}
                                        </p>

                                        @if ($lowongan->diproses_pada)
                                            <p>
                                                <strong>Diproses oleh</strong><br>
                                                {{ $lowongan->adminPemroses?->name ?? 'Admin tidak tersedia' }}
                                                ·
                                                {{ $lowongan->diproses_pada->format('d-m-Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                </details>
                            </td>

                            <td class="job-action">

                                @if ($lowongan->status === 'menunggu')

                                    <form
                                        class="job-form"
                                        action="{{ route('admin.job-vacancies.approve', $lowongan) }}"
                                        method="POST"
                                        onsubmit="return confirm('Setujui lowongan ini untuk dipublikasikan?')"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="job-button job-button-approve"
                                        >
                                            ✓ Setujui Lowongan
                                        </button>
                                    </form>

                                    <details class="job-detail">
                                        <summary>✕ Tolak Pengajuan</summary>

                                        <div class="job-detail-content">
                                            <form
                                                class="job-reject-form"
                                                action="{{ route('admin.job-vacancies.reject', $lowongan) }}"
                                                method="POST"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <label for="catatan_admin_{{ $lowongan->id }}">
                                                    Alasan penolakan *
                                                </label>

                                                <textarea
                                                    id="catatan_admin_{{ $lowongan->id }}"
                                                    name="catatan_admin"
                                                    required
                                                    placeholder="Jelaskan alasan penolakan..."
                                                >{{ old('catatan_admin') }}</textarea>

                                                <button
                                                    type="submit"
                                                    class="job-button job-button-reject"
                                                    onclick="return confirm('Tolak pengajuan lowongan ini?')"
                                                >
                                                    Tolak Pengajuan
                                                </button>
                                            </form>
                                        </div>
                                    </details>

                                @else

                                    <span class="job-processed">
                                        Sudah diproses
                                    </span>

                                    @if ($lowongan->diproses_pada)
                                        <p class="job-muted">
                                            {{ $lowongan->diproses_pada->format('d-m-Y H:i') }}
                                        </p>
                                    @endif

                                @endif

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td class="job-empty" colspan="5">
                                <strong>Belum Ada Pengajuan Lowongan</strong>
                                Pengajuan lowongan dari alumni akan muncul di halaman ini.
                            </td>
                        </tr>

                    @endforelse
                </tbody>

            </table>
        </div>

    </section>

    <a href="{{ route('dashboard') }}" class="job-back">
        ← Kembali ke Dashboard
    </a>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const search = document.getElementById('jobSearch');
    const rows = document.querySelectorAll('#jobList .job-row');

    if (!search) return;

    search.addEventListener('input', function () {
        const keyword = this.value.trim().toLowerCase();

        rows.forEach(function (row) {
            row.hidden = !row.dataset.search.includes(keyword);
        });
    });
});
</script>
@endpush