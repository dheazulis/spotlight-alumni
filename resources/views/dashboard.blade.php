@extends('layouts.dashboard')

@section('title', auth()->user()->role === 'pengelola_data'
    ? 'Dashboard Pengelola Data'
    : 'Dashboard Alumni')

@section('content')

<div class="dashboard-home">

    @if (session('status'))
        <div class="dh-alert">
            {{ session('status') }}
        </div>
    @endif

    {{-- ==================================================
         DASHBOARD PENGELOLA DATA
    ================================================== --}}

    @if (auth()->user()->role === 'pengelola_data')

        <section class="dh-welcome">
            <div>
                <span class="dh-eyebrow">PANEL PENGELOLA DATA</span>

                <h1>
                    Selamat Datang, {{ auth()->user()->name }}!
                </h1>

                <p>
                    Kelola dan pantau informasi alumni Fakultas Sains dan Teknologi
                    melalui Dashboard Pengelola Data.
                </p>
            </div>
        </section>

        {{-- KARTU STATISTIK --}}

        <section class="dh-section">

            <div class="dh-section-heading">
                <div>
                    <h2>Ringkasan Data</h2>
                    <p>Informasi singkat mengenai data alumni dan pengajuan alumni inspiratif.</p>
                </div>
            </div>

            <div class="dh-stats">

                <div class="dh-stat-card">
                    <div class="dh-stat-icon dh-icon-green">
                        <i class="fas fa-user-graduate">👥</i>
                    </div>

                    <div class="dh-stat-info">
                        <span>Total Alumni</span>
                        <h3>{{ number_format($totalAlumni, 0, ',', '.') }}</h3>
                        <small>Alumni terdaftar</small>
                    </div>
                </div>

                <div class="dh-stat-card">
                    <div class="dh-stat-icon dh-icon-blue">
                        <i class="fas fa-lightbulb">📄</i>
                    </div>

                    <div class="dh-stat-info">
                        <span>Total Pengajuan</span>
                        <h3>{{ number_format($totalPengajuan, 0, ',', '.') }}</h3>
                        <small>Pengajuan Alumni Inspiratif</small>
                    </div>
                </div>

                <div class="dh-stat-card">
                    <div class="dh-stat-icon dh-icon-orange">
                        <i class="fas fa-clock">◷</i>
                    </div>

                    <div class="dh-stat-info">
                        <span>Menunggu</span>
                        <h3>{{ number_format($pengajuanMenunggu, 0, ',', '.') }}</h3>
                        <small>Menunggu keputusan Admin</small>
                    </div>
                </div>

                <div class="dh-stat-card">
                    <div class="dh-stat-icon dh-icon-purple">
                        <i class="fas fa-check-circle">☑</i>
                    </div>

                    <div class="dh-stat-info">
                        <span>Disetujui</span>
                        <h3>{{ number_format($pengajuanDisetujui, 0, ',', '.') }}</h3>
                        <small>Pengajuan telah disetujui</small>
                    </div>
                </div>

            </div>
        </section>


        {{-- GRAFIK STATISTIK --}}

        <section class="dh-section">

            <div class="dh-section-heading">
                <div>
                    <h2>Visualisasi Data</h2>
                    <p>Grafik untuk membantu melihat persebaran data alumni dan pengajuan.</p>
                </div>
            </div>

            <div class="dh-charts">

                {{-- GRAFIK PENGAJUAN ALUMNI INSPIRATIF --}}

                <div class="dh-panel">
                    <div class="dh-panel-heading">
                        <div>
                            <h3>Status Pengajuan Alumni Inspiratif</h3>
                            <p>Perbandingan status seluruh pengajuan.</p>
                        </div>
                    </div>

                    @if ($totalPengajuan > 0)
                        <div class="dh-chart-container dh-doughnut-container">
                            <canvas id="pengajuanChart"></canvas>
                        </div>
                    @else
                        <div class="dh-empty">
                            Belum terdapat data pengajuan Alumni Inspiratif.
                        </div>
                    @endif

                    <div class="dh-chart-summary">
                        <div>
                            <span class="dh-dot dh-dot-orange"></span>
                            Menunggu
                            <strong>{{ $pengajuanMenunggu }}</strong>
                        </div>

                        <div>
                            <span class="dh-dot dh-dot-green"></span>
                            Disetujui
                            <strong>{{ $pengajuanDisetujui }}</strong>
                        </div>

                        <div>
                            <span class="dh-dot dh-dot-red"></span>
                            Ditolak
                            <strong>{{ $pengajuanDitolak }}</strong>
                        </div>
                    </div>
                </div>


                {{-- GRAFIK ALUMNI BERDASARKAN PRODI --}}

                <div class="dh-panel">
                    <div class="dh-panel-heading">
                        <div>
                            <h3>Alumni Berdasarkan Program Studi</h3>
                            <p>Jumlah alumni pada setiap program studi.</p>
                        </div>
                    </div>

                    @if ($alumniPerProdi->count() > 0)
                        <div class="dh-chart-container">
                            <canvas id="prodiChart"></canvas>
                        </div>
                    @else
                        <div class="dh-empty">
                            Belum terdapat data program studi alumni.
                        </div>
                    @endif
                </div>


                {{-- GRAFIK ALUMNI BERDASARKAN TAHUN LULUS --}}

                <div class="dh-panel">
                    <div class="dh-panel-heading">
                        <div>
                            <h3>Alumni Berdasarkan Tahun Lulus</h3>
                            <p>Persebaran jumlah alumni berdasarkan tahun kelulusan.</p>
                        </div>
                    </div>

                    @if ($alumniPerTahunLulus->count() > 0)
                        <div class="dh-chart-container">
                            <canvas id="tahunLulusChart"></canvas>
                        </div>
                    @else
                        <div class="dh-empty">
                            Belum terdapat data tahun lulus alumni.
                        </div>
                    @endif
                </div>


                {{-- GRAFIK STATUS SETELAH LULUS --}}

                <div class="dh-panel">
                    <div class="dh-panel-heading">
                        <div>
                            <h3>Status Alumni Setelah Lulus</h3>
                            <p>Gambaran kondisi alumni setelah menyelesaikan pendidikan.</p>
                        </div>
                    </div>

                    @if ($alumniPerStatus->count() > 0)
                        <div class="dh-chart-container dh-doughnut-container">
                            <canvas id="statusAlumniChart"></canvas>
                        </div>
                    @else
                        <div class="dh-empty">
                            Belum terdapat data status setelah lulus.
                        </div>
                    @endif
                </div>

            </div>
        </section>


    {{-- ==================================================
    DASHBOARD ALUMNI
    ================================================== --}}

    @elseif (auth()->user()->role === 'alumni')

        @php
            $alumni = auth()->user();

            $careerJourneys = $alumni->careerJourneys()
                ->orderByDesc('tanggal_mulai')
                ->get();
        @endphp

        <section class="dh-welcome">
            <div>
                <span class="dh-eyebrow">DASHBOARD ALUMNI</span>

                <h1>
                    Selamat Datang, {{ $alumni->name }}!
                </h1>

                <p>
                    Selamat datang di Sistem Informasi Alumni
                    Fakultas Sains dan Teknologi.
                </p>
            </div>
        </section>


        {{-- PROFIL ALUMNI --}}

        <section class="dh-profile-card">

            <div class="dh-profile-layout">

                {{-- IDENTITAS ALUMNI --}}
                <div class="dh-profile-top">

                    @if ($alumni->foto_profil)
                        <img
                            src="{{ asset('storage/' . $alumni->foto_profil) }}"
                            alt="Foto {{ $alumni->name }}"
                            class="dh-profile-photo"
                        >
                    @else
                        <div class="dh-profile-placeholder">
                            {{ strtoupper(substr($alumni->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="dh-profile-name">
                        <h2>{{ $alumni->name }}</h2>
                        <p>NIM: {{ $alumni->nim ?? '-' }}</p>
                        <p>{{ $alumni->program_studi ?? 'Program studi belum diisi' }}</p>
                        <p>Angkatan {{ $alumni->tahun_masuk ?? '-' }}</p>
                        <p>Alumni Tahun {{ $alumni->tahun_lulus ?? '-' }}</p>

                        <span class="dh-profile-status">
                            {{ $alumni->status_setelah_lulus ?? 'Status belum diisi' }}
                        </span>
                    </div>

                </div>

                {{-- TENTANG SAYA --}}
                <div class="dh-profile-about">
                    <h3>Tentang Saya</h3>

                    @if ($alumni->tentang_saya)
                        <div class="dh-profile-description">
                            {{ $alumni->tentang_saya }}
                        </div>
                    @else
                        <div class="dh-empty">
                            Kamu belum menambahkan informasi tentang dirimu.
                            Silakan lengkapi melalui menu Profil Saya.
                        </div>
                    @endif
                </div>

            </div>

            {{-- INFORMASI ALUMNI --}}
            <div class="dh-profile-section">
                <h3>Informasi Alumni</h3>

                <div class="dh-profile-data">

                    <div class="dh-data-item">
                        <small>Program Studi</small>
                        <strong>{{ $alumni->program_studi ?? '-' }}</strong>
                    </div>

                    <div class="dh-data-item">
                        <small>Tahun Masuk</small>
                        <strong>{{ $alumni->tahun_masuk ?? '-' }}</strong>
                    </div>

                    <div class="dh-data-item">
                        <small>Tahun Lulus</small>
                        <strong>{{ $alumni->tahun_lulus ?? '-' }}</strong>
                    </div>

                    <div class="dh-data-item">
                        <small>Nomor HP</small>
                        <strong>{{ $alumni->nomor_hp ?? '-' }}</strong>
                    </div>

                </div>
            </div>

            <a href="{{ route('alumni.profile') }}" class="dh-primary-button">
                Edit Profil Saya
            </a>

        </section>

        {{-- PERJALANAN KARIER --}}

        <section class="dh-panel dh-career-panel">

            <div class="dh-panel-heading">
                <div>
                    <h3>Perjalanan Karier</h3>
                    <p>Riwayat pengalaman dan perkembangan karier {{ $alumni->name }}.</p>
                </div>
            </div>

            @forelse ($careerJourneys as $career)

                <div class="dh-timeline">
                    <div class="dh-timeline-item">

                        <span class="dh-timeline-period">
                            {{ $career->tanggal_mulai?->format('Y') ?? 'Tahun tidak diisi' }}
                            —

                            @if ($career->masih_bekerja)
                                Sekarang
                            @else
                                {{ $career->tanggal_selesai?->format('Y') ?? 'Sekarang' }}
                            @endif
                        </span>

                        <h4>{{ $career->jabatan }}</h4>

                        <p>{{ $career->nama_perusahaan }}</p>

                        @if ($career->bidang_karier)
                            <p>Bidang: {{ $career->bidang_karier }}</p>
                        @endif

                        @if ($career->deskripsi)
                            <p>{{ $career->deskripsi }}</p>
                        @endif

                    </div>
                </div>

            @empty
                <div class="dh-empty">
                    Belum ada perjalanan karier yang ditambahkan.
                    Kamu bisa mulai mencatat pengalaman magang,
                    pekerjaan, maupun pengalaman lainnya.
                </div>
            @endforelse

            <a href="{{ route('alumni.career-journeys.index') }}" class="dh-primary-button">
                Kelola Perjalanan Karier
            </a>

        </section>
    @endif

</div> 

@endsection


{{-- ==================================================
     CSS DASHBOARD
================================================== --}}

@push('styles')
<style>
.dashboard-home {
    width: 100%;
    color: #263b32;
}

.dashboard-home * {
    box-sizing: border-box;
}

.dh-alert {
    padding: 14px 18px;
    margin-bottom: 22px;
    border-radius: 10px;
    background: #e8f4ec;
    color: #286344;
    border: 1px solid #cce5d4;
    font-size: 14px;
}

.dh-welcome {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 27px 30px;
    margin-bottom: 28px;
    background: #edf4ee;
    border: 1px solid #dce9de;
    border-radius: 14px;
}

.dh-eyebrow {
    display: inline-block;
    margin-bottom: 9px;
    color: #5b8066;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.2px;
}

.dh-welcome h1 {
    margin: 0 0 9px;
    color: #244d37;
    font-size: 25px;
    font-weight: 700;
}

.dh-welcome p {
    margin: 0;
    color: #718077;
    font-size: 14px;
    line-height: 1.7;
}

.dh-section {
    margin-bottom: 30px;
}

.dh-section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 17px;
}

.dh-section-heading h2 {
    margin: 0 0 6px;
    color: #294b38;
    font-size: 18px;
    font-weight: 700;
}

.dh-section-heading p {
    margin: 0;
    color: #849087;
    font-size: 13px;
    line-height: 1.6;
}


/* STATISTIK */

.dh-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 17px;
}

.dh-stat-card {
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
    padding: 20px;
    background: #fff;
    border: 1px solid #e7ece7;
    border-radius: 13px;
    box-shadow: 0 3px 12px rgba(35, 65, 45, .035);
}

.dh-stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 46px;
    height: 46px;
    border-radius: 12px;
    font-size: 18px;
}

.dh-icon-green {
    background: #e7f2e9;
    color: #477b55;
}

.dh-icon-blue {
    background: #e9f0f8;
    color: #527da5;
}

.dh-icon-orange {
    background: #fff1df;
    color: #c58a43;
}

.dh-icon-purple {
    background: #f0eafb;
    color: #8565b4;
}

.dh-stat-info {
    min-width: 0;
}

.dh-stat-info span {
    display: block;
    margin-bottom: 7px;
    color: #7b887e;
    font-size: 12px;
}

.dh-stat-info h3 {
    margin: 0 0 5px;
    color: #294b38;
    font-size: 25px;
    font-weight: 700;
}

.dh-stat-info small {
    color: #9aa49c;
    font-size: 11px;
}


/* GRAFIK */

.dh-charts {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.dh-panel {
    min-width: 0;
    padding: 22px;
    margin-bottom: 18px;
    background: #fff;
    border: 1px solid #e7ece7;
    border-radius: 13px;
    box-shadow: 0 3px 12px rgba(35, 65, 45, .035);
}

.dh-panel-heading {
    margin-bottom: 18px;
}

.dh-panel-heading h3 {
    margin: 0 0 6px;
    color: #294b38;
    font-size: 15px;
    font-weight: 700;
}

.dh-panel-heading p {
    margin: 0;
    color: #8a958d;
    font-size: 12px;
    line-height: 1.6;
}

.dh-chart-container {
    position: relative;
    width: 100%;
    height: 260px;
}

.dh-doughnut-container {
    max-width: 340px;
    margin: 0 auto;
}

.dh-chart-summary {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 13px;
    margin-top: 18px;
}

.dh-chart-summary div {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #748078;
    font-size: 11px;
}

.dh-chart-summary strong {
    margin-left: 3px;
    color: #344d3c;
}

.dh-dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
}

.dh-dot-orange {
    background: #e6aa61;
}

.dh-dot-green {
    background: #639675;
}

.dh-dot-red {
    background: #d77c78;
}

.dh-empty {
    padding: 17px;
    background: #f5f8f5;
    border-radius: 9px;
    color: #849087;
    font-size: 13px;
    line-height: 1.7;
}


/* MENU */

.dh-menu-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 17px;
}

