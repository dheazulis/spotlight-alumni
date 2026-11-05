<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    private function checkAlumni(Request $request): void
    {
        $user = $request->user();

        if (!$user || $user->role !== 'alumni') {
            abort(403, 'Akses hanya untuk alumni.');
        }

        if (!$user->otp_verified_at) {
            abort(403, 'Verifikasi OTP terlebih dahulu.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAlumni($request);

        $lowongans = $request->user()
            ->jobVacancies()
            ->latest()
            ->get();

        return view(
            'alumni.job-vacancies.index',
            compact('lowongans')
        );
    }

    public function create(Request $request)
    {
        $this->checkAlumni($request);

        return view('alumni.job-vacancies.create');
    }

    public function store(Request $request)
    {
        $this->checkAlumni($request);

        $validated = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'posisi' => ['required', 'string', 'max:255'],
            'bidang_karier' => ['nullable', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:10000'],
            'kualifikasi' => ['nullable', 'string', 'max:10000'],
            'tanggal_tutup' => ['nullable', 'date', 'after_or_equal:today'],
            'kontak_lamaran' => ['nullable', 'string', 'max:255'],
            'tautan_lamaran' => ['nullable', 'url', 'max:2000'],
        ]);

        $request->user()->jobVacancies()->create($validated);

        return redirect()
            ->route('alumni.job-vacancies.index')
            ->with(
                'status',
                'Pengajuan lowongan berhasil dikirim dan menunggu persetujuan admin.'
            );
    }

    public function destroy(
        Request $request,
        JobVacancy $jobVacancy
    ) {
        $this->checkAlumni($request);

        abort_unless(
            $jobVacancy->user_id === $request->user()->id,
            403,
            'Kamu tidak boleh menghapus pengajuan milik alumni lain.'
        );

        if ($jobVacancy->status === 'disetujui') {
            return redirect()
                ->route('alumni.job-vacancies.index')
                ->with(
                    'error',
                    'Lowongan yang sudah disetujui tidak dapat dihapus melalui menu pengajuan.'
                );
        }

        $jobVacancy->delete();

        return redirect()
            ->route('alumni.job-vacancies.index')
            ->with('status', 'Pengajuan lowongan berhasil dihapus.');
    }
}