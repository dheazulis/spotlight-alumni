
@extends('layouts.dashboard')

@section('title', 'Pengajuan Lowongan Kerja')
@section('breadcrumb', 'Pengajuan Lowongan Kerja')

@section('content')
<div class="job-page">

    <div class="page-heading">
        <div>
            <h1>Pengajuan Informasi Lowongan Kerja</h1>
            <p>Kelola dan pantau status pengajuan lowongan kerja yang kamu kirim.</p>
        </div>

        <a href="{{ route('alumni.job-vacancies.create') }}" class="btn-primary">
            + Ajukan Lowongan
        </a>
    </div>

    @if (session('status'))
        <div class="notice">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="error-notice">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-card">
        <div class="table-heading">
            <div>
                <h2>Daftar Pengajuan</h2>
                <p>Informasi lowongan kerja yang telah kamu ajukan.</p>
            </div>

            <span class="total-data">
                Total: {{ $lowongans->count() }} pengajuan
            </span>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Perusahaan</th>
                        <th>Posisi Pekerjaan</th>
                        <th>Bidang Karier</th>
                        <th>Lokasi</th>
                        <th>Batas Lamaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($lowongans as $lowongan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td class="company-name">
                                {{ $lowongan->nama_perusahaan }}
                            </td>

                            <td>{{ $lowongan->posisi }}</td>

                            <td>
                                {{ $lowongan->bidang_karier ?: '-' }}
                            </td>

                            <td>
                                {{ $lowongan->lokasi ?: '-' }}
                            </td>

                            <td>
                                {{ $lowongan->tanggal_tutup
                                    ? $lowongan->tanggal_tutup->format('d-m-Y')
                                    : '-' }}
                            </td>

                            <td>
                                @if ($lowongan->status === 'menunggu')
                                    <span class="status menunggu">
                                        Menunggu
                                    </span>
                                @elseif ($lowongan->status === 'disetujui')
                                    <span class="status disetujui">
                                        Disetujui
                                    </span>
                                @else
                                    <span class="status ditolak">
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="action-buttons">
                                    <button
                                        type="button"
                                        class="btn-detail"
                                        onclick="openDetail('detail-{{ $lowongan->id }}')"
                                    >
                                        Detail
                                    </button>

                                    @if ($lowongan->status !== 'disetujui')
                                        <form
                                            action="{{ route('alumni.job-vacancies.destroy', $lowongan) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn-delete">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <tr
                            id="detail-{{ $lowongan->id }}"
                            class="detail-row"
                            style="display: none;"
                        >
                            <td colspan="8">
                                <div class="detail-content">
                                    <h3>Detail Pengajuan Lowongan</h3>

                                    <div class="detail-grid">
                                        <div>
                                            <strong>Perusahaan/Instansi</strong>
                                            <p>{{ $lowongan->nama_perusahaan }}</p>
                                        </div>

                                        <div>
                                            <strong>Posisi Pekerjaan</strong>
                                            <p>{{ $lowongan->posisi }}</p>
                                        </div>

                                        <div>
                                            <strong>Bidang Karier</strong>
                                            <p>{{ $lowongan->bidang_karier ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <strong>Lokasi</strong>
                                            <p>{{ $lowongan->lokasi ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <strong>Kontak Lamaran</strong>
                                            <p>{{ $lowongan->kontak_lamaran ?: '-' }}</p>
                                        </div>

                                        <div>
                                            <strong>Batas Akhir Lamaran</strong>
                                            <p>
                                                {{ $lowongan->tanggal_tutup
                                                    ? $lowongan->tanggal_tutup->format('d-m-Y')
                                                    : '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="detail-text">
                                        <strong>Deskripsi Pekerjaan</strong>
                                        <p>{{ $lowongan->deskripsi }}</p>
                                    </div>

                                    <div class="detail-text">
                                        <strong>Kualifikasi/Persyaratan</strong>
                                        <p>{{ $lowongan->kualifikasi ?: '-' }}</p>
                                    </div>

                                    @if ($lowongan->tautan_lamaran)
                                        <div class="detail-text">
                                            <strong>Tautan Lamaran</strong>
                                            <p>
                                                <a
                                                    href="{{ $lowongan->tautan_lamaran }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    Buka Tautan Lamaran
                                                </a>
                                            </p>
                                        </div>
                                    @endif

                                    @if ($lowongan->catatan_admin)
                                        <div class="admin-note">
                                            <strong>Catatan Admin</strong>
                                            <p>{{ $lowongan->catatan_admin }}</p>
                                        </div>
                                    @endif

                                    <button
                                        type="button"
                                        class="btn-close"
                                        onclick="openDetail('detail-{{ $lowongan->id }}')"
                                    >
                                        Tutup Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-data">
                                <h3>Belum Ada Pengajuan</h3>
                                <p>
                                    Kamu belum mengajukan lowongan kerja.
                                    Klik tombol Ajukan Lowongan untuk memulai.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .job-page {
        width: 100%;
        color: #26344b;
    }

    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 22px;
    }

    .page-heading h1 {
        margin: 0 0 8px;
        color: #1d6045;
        font-size: 25px;
    }

    .page-heading p {
        margin: 0;
        color: #68758a;
        font-size: 14px;
        line-height: 1.6;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 16px;
        border-radius: 7px;
        background: #1d6045;
        color: white;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-primary:hover {
        background: #164b35;
    }

    .table-card {
        width: 100%;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e4f0e8;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(29, 96, 69, 0.04);
    }

    .table-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 22px 24px;
        border-bottom: 1px solid #edf1ed;
    }

    .table-heading h2 {
        margin: 0 0 6px;
        color: #26344b;
        font-size: 17px;
    }

    .table-heading p {
        margin: 0;
        color: #68758a;
        font-size: 13px;
    }

    .total-data {
        padding: 7px 12px;
        border-radius: 7px;
        background: #e8f5e9;
        color: #276738;
        font-size: 12px;
        font-weight: 600;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
        text-align: left;
    }

    thead {
        background: #f5f7f3;
    }

    th {
        padding: 15px 13px;
        color: #526176;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        border-bottom: 1px solid #e4f0e8;
    }

    td {
        padding: 15px 13px;
        color: #526176;
        font-size: 13px;
        border-bottom: 1px solid #edf1ed;
        vertical-align: middle;
    }

    tbody tr:not(.detail-row):hover {
        background: #fafcf9;
    }

    .company-name {
        color: #26344b;
        font-weight: 600;
    }

    .status {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status.menunggu {
        background: #fff1cc;
        color: #875d00;
    }

    .status.disetujui {
        background: #d9f5e2;
        color: #216b3b;
    }

    .status.ditolak {
        background: #fee2e2;
        color: #a61b1b;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .action-buttons form {
        margin: 0;
    }

    .btn-detail,
    .btn-delete,
    .btn-close {
        padding: 7px 10px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-detail {
        background: #e4f0e8;
        color: #1d6045;
    }

    .btn-detail:hover {
        background: #d4e8da;
    }

    .btn-delete {
        background: #fee2e2;
        color: #a61b1b;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    .detail-row td {
        padding: 0;
        background: #fafcf9;
    }

    .detail-content {
        padding: 23px;
        border-bottom: 1px solid #e4f0e8;
    }

    .detail-content h3 {
        margin: 0 0 18px;
        color: #1d6045;
        font-size: 17px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px;
        margin-bottom: 20px;
    }

    .detail-grid strong,
    .detail-text strong {
        color: #26344b;
        font-size: 13px;
    }

    .detail-grid p,
    .detail-text p {
        margin: 6px 0 0;
        color: #68758a;
        font-size: 13px;
        line-height: 1.7;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .detail-text {
        margin-bottom: 17px;
    }

    .detail-text a {
        color: #1d6045;
        font-weight: 600;
        text-decoration: none;
    }

    .admin-note {
        padding: 13px 15px;
        margin: 15px 0;
        border-left: 4px solid #1d6045;
        border-radius: 7px;
        background: #e8f5e9;
    }

    .admin-note strong {
        color: #276738;
        font-size: 13px;
    }

    .admin-note p {
        margin: 7px 0 0;
        color: #276738;
        font-size: 13px;
        line-height: 1.6;
    }

    .btn-close {
        margin-top: 5px;
        background: #64748b;
        color: white;
    }

    .empty-data {
        padding: 45px 20px;
        text-align: center;
    }

    .empty-data h3 {
        margin: 0 0 8px;
        color: #1d6045;
        font-size: 17px;
    }

    .empty-data p {
        margin: 0;
        color: #68758a;
        font-size: 13px;
    }

    .notice,
    .error-notice {
        padding: 13px 15px;
        margin-bottom: 18px;
        border-radius: 8px;
        font-size: 14px;
    }

    .notice {
        background: #e8f5e9;
        color: #276738;
    }

    .error-notice {
        background: #fee2e2;
        color: #a61b1b;
    }

    @media (max-width: 600px) {
        .page-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .page-heading h1 {
            font-size: 22px;
        }

        .table-heading {
            padding: 18px 16px;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-content {
            padding: 18px 14px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function openDetail(id) {
        const detail = document.getElementById(id);

        if (!detail) return;

        detail.style.display =
            detail.style.display === 'none' ? 'table-row' : 'none';
    }
</script>
@endpush
