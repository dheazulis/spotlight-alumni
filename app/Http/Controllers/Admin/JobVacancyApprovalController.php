<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Illuminate\Http\Request;

class JobVacancyApprovalController extends Controller
{
    private function checkAdmin(Request $request): void
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses hanya untuk admin.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdmin($request);

        $lowongans = JobVacancy::with(['user', 'adminPemroses'])
            ->orderByRaw(
                "CASE WHEN status = 'menunggu' THEN 0 ELSE 1 END"
            )
            ->latest()
            ->get();

        return view(
            'admin.job-vacancies.index',
            compact('lowongans')
        );
    }

    public function approve(Request $request, JobVacancy $jobVacancy)
    {
        $this->checkAdmin($request);

        if ($jobVacancy->status !== 'menunggu') {
            return redirect()
                ->route('admin.job-vacancies.index')
                ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $jobVacancy->update([
            'status' => 'disetujui',
            'diproses_oleh' => $request->user()->id,
            'diproses_pada' => now(),
            'catatan_admin' => null,
        ]);

        return redirect()
            ->route('admin.job-vacancies.index')
            ->with('status', 'Lowongan berhasil disetujui dan siap dipublikasikan.');
    }

    public function reject(Request $request, JobVacancy $jobVacancy)
    {
        $this->checkAdmin($request);

        if ($jobVacancy->status !== 'menunggu') {
            return redirect()
                ->route('admin.job-vacancies.index')
                ->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:5000'],
        ]);

        $jobVacancy->update([
            'status' => 'ditolak',
            'diproses_oleh' => $request->user()->id,
            'diproses_pada' => now(),
            'catatan_admin' => $validated['catatan_admin'],
        ]);

        return redirect()
            ->route('admin.job-vacancies.index')
            ->with('status', 'Pengajuan lowongan berhasil ditolak.');
    }
}