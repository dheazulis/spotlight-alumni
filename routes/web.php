<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailOtpController;
use App\Http\Controllers\AlumniProfileController;
use App\Http\Controllers\CareerJourneyController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\Admin\JobVacancyApprovalController;
use App\Http\Controllers\PublicJobVacancyController;
use App\Http\Controllers\Admin\AlumniManagementController;
use App\Http\Controllers\Admin\AlumniAccountController;
use App\Http\Controllers\Admin\AlumniAccountManagementController;
use App\Http\Controllers\PublicAlumniDirectoryController;
use App\Http\Controllers\AlumniInspiratifController;
use App\Http\Controllers\Admin\AlumniInspiratifApprovalController;
use App\Http\Controllers\Admin\AlumniReportController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\PublicAgendaController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\Admin\DatabaseRestoreController;
use App\Models\AlumniInspiratif;
use App\Models\Agenda;
use App\Models\User;

// ==================================================
// HALAMAN UTAMA PUBLIK
// ==================================================

Route::get('/', function () {

    $alumniInspiratif = AlumniInspiratif::with('alumni')
        ->where('status', 'disetujui')
        ->whereBetween('bulan', [
            intdiv(now()->month - 1, 3) * 3 + 1,
            intdiv(now()->month - 1, 3) * 3 + 3,
        ])
        ->where('tahun', now()->year)
        ->whereHas('alumni', function ($query) {
            $query->whereNotNull('foto_profil')
                ->whereNotNull('program_studi')
                ->whereNotNull('tahun_lulus')
                ->whereNotNull('status_setelah_lulus')
                ->whereNotNull('pesan_motivasi')
                ->where('foto_profil', '!=', '')
                ->where('program_studi', '!=', '')
                ->where('tahun_lulus', '!=', '')
                ->where('status_setelah_lulus', '!=', '')
                ->where('pesan_motivasi', '!=', '');
        })
        ->orderByDesc('diputuskan_pada')
        ->get();

    $agendas = Agenda::where('status', 'publikasi')
        ->orderBy('tanggal_mulai')
        ->take(6)
        ->get();

    return view('welcome', compact('alumniInspiratif', 'agendas'));

})->name('home');


// ==================================================
// LOGIN
// ==================================================

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

});


// ==================================================
// LOGOUT
// ==================================================

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ==================================================
// DASHBOARD
// ==================================================