.dh-menu-card {
    display: flex;
    align-items: flex-start;
    gap: 17px;
    min-width: 0;
    padding: 22px;
    background: #fff;
    border: 1px solid #e7ece7;
    border-radius: 13px;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 3px 12px rgba(35, 65, 45, .035);
    transition: .2s ease;
}

.dh-menu-card:hover {
    transform: translateY(-3px);
    border-color: #b8d0bd;
    box-shadow: 0 7px 18px rgba(35, 65, 45, .08);
}

.dh-menu-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 46px;
    height: 46px;
    border-radius: 12px;
    font-size: 18px;
}

.dh-menu-green {
    background: #e7f2e9;
    color: #477b55;
}

.dh-menu-blue {
    background: #e9f0f8;
    color: #527da5;
}

.dh-menu-purple {
    background: #f0eafb;
    color: #8565b4;
}

.dh-menu-orange {
    background: #fff1df;
    color: #c58a43;
}

.dh-menu-content {
    min-width: 0;
}

.dh-menu-content h3 {
    margin: 1px 0 8px;
    color: #294b38;
    font-size: 15px;
    font-weight: 700;
}

.dh-menu-content p {
    margin: 0 0 13px;
    color: #849087;
    font-size: 12px;
    line-height: 1.7;
}

.dh-menu-content span {
    color: #548064;
    font-size: 12px;
    font-weight: 700;
}

