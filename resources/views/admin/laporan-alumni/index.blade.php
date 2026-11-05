@extends('layouts.dashboard')

@section('title', 'Rekapitulasi Alumni')

@section('content')
@php
    $routePrefix = auth()->user()->role === 'admin'
        ? 'admin'
        : 'pengelola';
@endphp

<style>
    .rekap-header {
        background: #eaf3ed;
        border-radius: 12px;
        padding: 22px 25px;
        margin-bottom: 24px;
    }

    .rekap-header h2 {
        color: #1d6045;
        font-size: 23px;
        margin: 0 0 7px;
    }

    .rekap-header p {
        color: #64748b;
        margin: 0;
        font-size: 14px;
    }

    .rekap-filter {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 3px 12px rgba(30, 60, 100, .05);
    }

    .rekap-filter label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #334155;
    }

    .rekap-filter select {
        width: 100%;
        padding: 11px;
        border: 1px solid #dce9df;
        border-radius: 8px;
        background: white;
    }

    .rekap-list {
        display: grid;
        grid-template-columns: 1fr;
        gap: 18px;
    }

    .rekap-card {
        position: relative;
        background: #fff;
        border-radius: 12px;
        padding: 22px;
        padding-right: 245px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 3px 12px rgba(30, 60, 100, .06);
        border: 1px solid #edf2ee;
        min-height: 112px;
    }

    .rekap-icon {
        width: 52px;
        height: 52px;
        flex-shrink: 0;
        border-radius: 12px;
        background: #eaf3ed;
        color: #1d6045;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
    }

    .rekap-info {
        flex: 1;
        min-width: 0;
    }

    .rekap-info h3 {
        margin: 0 0 7px;
        font-size: 15px;
        color: #263449;
    }

    .rekap-info p {
        color: #718096;
        font-size: 12px;
        margin: 0;
        line-height: 1.5;
    }

    /* Tombol berada di ujung kanan card */
    .rekap-actions {
        position: absolute;
        right: 22px;
        top: 50%;
        transform: translateY(-50%);
        display: flex !important;
        flex-direction: row !important;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: nowrap !important;
        width: auto;
    }

    .rekap-actions form {
        display: inline-flex !important;
        flex-direction: row !important;
        align-items: center;
        flex: 0 0 auto !important;
        width: auto !important;
        margin: 0 !important;
        padding: 0;
    }

    .rekap-btn {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: auto !important;
        flex: 0 0 auto !important;
        border: 0;
        border-radius: 7px;
        padding: 9px 13px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
    }

    .rekap-pdf {
        background: #1d6045;
        color: white;
    }

    .rekap-excel {
        background: #eaf3ed;
        color: #1d6045;
    }

    .rekap-alert {
        background: #fee2e2;
        color: #991b1b;
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 18px;
    }

    .chart-source {
        position: fixed;
        left: -10000px;
        top: 0;
        width: 700px;
        height: 420px;
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .rekap-list,
        .rekap-filter {
            grid-template-columns: 1fr;
        }

        .rekap-card {
            padding: 18px;
            padding-bottom: 70px;
            align-items: flex-start;
        }

        .rekap-actions {
            top: auto;
            bottom: 18px;
            right: 18px;
            transform: none;
        }
    }

    @media (max-width: 400px) {
        .rekap-actions {
            left: 18px;
            right: auto;
        }
    }
</style>

<div class="rekap-header">
    <h2>Rekapitulasi Data Alumni</h2>
    <p>
        Pilih jenis laporan untuk mengunduh rekapitulasi data alumni
        dalam format PDF atau Excel.
    </p>
</div>