Route::get('/dashboard', function () {

    $user = Auth::user();

    // Alumni wajib menyelesaikan verifikasi OTP
    if (
        $user->role === 'alumni' &&
        !session('otp_verified_this_session', false)
    ) {
        return redirect()->route('otp.form');
    }

    // ==================================================
    // DASHBOARD ADMIN
    // ==================================================

    if ($user->role === 'admin') {

        $alumni = User::where('role', 'alumni');

        $totalAlumni = (clone $alumni)->count();

        $tahunIni = now()->year;

        $alumniTahunIni = (clone $alumni)
            ->where('tahun_lulus', $tahunIni)
            ->count();

        $alumniTahunLalu = (clone $alumni)
            ->where('tahun_lulus', $tahunIni - 1)
            ->count();

        $persentasePertumbuhan = $alumniTahunLalu > 0
            ? (($alumniTahunIni - $alumniTahunLalu) / $alumniTahunLalu) * 100
            : null;

        $statusAlumni = User::where('role', 'alumni')
            ->selectRaw('status_setelah_lulus, COUNT(*) as total')
            ->groupBy('status_setelah_lulus')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'status' => empty($item->status_setelah_lulus)
                        ? 'Belum mengisi'
                        : $item->status_setelah_lulus,
                    'total' => $item->total,
                ];
            });

        $jumlahBekerja = (clone $alumni)
            ->whereIn('status_setelah_lulus', ['Bekerja', 'Wirausaha'])
            ->count();

        $jumlahStudi = (clone $alumni)
            ->where('status_setelah_lulus', 'Melanjutkan Studi')
            ->count();

        $jumlahMencariKerja = (clone $alumni)
            ->where('status_setelah_lulus', 'Belum Bekerja')
            ->count();

        // Aktif: data inti profil alumni sudah lengkap
        $profilAktifQuery = (clone $alumni)
            ->whereNotNull('program_studi')
            ->where('program_studi', '!=', '')
            ->whereNotNull('tahun_lulus')
            ->whereNotNull('status_setelah_lulus')
            ->where('status_setelah_lulus', '!=', '')
            ->whereNotNull('nomor_hp')
            ->where('nomor_hp', '!=', '');

        $jumlahAktif = (clone $profilAktifQuery)->count();

        $jumlahPasif = max(0, $totalAlumni - $jumlahAktif);

        $alumniPerProdi = (clone $alumni)
            ->whereNotNull('program_studi')
            ->where('program_studi', '!=', '')
            ->selectRaw('program_studi, COUNT(*) as total')
            ->groupBy('program_studi')
            ->orderBy('program_studi')
            ->get();

        return view('admin.index', compact(
            'totalAlumni',
            'alumniTahunIni',
            'alumniTahunLalu',
            'persentasePertumbuhan',
            'statusAlumni',
            'jumlahBekerja',
            'jumlahStudi',
            'jumlahMencariKerja',
            'jumlahAktif',
            'jumlahPasif',
            'alumniPerProdi'
        ));
    }

    // ==================================================
    // DASHBOARD PENGELOLA DATA
    // ==================================================

    if ($user->role === 'pengelola_data') {

        $alumni = User::where('role', 'alumni');

        // Statistik data alumni
        $totalAlumni = (clone $alumni)->count();

        $alumniPerProdi = (clone $alumni)
            ->whereNotNull('program_studi')
            ->where('program_studi', '!=', '')
            ->selectRaw('program_studi, COUNT(*) as total')
            ->groupBy('program_studi')
            ->orderBy('program_studi')
            ->get();

        $alumniPerTahunLulus = (clone $alumni)
            ->whereNotNull('tahun_lulus')
            ->selectRaw('tahun_lulus, COUNT(*) as total')
            ->groupBy('tahun_lulus')
            ->orderBy('tahun_lulus')
            ->get();

        $alumniPerStatus = (clone $alumni)
            ->selectRaw('status_setelah_lulus, COUNT(*) as total')
            ->groupBy('status_setelah_lulus')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'status' => empty($item->status_setelah_lulus)
                        ? 'Belum mengisi'
                        : $item->status_setelah_lulus,
                    'total' => $item->total,
                ];
            });

        // Statistik pengajuan Alumni Inspiratif
        $totalPengajuan = AlumniInspiratif::count();

        $pengajuanMenunggu = AlumniInspiratif::where(
            'status',
            'menunggu'
        )->count();

        $pengajuanDisetujui = AlumniInspiratif::where(
            'status',
            'disetujui'
        )->count();

        $pengajuanDitolak = AlumniInspiratif::where(
            'status',
            'ditolak'
        )->count();

        $pengajuanPerStatus = AlumniInspiratif::selectRaw(
            'status, COUNT(*) as total'
        )
            ->groupBy('status')
            ->get();

        return view('dashboard', compact(
            'totalAlumni',
            'alumniPerProdi',
            'alumniPerTahunLulus',
            'alumniPerStatus',
            'totalPengajuan',
            'pengajuanMenunggu',
            'pengajuanDisetujui',
            'pengajuanDitolak',
            'pengajuanPerStatus'
        ));
    }

    // ==================================================
    // DASHBOARD ALUMNI
    // ==================================================

    if ($user->role === 'alumni') {
        return view('dashboard');
    }

    abort(403, 'Role akun tidak dikenali.');

})->middleware([
    'auth',
    'role:admin,pengelola_data,alumni'
])->name('dashboard');

// ==================================================
// DIREKTORI ALUMNI UNTUK PUBLIK
// ==================================================

Route::get('/direktori-alumni', [
    PublicAlumniDirectoryController::class,
    'index'
])->name('public.alumni-directory.index');

Route::get('/direktori-alumni/{id}', [
    PublicAlumniDirectoryController::class,
    'show'
])->name('public.alumni.show');