.dh-menu-content span i {
    margin-left: 4px;
    font-size: 10px;
}


/* PROFIL ALUMNI */

.dh-profile-card {
    padding: 25px;
    margin-bottom: 23px;
    background: #fff;
    border: 1px solid #e7ece7;
    border-radius: 13px;
    box-shadow: 0 3px 12px rgba(35, 65, 45, .035);
}

.dh-profile-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    gap: 30px;
    margin-bottom: 22px;
}

.dh-profile-top {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 22px;
    min-width: 0;
    margin-bottom: 0;
}

.dh-profile-about {
    min-width: 0;
    min-height: 145px;
    padding: 15px 0 15px 30px;
    border-left: 1px solid #edf0ed;
}

.dh-profile-about h3 {
    margin: 0 0 13px;
    color: #355b42;
    font-size: 15px;
}

.dh-profile-section {
    padding-top: 19px;
    margin-top: 19px;
    border-top: 1px solid #edf0ed;
}

.dh-profile-section h3 {
    margin: 0 0 13px;
    color: #355b42;
    font-size: 15px;
}

@media (max-width: 760px) {
    .dh-profile-layout {
        grid-template-columns: 1fr;
        gap: 22px;
    }

    .dh-profile-about {
        min-height: auto;
        padding: 20px 0 0;
        border-left: none;
        border-top: 1px solid #edf0ed;
    }
}

