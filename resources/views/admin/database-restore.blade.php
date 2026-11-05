@extends('layouts.dashboard')

@section('title', 'Restore Database')
@section('breadcrumb', 'Restore Database')

@push('styles')
<style>
    .restore-content,
    .restore-content * {
        box-sizing: border-box;
    }

    .restore-content {
        color: #26344b;
        font-family: Arial, sans-serif;
    }

    .restore-content main {
        max-width: 1100px;
        margin: auto;
    }

    .restore-content h1 {
        color: #26344b;
    }

    .restore-content .description {
        color: #68758a;
        margin-bottom: 25px;
    }

    .restore-content .card {
        background: white;
        border: 1px solid #e4f0e8;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
    }

    .restore-content .backup-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        border: 1px solid #e4f0e8;
        border-radius: 8px;
        padding: 16px;
        margin-top: 12px;
    }

    .restore-content button {
        border: none;
        border-radius: 7px;
        padding: 11px 18px;
        background: #b42318;
        color: white;
        cursor: pointer;
        font-weight: bold;
    }

    .restore-content button:hover {
        background: #912018;
    }

    .restore-content .warning {
        background: #fff4e5;
        color: #92400e;
        border: 1px solid #f5d08a;
        padding: 15px;
        border-radius: 8px;
        line-height: 1.5;
    }

    .restore-content .success {
        background: #e8f7ed;
        color: #166534;
        padding: 13px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .restore-content .error {
        background: #fdecec;
        color: #991b1b;
        padding: 13px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .restore-content .back {
        display: inline-block;
        margin-top: 15px;
        color: #1d6045;
        text-decoration: none;
    }
</style>
@endpush

@section('content')
<div class="restore-content">
    <main>
        <h1>Restore Database</h1>

        <p class="description">
            Pilih file backup yang ingin digunakan untuk memulihkan database.
        </p>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="card">
            <h2>Daftar File Backup</h2>

            <div class="warning">
                <strong>Perhatian!</strong>
                Restore akan mengganti data database saat ini dengan data
                dari file backup yang dipilih. Sistem akan membuat backup
                pengaman terlebih dahulu sebelum proses pemulihan.
            </div>

            @forelse ($backups as $backup)
                <div class="backup-item">
                    <span>{{ basename($backup) }}</span>

                    <form
                        action="{{ route('admin.database-restore.restore') }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin memulihkan database menggunakan file ini? Pastikan kamu sudah memahami risikonya.');"
                    >
                        @csrf
                        <input type="hidden" name="backup" value="{{ $backup }}">

                        <button type="submit">Restore Database</button>
                    </form>
                </div>
            @empty
                <p>Belum ada file backup yang tersedia.</p>
            @endforelse
        </div>

        <a href="{{ route('dashboard') }}" class="back">
            ← Kembali ke Dashboard
        </a>
    </main>
</div>
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