// ==================================================
// FITUR PENGELOLA DATA
// ==================================================

Route::middleware(['auth', 'role:pengelola_data'])
    ->prefix('pengelola')
    ->name('pengelola.')
    ->group(function () {

        Route::get('/alumni', [
            AlumniManagementController::class,
            'index'
        ])->name('alumni.index');

        Route::get('/alumni-inspiratif', [
            AlumniInspiratifController::class,
            'index'
        ])->name('alumni-inspiratif.index');

        Route::post('/alumni-inspiratif', [
            AlumniInspiratifController::class,
            'store'
        ])->name('alumni-inspiratif.store');

        Route::get('/laporan-alumni', [
            AlumniReportController::class,
            'index'
        ])->name('laporan-alumni.index');

        Route::post('/laporan-alumni/export-pdf', [
            AlumniReportController::class,
            'exportPdf'
        ])->name('laporan-alumni.export-pdf');

        Route::post('/laporan-alumni/export-excel', [
            AlumniReportController::class,
            'exportExcel'
        ])->name('laporan-alumni.export-excel');

    });


// ==================================================
// NEWSLETTER UNTUK PUBLIK
// ==================================================

Route::get('/agenda', [
    PublicAgendaController::class,
    'index'
])->name('agenda.index');

Route::get('/agenda/{id}', [
    PublicAgendaController::class,
    'show'
])->name('agenda.show');

// ==================================================
// FITUR KHUSUS ADMIN
// ==================================================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Persetujuan pengajuan lowongan
        Route::get('/pengajuan-lowongan', [
            JobVacancyApprovalController::class,
            'index'
        ])->name('job-vacancies.index');

        Route::patch('/pengajuan-lowongan/{jobVacancy}/setujui', [
            JobVacancyApprovalController::class,
            'approve'
        ])->name('job-vacancies.approve');

        Route::patch('/pengajuan-lowongan/{jobVacancy}/tolak', [
            JobVacancyApprovalController::class,
            'reject'
        ])->name('job-vacancies.reject');


        // Melihat data alumni
        Route::get('/alumni', [
            AlumniManagementController::class,
            'index'
        ])->name('alumni.index');


        // Membuat akun alumni
        Route::get('/alumni/tambah', [
            AlumniAccountController::class,
            'create'
        ])->name('alumni.create');

        Route::post('/alumni', [
            AlumniAccountController::class,
            'store'
        ])->name('alumni.store');


        // Mengedit akun alumni
        Route::get('/alumni/{id}/edit', [
            AlumniAccountManagementController::class,
            'edit'
        ])->name('alumni.edit');

        Route::put('/alumni/{id}', [
            AlumniAccountManagementController::class,
            'update'
        ])->name('alumni.update');

        Route::delete('/alumni/{id}', [
            AlumniAccountManagementController::class,
            'destroy'
        ])->name('alumni.destroy');


        // Persetujuan Alumni Inspiratif
        Route::get('/alumni-inspiratif', [
            AlumniInspiratifApprovalController::class,
            'index'
        ])->name('alumni-inspiratif.index');

        Route::patch('/alumni-inspiratif/{alumniInspiratif}/setujui', [
            AlumniInspiratifApprovalController::class,
            'approve'
        ])->name('alumni-inspiratif.approve');

        Route::patch('/alumni-inspiratif/{alumniInspiratif}/tolak', [
            AlumniInspiratifApprovalController::class,
            'reject'
        ])->name('alumni-inspiratif.reject');


        // Laporan dan statistik alumni
        Route::get('/laporan-alumni', [
            AlumniReportController::class,
            'index'
        ])->name('laporan-alumni.index');

        Route::post('/laporan-alumni/export-pdf', [
            AlumniReportController::class,
            'exportPdf'
        ])->name('laporan-alumni.export-pdf');

        Route::post('/laporan-alumni/export-excel', [
            AlumniReportController::class,
            'exportExcel'
        ])->name('laporan-alumni.export-excel');


        // Pengelolaan Agenda
        Route::resource('agenda', AgendaController::class)
            ->except(['show']);


        // Restore Database - Khusus Admin
        Route::get('/database-restore', [
            DatabaseRestoreController::class,
            'index'
        ])->name('database-restore.index');

        Route::post('/database-restore', [
            DatabaseRestoreController::class,
            'restore'
        ])->name('database-restore.restore');

    });