.dh-profile-photo,
.dh-profile-placeholder {
    width: 115px;
    height: 115px;
    flex-shrink: 0;
    border-radius: 50%;
}

.dh-profile-photo {
    object-fit: cover;
    border: 4px solid #e7f2e9;
}

.dh-profile-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e7f2e9;
    color: #477b55;
    font-size: 36px;
    font-weight: 700;
}

.dh-profile-name h2 {
    margin: 0 0 8px;
    color: #294b38;
    font-size: 21px;
}

.dh-profile-name p {
    margin: 5px 0;
    color: #849087;
    font-size: 13px;
}

.dh-profile-status {
    display: inline-block;
    margin-top: 7px;
    padding: 6px 11px;
    background: #e7f2e9;
    color: #477b55;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.dh-profile-section {
    padding-top: 19px;
    margin-top: 19px;
    border-top: 1px solid #edf0ed;
}

.dh-profile-section h3 {
    margin: 0 0 13px;
    color: #355b42;
    font-size: 15px;
}

.dh-profile-description {
    color: #69776d;
    font-size: 13px;
    line-height: 1.8;
    white-space: pre-line;
}

.dh-profile-data {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 13px;
}

.dh-data-item {
    padding: 14px;
    background: #f5f8f5;
    border-radius: 9px;
}

