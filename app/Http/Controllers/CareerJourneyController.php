<?php

namespace App\Http\Controllers;

use App\Models\CareerJourney;
use Illuminate\Http\Request;

class CareerJourneyController extends Controller
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

        $careerJourneys = $request->user()
            ->careerJourneys()
            ->orderByDesc('tanggal_mulai')
            ->get();

        return view('alumni.career-journeys.index', compact('careerJourneys'));
    }

    public function create(Request $request)
    {
        $this->checkAlumni($request);

        return view('alumni.career-journeys.create');
    }

    public function store(Request $request)
    {
        $this->checkAlumni($request);

        $validated = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'bidang_karier' => ['nullable', 'string', 'max:255'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'masih_bekerja' => ['nullable', 'boolean'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
        ]);

        $validated['masih_bekerja'] = $request->boolean('masih_bekerja');

        if ($validated['masih_bekerja']) {
            $validated['tanggal_selesai'] = null;
        }

        $request->user()->careerJourneys()->create($validated);

        return redirect()
            ->route('alumni.career-journeys.index')
            ->with('status', 'Perjalanan karier berhasil ditambahkan.');
    }

    public function edit(Request $request, CareerJourney $careerJourney)
    {
        $this->checkAlumni($request);

        abort_unless(
            $careerJourney->user_id === $request->user()->id,
            403,
            'Kamu tidak boleh mengubah data milik alumni lain.'
        );

        return view('alumni.career-journeys.edit', compact('careerJourney'));
    }

    public function update(
        Request $request,
        CareerJourney $careerJourney
    ) {
        $this->checkAlumni($request);

        abort_unless(
            $careerJourney->user_id === $request->user()->id,
            403,
            'Kamu tidak boleh mengubah data milik alumni lain.'
        );

        $validated = $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'jabatan' => ['required', 'string', 'max:255'],
            'bidang_karier' => ['nullable', 'string', 'max:255'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'masih_bekerja' => ['nullable', 'boolean'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
        ]);

        $validated['masih_bekerja'] = $request->boolean('masih_bekerja');

        if ($validated['masih_bekerja']) {
            $validated['tanggal_selesai'] = null;
        }

        $careerJourney->update($validated);

        return redirect()
            ->route('alumni.career-journeys.index')
            ->with('status', 'Perjalanan karier berhasil diperbarui.');
    }

    public function destroy(Request $request, CareerJourney $careerJourney)
    {
        $this->checkAlumni($request);

        abort_unless(
            $careerJourney->user_id === $request->user()->id,
            403,
            'Kamu tidak boleh menghapus data milik alumni lain.'
        );

        $careerJourney->delete();

        return redirect()
            ->route('alumni.career-journeys.index')
            ->with('status', 'Perjalanan karier berhasil dihapus.');
    }
}