// ==================================================
// INFO LOWONGAN KERJA UNTUK PUBLIK
// ==================================================

Route::get('/info-loker', [
    PublicJobVacancyController::class,
    'index'
])->name('public.job-vacancies.index');


// ==================================================
// VERIFIKASI OTP
// ==================================================

Route::middleware('auth')->group(function () {

    Route::post('/send-otp', [
        EmailOtpController::class,
        'send'
    ])->name('otp.send');

    Route::get('/verify-otp', [
        EmailOtpController::class,
        'showVerify'
    ])->name('otp.form');

    Route::post('/verify-otp', [
        EmailOtpController::class,
        'verify'
    ])->name('otp.verify');

});


// ==================================================
// FITUR ALUMNI
// WAJIB LOGIN DAN OTP TERLEBIH DAHULU
// ==================================================

Route::middleware(['auth', 'role:alumni', 'alumni.otp'])
    ->group(function () {

        // Profil alumni
        Route::get('/profil-saya', [
            AlumniProfileController::class,
            'show'
        ])->name('alumni.profile');

        Route::put('/profil-saya', [
            AlumniProfileController::class,
            'update'
        ])->name('alumni.profile.update');


        // Ganti password alumni
        Route::get('/ganti-password', [
            AlumniProfileController::class,
            'showChangePassword'
        ])->name('alumni.password.edit');

        Route::put('/ganti-password', [
            AlumniProfileController::class,
            'updatePassword'
        ])->name('alumni.password.update');


        // Perjalanan karier
        Route::get('/perjalanan-karier', [
            CareerJourneyController::class,
            'index'
        ])->name('alumni.career-journeys.index');

        Route::get('/perjalanan-karier/tambah', [
            CareerJourneyController::class,
            'create'
        ])->name('alumni.career-journeys.create');

        Route::post('/perjalanan-karier', [
            CareerJourneyController::class,
            'store'
        ])->name('alumni.career-journeys.store');

        Route::get('/perjalanan-karier/{careerJourney}/edit', [
            CareerJourneyController::class,
            'edit'
        ])->name('alumni.career-journeys.edit');

        Route::put('/perjalanan-karier/{careerJourney}', [
            CareerJourneyController::class,
            'update'
        ])->name('alumni.career-journeys.update');

        Route::delete('/perjalanan-karier/{careerJourney}', [
            CareerJourneyController::class,
            'destroy'
        ])->name('alumni.career-journeys.destroy');


        // Pengajuan lowongan oleh alumni
        Route::get('/pengajuan-lowongan', [
            JobVacancyController::class,
            'index'
        ])->name('alumni.job-vacancies.index');

        Route::get('/pengajuan-lowongan/tambah', [
            JobVacancyController::class,
            'create'
        ])->name('alumni.job-vacancies.create');

        Route::post('/pengajuan-lowongan', [
            JobVacancyController::class,
            'store'
        ])->name('alumni.job-vacancies.store');

        Route::delete('/pengajuan-lowongan/{jobVacancy}', [
            JobVacancyController::class,
            'destroy'
        ])->name('alumni.job-vacancies.destroy');

    });


// ==================================================
// BACKUP DATABASE
// Admin dan Pengelola Data
// ==================================================

Route::middleware(['auth', 'role:admin,pengelola_data'])
    ->prefix('database-backup')
    ->name('database-backup.')
    ->group(function () {

        Route::get('/', [
            DatabaseBackupController::class,
            'index'
        ])->name('index');

        Route::post('/backup', [
            DatabaseBackupController::class,
            'backup'
        ])->name('backup');

        Route::get('/download/{filename}', [
            DatabaseBackupController::class,
            'download'
        ])->name('download');

    });