.dh-data-item small {
    display: block;
    margin-bottom: 7px;
    color: #849087;
    font-size: 11px;
}

.dh-data-item strong {
    color: #344d3c;
    font-size: 13px;
}

.dh-primary-button {
    display: inline-block;
    margin-top: 19px;
    padding: 10px 16px;
    background: #477b55;
    color: #fff;
    text-decoration: none;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
}

.dh-primary-button:hover {
    background: #365f42;
}


/* TIMELINE KARIER */

.dh-career-panel {
    margin-bottom: 28px;
}

.dh-timeline {
    position: relative;
    margin: 20px 0 10px 8px;
    padding-left: 25px;
    border-left: 3px solid #dce9de;
}

.dh-timeline-item {
    position: relative;
    margin-bottom: 25px;
}

.dh-timeline-item:last-child {
    margin-bottom: 5px;
}

.dh-timeline-item::before {
    content: "";
    position: absolute;
    left: -34px;
    top: 5px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #639675;
    border: 3px solid #e7f2e9;
}

.dh-timeline-period {
    display: inline-block;
    margin-bottom: 7px;
    color: #548064;
    font-size: 12px;
    font-weight: 700;
}

.dh-timeline-item h4 {
    margin: 0 0 5px;
    color: #344d3c;
    font-size: 14px;
}