@if (session('error'))
    <div class="rekap-alert">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="rekap-alert">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="rekap-filter">
    <div>
        <label for="filterTahun">Periode Tahun Lulus</label>
        <select id="filterTahun">
            <option value="">Semua Periode</option>
            @foreach ($berdasarkanTahunLulus as $item)
                <option value="{{ $item->tahun_lulus }}">
                    {{ $item->tahun_lulus }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="filterProdi">Program Studi</label>
        <select id="filterProdi">
            <option value="">Semua Program Studi</option>
            @foreach ($berdasarkanProdi as $item)
                <option value="{{ $item->program_studi }}">
                    {{ $item->program_studi }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="rekap-list">

    {{-- KARTU 1 --}}
    <div class="rekap-card">
        <div class="rekap-icon">▤</div>

        <div class="rekap-info">
            <h3>Laporan Keseluruhan Alumni</h3>
            <p>Rekapitulasi seluruh data alumni berdasarkan program studi, tahun lulus, dan status pekerjaan.</p>

            <div class="rekap-actions">
                <form class="form-pdf" method="POST" action="{{ route($routePrefix . '.laporan-alumni.export-pdf') }}">
                    @csrf
                    <input type="hidden" name="kategori" value="keseluruhan">
                    <input type="hidden" name="chart_prodi">
                    <input type="hidden" name="chart_tahun">
                    <input type="hidden" name="chart_status">
                    <input type="hidden" name="chart_horizontal">
                    <input type="hidden" name="chart_horizontal_prodi">
                    <input type="hidden" name="chart_horizontal_tahun">
                    <input type="hidden" name="chart_horizontal_status">
                    <button class="rekap-btn rekap-pdf" type="submit">Unduh PDF</button>
                </form>

                <form method="POST" action="{{ route($routePrefix . '.laporan-alumni.export-excel') }}">
                    @csrf
                    <input type="hidden" name="kategori" value="keseluruhan">
                    <button class="rekap-btn rekap-excel" type="submit">Unduh Excel</button>
                </form>
            </div>
        </div>
    </div>

    {{-- KARTU 2 --}}
    <div class="rekap-card">
        <div class="rekap-icon">▦</div>

        <div class="rekap-info">
            <h3>Laporan Alumni per Tahun Lulus</h3>
            <p>Rekapitulasi jumlah alumni berdasarkan tahun kelulusan yang dipilih.</p>

            <div class="rekap-actions">
                <form class="form-pdf form-filter-tahun" method="POST" action="{{ route($routePrefix . '.laporan-alumni.export-pdf') }}">
                    @csrf
                    <input type="hidden" name="kategori" value="tahun">
                    <input type="hidden" name="nilai">
                    <input type="hidden" name="chart_prodi">
                    <input type="hidden" name="chart_tahun">
                    <input type="hidden" name="chart_status">
                    <input type="hidden" name="chart_horizontal">
                    <input type="hidden" name="chart_horizontal_prodi">
                    <input type="hidden" name="chart_horizontal_tahun">
                    <input type="hidden" name="chart_horizontal_status">
                    <button class="rekap-btn rekap-pdf" type="submit">Unduh PDF</button>
                </form>

                <form class="form-filter-tahun" method="POST" action="{{ route($routePrefix . '.laporan-alumni.export-excel') }}">
                    @csrf
                    <input type="hidden" name="kategori" value="tahun">
                    <input type="hidden" name="nilai">
                    <button class="rekap-btn rekap-excel" type="submit">Unduh Excel</button>
                </form>
            </div>
        </div>
    </div>

    {{-- KARTU 3 --}}
    <div class="rekap-card">
        <div class="rekap-icon">▥</div>

        <div class="rekap-info">
            <h3>Laporan Status Pekerjaan Alumni</h3>
            <p>Rekapitulasi jumlah alumni berdasarkan status setelah lulus.</p>

            <div class="rekap-actions">
                <form class="form-pdf" method="POST" action="{{ route($routePrefix . '.laporan-alumni.export-pdf') }}">
                    @csrf
                    <input type="hidden" name="kategori" value="status">
                    <input type="hidden" name="chart_prodi">
                    <input type="hidden" name="chart_tahun">
                    <input type="hidden" name="chart_status">
                    <input type="hidden" name="chart_horizontal">
                    <input type="hidden" name="chart_horizontal_prodi">
                    <input type="hidden" name="chart_horizontal_tahun">
                    <input type="hidden" name="chart_horizontal_status">
                    <button class="rekap-btn rekap-pdf" type="submit">Unduh PDF</button>
                </form>

                <form method="POST" action="{{ route($routePrefix . '.laporan-alumni.export-excel') }}">
                    @csrf
                    <input type="hidden" name="kategori" value="status">
                    <button class="rekap-btn rekap-excel" type="submit">Unduh Excel</button>
                </form>
            </div>
        </div>
    </div>

    {{-- KARTU 4 --}}
    <div class="rekap-card">
        <div class="rekap-icon">▧</div>

        <div class="rekap-info">
            <h3>Laporan per Jurusan</h3>
            <p>Rekapitulasi data alumni berdasarkan program studi yang dipilih.</p>

            <div class="rekap-actions">
                <form class="form-pdf form-filter-prodi" method="POST" action="{{ route($routePrefix . '.laporan-alumni.export-pdf') }}">
                    @csrf
                    <input type="hidden" name="kategori" value="prodi">
                    <input type="hidden" name="nilai">
                    <input type="hidden" name="chart_prodi">
                    <input type="hidden" name="chart_tahun">
                    <input type="hidden" name="chart_status">
                    <input type="hidden" name="chart_horizontal">
                    <button class="rekap-btn rekap-pdf" type="submit">Unduh PDF</button>
                </form>

                <form class="form-filter-prodi" method="POST" action="{{ route($routePrefix . '.laporan-alumni.export-excel') }}">
                    @csrf
                    <input type="hidden" name="kategori" value="prodi">
                    <input type="hidden" name="nilai">
                    <button class="rekap-btn rekap-excel" type="submit">Unduh Excel</button>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- CANVAS SUMBER GRAFIK UNTUK PDF --}}
