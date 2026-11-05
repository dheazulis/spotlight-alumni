
@extends('layouts.dashboard')

@section('title', 'BackUp Database')
@section('breadcrumb', 'BackUp Database')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #f5f7f3;
        color: #26344b;
        font-family: Arial, sans-serif;
    }

    header {
        background: #1d6045;
        color: white;
        padding: 22px 30px;
    }

    header h1 {
        margin: 0 0 5px;
        font-size: 25px;
    }

    main {
        max-width: 1100px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .card {
        border: 1px solid #e4f0e8;
        border-radius: 12px;
    }

    .section-title {
        color: #26344b;
        font-weight: 600;
    }
</style>
@endpush

@section('content')

<header>
    <h1>Backup Database</h1>
    <p class="mb-0">Spotlight Alumni Fakultas Sains dan Teknologi</p>
</header>

<main>

    <div class="mb-4">
        <h2>Kelola Backup Database</h2>
        <p class="text-muted">
            Buat dan unduh salinan database untuk menjaga keamanan data sistem.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- MEMBUAT BACKUP --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">

            <h5 class="section-title mb-2">Buat Backup Database</h5>

            <p class="text-muted">
                Sistem akan membuat salinan database dalam format ZIP.
            </p>

            <form action="{{ route('database-backup.backup') }}"
                  method="POST"
                  onsubmit="return confirm('Yakin ingin membuat backup database sekarang?')">

                @csrf

                <button type="submit" class="btn btn-primary">
                    Buat Backup Sekarang
                </button>

            </form>

        </div>
    </div>

    {{-- DAFTAR BACKUP --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">

            <h5 class="section-title mb-2">Daftar File Backup</h5>

            <p class="text-muted">
                Daftar seluruh file backup ZIP yang tersimpan di sistem.
            </p>

            @if ($backups->count() > 0)

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">

                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama File</th>
                                <th>Ukuran</th>
                                <th>Tanggal Backup</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($backups as $index => $backup)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $backup['name'] }}</td>
                                    <td>
                                        {{ number_format($backup['size'] / 1024, 2) }} KB
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::createFromTimestamp($backup['date'])->format('d-m-Y H:i:s') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('database-backup.download', $backup['name']) }}"
                                           class="btn btn-success btn-sm">
                                            Download ZIP
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            @else

                <div class="text-center py-5">
                    <h5>Belum Ada File Backup</h5>
                    <p class="text-muted">
                        Silakan buat backup database terlebih dahulu.
                    </p>
                </div>

            @endif

        </div>
    </div>

    {{-- MENU RESTORE KHUSUS ADMIN --}}
    @if (auth()->user()->role === 'admin')
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">

                <h5 class="section-title mb-2 text-danger">
                    Restore Database
                </h5>

                <p class="text-muted">
                    Pulihkan database menggunakan salah satu file backup ZIP
                    yang telah tersimpan.
                </p>

                <a href="{{ route('admin.database-restore.index') }}"
                   class="btn btn-danger">
                    Buka Halaman Restore
                </a>

            </div>
        </div>
    @endif

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-2">
        Kembali ke Dashboard
    </a>

</main>

@endsection