.dh-timeline-item p {
    margin: 5px 0;
    color: #849087;
    font-size: 12px;
    line-height: 1.7;
}


/* RESPONSIVE */

@media (max-width: 1100px) {
    .dh-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dh-profile-data {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 760px) {
    .dh-charts {
        grid-template-columns: 1fr;
    }

    .dh-menu-grid {
        grid-template-columns: 1fr;
    }

    .dh-welcome {
        padding: 22px;
    }

    .dh-welcome h1 {
        font-size: 21px;
    }
}

@media (max-width: 480px) {
    .dh-stats {
        grid-template-columns: 1fr;
    }

    .dh-profile-data {
        grid-template-columns: 1fr 1fr;
    }

    .dh-profile-top {
        align-items: flex-start;
    }

    .dh-profile-photo,
    .dh-profile-placeholder {
        width: 85px;
        height: 85px;
    }

    .dh-menu-card {
        padding: 17px;
    }
}
</style>
@endpush


{{-- ==================================================
     JAVASCRIPT GRAFIK
================================================== --}}
@if (auth()->user()->role === 'pengelola_data')
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const green = '#639675';
    const blue = '#7899b8';
    const orange = '#e6aa61';
    const red = '#d77c78';
    const purple = '#9a82bd';

    const chartTextColor = '#849087';

    Chart.defaults.font.family = 'Arial, sans-serif';
    Chart.defaults.color = chartTextColor;


    // ==========================================
    // GRAFIK STATUS PENGAJUAN
    // ==========================================

    const pengajuanCanvas = document.getElementById('pengajuanChart');

    if (pengajuanCanvas) {

        new Chart(pengajuanCanvas, {
            type: 'doughnut',

            data: {
                labels: ['Menunggu', 'Disetujui', 'Ditolak'],

                datasets: [{
                    data: [
                        {{ $pengajuanMenunggu }},
                        {{ $pengajuanDisetujui }},
                        {{ $pengajuanDitolak }}
                    ],

                    backgroundColor: [orange, green, red],
                    borderWidth: 0,
                    hoverOffset: 5
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 18
                        }
                    }
                },

                cutout: '68%'
            }
        });
    }


    // ==========================================
    // GRAFIK ALUMNI PER PROGRAM STUDI
    // ==========================================

    const prodiCanvas = document.getElementById('prodiChart');

    if (prodiCanvas) {

        new Chart(prodiCanvas, {
            type: 'bar',

            data: {
                labels: @json($alumniPerProdi->pluck('program_studi')),

                datasets: [{
                    label: 'Jumlah Alumni',
                    data: @json($alumniPerProdi->pluck('total')),
                    backgroundColor: green,
                    borderRadius: 6,
                    maxBarThickness: 45
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: '#edf1ed'
                        }
                    },

                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }


    // ==========================================
    // GRAFIK ALUMNI PER TAHUN LULUS
    // ==========================================

    const tahunCanvas = document.getElementById('tahunLulusChart');

    if (tahunCanvas) {

        new Chart(tahunCanvas, {
            type: 'bar',

            data: {
                labels: @json($alumniPerTahunLulus->pluck('tahun_lulus')),

                datasets: [{
                    label: 'Jumlah Alumni',
                    data: @json($alumniPerTahunLulus->pluck('total')),
                    backgroundColor: blue,
                    borderRadius: 6,
                    maxBarThickness: 45
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: '#edf1ed'
                        }
                    },

                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }


    // ==========================================
    // GRAFIK STATUS SETELAH LULUS
    // ==========================================

    const statusCanvas = document.getElementById('statusAlumniChart');

    if (statusCanvas) {

        new Chart(statusCanvas, {
            type: 'doughnut',

            data: {
                labels: @json($alumniPerStatus->pluck('status')),

                datasets: [{
                    data: @json($alumniPerStatus->pluck('total')),
                    backgroundColor: [
                        green,
                        blue,
                        orange,
                        purple,
                        red,
                        '#91aaa0',
                        '#c2a58b'
                    ],
                    borderWidth: 0,
                    hoverOffset: 5
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 14
                        }
                    }
                },

                cutout: '68%'
            }
        });
    }

});
</script>
@endpush
@endif