<div class="chart-source">
    <canvas id="pieProdi" width="700" height="420"></canvas>
    <canvas id="barProdi" width="700" height="420"></canvas>

    <canvas id="pieTahun" width="700" height="420"></canvas>
    <canvas id="barTahun" width="700" height="420"></canvas>

    <canvas id="pieStatus" width="700" height="420"></canvas>
    <canvas id="barStatus" width="700" height="420"></canvas>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterTahun = document.getElementById('filterTahun');
    const filterProdi = document.getElementById('filterProdi');

    const dataProdi = @json($berdasarkanProdi->map(fn($item) => [
        'label' => $item->program_studi,
        'jumlah' => (int) $item->total
    ])->values());

    const dataTahun = @json($berdasarkanTahunLulus->map(fn($item) => [
        'label' => (string) $item->tahun_lulus,
        'jumlah' => (int) $item->total
    ])->values());

    const dataStatus = @json($berdasarkanStatus->map(fn($item) => [
        'label' => $item->status_setelah_lulus,
        'jumlah' => (int) $item->total
    ])->values());

    const warna = [
        '#1d6045', '#5b83c4', '#86a5d8', '#d1a85a',
        '#9b7bb5', '#d47b70', '#6caaa0', '#94a3b8'
    ];

    function buatPie(id, data) {
        return new Chart(document.getElementById(id), {
            type: 'pie',
            data: {
                labels: data.map(item => item.label),
                datasets: [{
                    data: data.map(item => item.jumlah),
                    backgroundColor: data.map((item, i) => warna[i % warna.length]),
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: false,
                animation: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    function buatBar(id, data) {
        return new Chart(document.getElementById(id), {
            type: 'bar',
            data: {
                labels: data.map(item => item.label),
                datasets: [{
                    label: 'Jumlah Alumni',
                    data: data.map(item => item.jumlah),
                    backgroundColor: '#1d6045',
                    borderRadius: 5
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: false,
                animation: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    // Buat grafik hanya satu kali untuk setiap canvas.
    buatPie('pieProdi', dataProdi);
    buatBar('barProdi', dataProdi);

    buatPie('pieTahun', dataTahun);
    buatBar('barTahun', dataTahun);

    buatPie('pieStatus', dataStatus);
    buatBar('barStatus', dataStatus);

    function isiGrafik(form, kategori) {
        // Ambil semua diagram lingkaran
        const pieProdi = document.getElementById('pieProdi');
        const pieTahun = document.getElementById('pieTahun');
        const pieStatus = document.getElementById('pieStatus');

        // Ambil semua grafik batang
        const barProdi = document.getElementById('barProdi');
        const barTahun = document.getElementById('barTahun');
        const barStatus = document.getElementById('barStatus');

        // Kosongkan seluruh input grafik terlebih dahulu
        const namaInput = [
            'chart_prodi',
            'chart_tahun',
            'chart_status',
            'chart_horizontal_prodi',
            'chart_horizontal_tahun',
            'chart_horizontal_status'
        ];

        namaInput.forEach(nama => {
            const input = form.querySelector(`[name="${nama}"]`);
            if (input) input.value = '';
        });

        // Laporan keseluruhan mengirim seluruh grafik
        if (kategori === 'keseluruhan') {
            form.querySelector('[name="chart_prodi"]').value =
                pieProdi.toDataURL('image/png');

            form.querySelector('[name="chart_tahun"]').value =
                pieTahun.toDataURL('image/png');

            form.querySelector('[name="chart_status"]').value =
                pieStatus.toDataURL('image/png');

            form.querySelector('[name="chart_horizontal_prodi"]').value =
                barProdi.toDataURL('image/png');

            form.querySelector('[name="chart_horizontal_tahun"]').value =
                barTahun.toDataURL('image/png');

            form.querySelector('[name="chart_horizontal_status"]').value =
                barStatus.toDataURL('image/png');
        }

        // Laporan per program studi
        else if (kategori === 'prodi') {
            form.querySelector('[name="chart_prodi"]').value =
                pieProdi.toDataURL('image/png');

            form.querySelector('[name="chart_horizontal_prodi"]').value =
                barProdi.toDataURL('image/png');
        }

        // Laporan per tahun lulus
        else if (kategori === 'tahun') {
            form.querySelector('[name="chart_tahun"]').value =
                pieTahun.toDataURL('image/png');

            form.querySelector('[name="chart_horizontal_tahun"]').value =
                barTahun.toDataURL('image/png');
        }

        // Laporan status pekerjaan
        else if (kategori === 'status') {
            form.querySelector('[name="chart_status"]').value =
                pieStatus.toDataURL('image/png');

            form.querySelector('[name="chart_horizontal_status"]').value =
                barStatus.toDataURL('image/png');
        }
    }

    document.querySelectorAll('.form-pdf').forEach(form => {
        form.addEventListener('submit', function (event) {
            const kategori = form.querySelector('[name="kategori"]').value;

            if (kategori === 'tahun' && !filterTahun.value) {
                event.preventDefault();
                alert('Pilih tahun lulus terlebih dahulu.');
                return;
            }

            if (kategori === 'prodi' && !filterProdi.value) {
                event.preventDefault();
                alert('Pilih program studi terlebih dahulu.');
                return;
            }

            if (kategori === 'tahun') {
                form.querySelector('[name="nilai"]').value = filterTahun.value;
            }

            if (kategori === 'prodi') {
                form.querySelector('[name="nilai"]').value = filterProdi.value;
            }

            isiGrafik(form, kategori);
        });
    });

    document.querySelectorAll('.form-filter-tahun').forEach(form => {
        form.addEventListener('submit', function (event) {
            if (!filterTahun.value) {
                event.preventDefault();
                alert('Pilih tahun lulus terlebih dahulu.');
                return;
            }

            form.querySelector('[name="nilai"]').value = filterTahun.value;
        });
    });

    document.querySelectorAll('.form-filter-prodi').forEach(form => {
        form.addEventListener('submit', function (event) {
            if (!filterProdi.value) {
                event.preventDefault();
                alert('Pilih program studi terlebih dahulu.');
                return;
            }

            form.querySelector('[name="nilai"]').value = filterProdi.value;
        });
    });
});
</script>
@endpush

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