@push('styles')
<style>
/* TEMA ADMIN SERAGAM - mengikuti halaman alumni */
:root { --admin-green:#198754; --admin-green-hover:#157347; --admin-text:#263449; --admin-muted:#7b8493; --admin-border:#e7ebf0; --admin-bg:#f7f9f8; }
/* Semua halaman admin */
.ad-page,.job-page,.restore-content,.agenda-admin,.da-page,.fa-page,.ai-page { color:var(--admin-text)!important; }
.ad-welcome h1,.ad-section-title h2,.admin-section-heading h2,.agenda-admin-head h1,.agenda-admin-title,.job-heading h1,.da-heading h1,.fa-heading h1,.ai-heading h1,.rekap-header h1 { color:var(--admin-text)!important; }
.ad-welcome p,.ad-section-title p,.admin-section-heading p,.agenda-admin-head p,.job-heading p,.da-heading p,.fa-card-description,.ai-muted,.rekap-header p { color:var(--admin-muted)!important; }
.ad-stat,.ad-panel,.admin-menu-card,.agenda-admin-panel,.da-filter-panel,.da-table-card,.fa-card,.ai-panel,.restore-content .card,.job-row,.job-empty,.rekap-card,.rekap-filter,.rekap-list { background:#fff!important; border:1px solid var(--admin-border)!important; border-radius:12px!important; box-shadow:0 2px 8px rgba(30,55,40,.035)!important; }
.ad-stat.ad-stat-main { background:#f0f7f2!important; border-color:#dcebe1!important; }
.ad-stat-label,.ad-stat-note,.ad-panel p,.admin-menu-card p { color:var(--admin-muted)!important; }
.ad-stat-value,.ad-panel h3,.admin-menu-card h3 { color:var(--admin-text)!important; }
/* Tombol utama dan edit */
.btn-primary,.btn-success,.btn-save,.fa-button-primary,.da-primary-button,.job-btn-approve,.ai-button-approve,.rekap-pdf,.rekap-excel,.btn-add,.btn-edit { background:var(--admin-green)!important; border:1px solid var(--admin-green)!important; color:#fff!important; border-radius:7px!important; }
.btn-primary:hover,.btn-success:hover,.btn-save:hover,.fa-button-primary:hover,.da-primary-button:hover,.job-btn-approve:hover,.ai-button-approve:hover,.rekap-pdf:hover,.rekap-excel:hover,.btn-add:hover,.btn-edit:hover { background:var(--admin-green-hover)!important; border-color:var(--admin-green-hover)!important; color:#fff!important; }
/* Tombol hapus/tolak tetap merah */
.btn-danger,.btn-delete,.da-icon-action.danger,.ai-button-reject,.job-btn-reject,.admin-logout-button { background:#fff0f0!important; border:1px solid #f5d5d5!important; color:#d14343!important; border-radius:7px!important; }
.btn-danger:hover,.btn-delete:hover,.da-icon-action.danger:hover,.ai-button-reject:hover,.job-btn-reject:hover { background:#ffe1e1!important; color:#bd3030!important; }
/* Tombol sekunder */
.btn-outline-secondary,.btn-cancel,.fa-button-secondary,.da-reset-button,.btn-secondary { background:#fff!important; color:#596579!important; border:1px solid #dfe4ea!important; border-radius:7px!important; }
/* Form */
.form-control,.form-select,.da-filter-field select,.da-search-control,.job-search,.fa-form-group input,.fa-form-group select,.fa-form-group textarea { border:1px solid #dfe4ea!important; border-radius:7px!important; color:var(--admin-text)!important; box-shadow:none!important; }
.form-control:focus,.form-select:focus,.da-filter-field select:focus,.da-search-control:focus,.job-search:focus { border-color:#72b58f!important; box-shadow:0 0 0 3px rgba(25,135,84,.10)!important; }
.form-label,.da-filter-field span,.fa-form-group label { color:#39465a!important; font-weight:600!important; }
/* Tabel */
.da-table,.ai-table,.agenda-admin table,.restore-content table { width:100%; border-collapse:collapse; }
.da-table thead th,.ai-table thead th,.agenda-admin table thead th,.restore-content table thead th { background:#f5f8f6!important; color:#39465a!important; border-bottom:1px solid var(--admin-border)!important; font-weight:700!important; }
.da-table td,.da-table th,.ai-table td,.ai-table th,.agenda-admin table td,.agenda-admin table th,.restore-content table td,.restore-content table th { border-color:var(--admin-border)!important; }
.da-table tbody tr:hover,.ai-table tbody tr:hover,.agenda-admin table tbody tr:hover { background:#f8fbf9!important; }
/* Label status */
.badge.bg-success,.da-status,.ai-status { border-radius:20px!important; }
.alert-success,.da-alert-success,.alert.alert-success { background:#eaf7ef!important; border:1px solid #d5ecdd!important; color:#187344!important; border-radius:8px!important; }
.alert-danger,.alert.alert-danger,.da-alert-error,.ai-error-box { background:#fff0f0!important; border:1px solid #f5d5d5!important; color:#bd3030!important; border-radius:8px!important; }
/* Pagination */
.pagination .page-link { color:var(--admin-green)!important; border-color:var(--admin-border)!important; }
.pagination .page-item.active .page-link { background:var(--admin-green)!important; border-color:var(--admin-green)!important; color:#fff!important; }
/* Halaman agenda: hindari header warna blok yang berbeda */
.agenda-admin-head { background:#fff!important; border-bottom:1px solid var(--admin-border)!important; padding:0 0 20px!important; margin:0 0 22px!important; }
.agenda-admin-panel-head { background:#f5f8f6!important; color:var(--admin-text)!important; }
@media(max-width:700px) { .ad-stats{grid-template-columns:repeat(2,minmax(0,1fr))!important;} .admin-menu-grid{grid-template-columns:1fr!important;} }

</style>
@endpush
