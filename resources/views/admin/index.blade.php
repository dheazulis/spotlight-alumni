
@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('breadcrumb', 'Dashboard Admin')

@push('styles')
<style>
    .ad-page {
        color: var(--admin-text, #263449);
    }

    /* Header */
    .ad-welcome {
        margin-bottom: 28px;
    }

    .ad-eyebrow {
        margin: 0 0 7px;
        color: var(--admin-green, #198754);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .07em;
        text-transform: uppercase;
    }

    .ad-welcome h1 {
        margin: 0 0 8px;
        color: var(--admin-text, #263449);
        font-size: 26px;
        line-height: 1.3;
        font-weight: 800;
    }

    .ad-welcome p {
        margin: 0;
        color: var(--admin-muted, #7b8493);
        font-size: 14px;
        line-height: 1.7;
    }

    /* Judul bagian */
    .ad-section-title {
        margin: 27px 0 15px;
    }

    .ad-section-title h2 {
        margin: 0 0 5px;
        color: var(--admin-text, #263449);
        font-size: 19px;
        font-weight: 800;
    }

    .ad-section-title p {
        margin: 0;
        color: var(--admin-muted, #7b8493);
        font-size: 13px;
        line-height: 1.6;
    }

    /* Statistik */
    .ad-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
    }

    .ad-stat {
        min-width: 0;
        padding: 19px;
        border: 1px solid var(--admin-border, #e7ebf0);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(30, 55, 40, .035);
    }

    .ad-stat.ad-stat-main {
        background: #f0f7f2;
        border-color: #dcebe1;
    }

    .ad-stat-label {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 13px;
        color: var(--admin-muted, #7b8493);
        font-size: 13px;
        font-weight: 700;
        line-height: 1.4;
    }

    .ad-stat-icon {
        display: grid;
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        place-items: center;
        border-radius: 9px;
        background: #edf4ed;
        color: #315f43;
        font-size: 17px;
    }

    .ad-stat-value {
        color: var(--admin-text, #263449);
        font-size: 30px;
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -.03em;
    }

    .ad-stat-note {
        margin-top: 8px;
        color: var(--admin-muted, #7b8493);
        font-size: 12px;
        line-height: 1.5;
    }

    .ad-growth {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: #198754;
        font-weight: 800;
    }

    /* Grafik */
    .ad-charts {
        display: grid;
        grid-template-columns: minmax(0, .9fr) minmax(0, 1.35fr);
        gap: 16px;
    }

    .ad-panel {
        min-width: 0;
        padding: 21px;
        border: 1px solid var(--admin-border, #e7ebf0);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(30, 55, 40, .035);
    }

    .ad-panel h3 {
        margin: 0 0 5px;
        color: var(--admin-text, #263449);
        font-size: 16px;
        font-weight: 800;
    }

    .ad-panel p {
        margin: 0 0 18px;
        color: var(--admin-muted, #7b8493);
        font-size: 12px;
        line-height: 1.6;
    }

    .ad-chart-wrap {
        position: relative;
        height: 285px;
        width: 100%;
    }

    .ad-chart-empty {
        display: grid;
        height: 100%;
        place-items: center;
        color: var(--admin-muted, #7b8493);
        font-size: 13px;
        text-align: center;
    }

    .ad-definition {
        margin-top: 15px;
        padding: 13px 15px;
        border: 1px solid #e7eee8;
        border-radius: 9px;
        background: #f5f8f6;
        color: #68796d;
        font-size: 12px;
        line-height: 1.7;
    }

    /* Responsif */
    @media (max-width: 1050px) {
        .ad-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .ad-charts {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 620px) {
        .ad-welcome h1 {
            font-size: 23px;
        }

        .ad-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .ad-stat {
            padding: 15px;
        }

        .ad-stat-label {
            font-size: 12px;
        }

        .ad-stat-value {
            font-size: 25px;
        }

        .ad-panel {
            padding: 16px;
        }

        .ad-chart-wrap {
            height: 260px;
        }
    }

    @media (max-width: 380px) {
        .ad-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="ad-page">

    {{-- Header Dashboard --}}
    <header class="ad-welcome">
        <p class="ad-eyebrow">Panel Administrator</p>
        <h1>Selamat datang, {{ auth()->user()->name }}!</h1>
        <p>
            Pantau perkembangan data dan status alumni Fakultas Sains dan Teknologi
            melalui ringkasan berikut.
        </p>
    </header>

    {{-- Ringkasan Alumni --}}
    <section aria-labelledby="ad-stat-heading">
        <div class="ad-section-title">
            <h2 id="ad-stat-heading">Ringkasan Alumni</h2>
            <p>Statistik diperbarui otomatis berdasarkan data alumni yang tersimpan.</p>
        </div>

        <div class="ad-stats">

            <article class="ad-stat ad-stat-main">
                <div class="ad-stat-label">
                    <span class="ad-stat-icon">👥</span>
                    Total Alumni
                </div>
                <div class="ad-stat-value">
                    {{ number_format($totalAlumni, 0, ',', '.') }}
                </div>
                <div class="ad-stat-note">Seluruh akun dengan role alumni</div>
            </article>

            <article class="ad-stat">
                <div class="ad-stat-label">
                    <span class="ad-stat-icon">📄</span>
                    Pertumbuhan Lulusan
                </div>
                <div class="ad-stat-value">
                    {{ $persentasePertumbuhan === null ? '—' : number_format(abs($persentasePertumbuhan), 1, ',', '.') . '%' }}
                </div>
                <div class="ad-stat-note">
                    @if($persentasePertumbuhan === null)
                        Belum ada data pembanding tahun lalu.
                    @else
                        <span class="ad-growth">
                            {{ $persentasePertumbuhan > 0 ? '▲' : ($persentasePertumbuhan < 0 ? '▼' : '—') }}
                            {{ $persentasePertumbuhan > 0 ? 'Naik' : ($persentasePertumbuhan < 0 ? 'Turun' : 'Tetap') }}
                        </span>
                        dibanding tahun lalu.
                    @endif
                </div>
            </article>

            <article class="ad-stat">
                <div class="ad-stat-label">
                    <span class="ad-stat-icon">🕒</span>
                    Alumni Bekerja
                </div>
                <div class="ad-stat-value">
                    {{ number_format($jumlahBekerja, 0, ',', '.') }}
                </div>
                <div class="ad-stat-note">Termasuk status wirausaha</div>
            </article>

            <article class="ad-stat">
                <div class="ad-stat-label">
                    <span class="ad-stat-icon">☑</span>
                    Melanjutkan Studi
                </div>
                <div class="ad-stat-value">
                    {{ number_format($jumlahStudi, 0, ',', '.') }}
                </div>
                <div class="ad-stat-note">Alumni yang melanjutkan pendidikan</div>
            </article>

            <article class="ad-stat">
                <div class="ad-stat-label">
                    <span class="ad-stat-icon">🔍</span>
                    Mencari Kerja
                </div>
                <div class="ad-stat-value">
                    {{ number_format($jumlahMencariKerja, 0, ',', '.') }}
                </div>
                <div class="ad-stat-note">Status “Belum Bekerja” pada profil</div>
            </article>

            <article class="ad-stat">
                <div class="ad-stat-label">
                    <span class="ad-stat-icon">✅</span>
                    Alumni Aktif
                </div>
                <div class="ad-stat-value">
                    {{ number_format($jumlahAktif, 0, ',', '.') }}
                </div>
                <div class="ad-stat-note">Sudah mengisi data inti profil</div>
            </article>

            <article class="ad-stat">
                <div class="ad-stat-label">
                    <span class="ad-stat-icon">⏸️</span>
                    Alumni Pasif
                </div>
                <div class="ad-stat-value">
                    {{ number_format($jumlahPasif, 0, ',', '.') }}
                </div>
                <div class="ad-stat-note">Data inti profil belum lengkap</div>
            </article>

        </div>
    </section>

    {{-- Visualisasi Data --}}
    <section aria-labelledby="ad-chart-heading">
        <div class="ad-section-title">
            <h2 id="ad-chart-heading">Visualisasi Data Alumni</h2>
            <p>Lihat sebaran status alumni dan jumlah alumni pada setiap program studi.</p>
        </div>

        <div class="ad-charts">

            <article class="ad-panel">
                <h3>Status Alumni</h3>
                <p>Perbandingan status setelah lulus berdasarkan data profil.</p>

                <div class="ad-chart-wrap">
                    @if($statusAlumni->sum('total') > 0)
                        <canvas
                            id="adminStatusChart"
                            aria-label="Grafik lingkaran status alumni"
                        ></canvas>
                    @else
                        <div class="ad-chart-empty">
                            Belum ada data status alumni.
                        </div>
                    @endif
                </div>
            </article>

            <article class="ad-panel">
                <h3>Alumni per Program Studi</h3>
                <p>Jumlah alumni terdaftar pada masing-masing program studi.</p>

                <div class="ad-chart-wrap">
                    @if($alumniPerProdi->isNotEmpty())
                        <canvas
                            id="adminProdiChart"
                            aria-label="Grafik batang vertikal jumlah alumni per program studi"
                        ></canvas>
                    @else
                        <div class="ad-chart-empty">
                            Belum ada data program studi.
                        </div>
                    @endif
                </div>
            </article>

        </div>

        <div class="ad-definition">
            <strong>Catatan:</strong>
            Pertumbuhan dihitung dari jumlah alumni berdasarkan tahun lulus tahun ini
            dibanding tahun lalu. Alumni aktif berarti telah mengisi program studi,
            tahun lulus, status setelah lulus, dan nomor HP. Status “Belum Bekerja”
            ditampilkan sebagai mencari kerja.
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const statusLabels = @json($statusAlumni->pluck('status')->values());
    const statusValues = @json($statusAlumni->pluck('total')->map(fn ($value) => (int) $value)->values());

    const prodiLabels = @json($alumniPerProdi->pluck('program_studi')->values());
    const prodiValues = @json($alumniPerProdi->pluck('total')->map(fn ($value) => (int) $value)->values());

    // Grafik Status Alumni
    const statusCanvas = document.getElementById('adminStatusChart');

    if (statusCanvas && typeof Chart !== 'undefined') {
        new Chart(statusCanvas, {
            type: 'doughnut',

            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                        '#315f43',
                        '#8eaa91',
                        '#d5b96f',
                        '#9ca9a0',
                        '#c7d5c8',
                        '#b47d68'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 7
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '58%',

                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#53665a',
                            padding: 16,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },

                    tooltip: {
                        callbacks: {
                            label: context =>
                                `${context.label}: ${context.parsed} alumni`
                        }
                    }
                }
            }
        });
    }

    // Grafik Alumni per Program Studi
    const prodiCanvas = document.getElementById('adminProdiChart');

    if (prodiCanvas && typeof Chart !== 'undefined') {
        new Chart(prodiCanvas, {
            type: 'bar',

            data: {
                labels: prodiLabels,
                datasets: [{
                    label: 'Jumlah Alumni',
                    data: prodiValues,
                    backgroundColor: '#78977c',
                    hoverBackgroundColor: '#315f43',
                    borderRadius: 7,
                    maxBarThickness: 48
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        callbacks: {
                            label: context =>
                                `${context.parsed.y} alumni`
                        }
                    }
                },

                scales: {
                    x: {
                        ticks: {
                            color: '#53665a',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    },

                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#718076',
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: '#edf1ed'
                        }
                    }
                }
            }
        });
    }

});
</script>
@endpush
