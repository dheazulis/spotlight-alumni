<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;

class PublicJobVacancyController extends Controller
{
    public function index()
    {
        $lowongans = JobVacancy::with('user')
            ->where('status', 'disetujui')
            ->where(function ($query) {
                $query->whereNull('tanggal_tutup')
                    ->orWhereDate('tanggal_tutup', '>=', today());
            })
            ->latest()
            ->get();

        return view('public.job-vacancies.index', compact('lowongans'));
    }
}
