<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlumniInspiratif;
use Illuminate\Http\Request;

class AlumniInspiratifApprovalController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $pengajuan = AlumniInspiratif::with(['alumni', 'pengaju', 'peninjau'])
            ->orderByRaw("CASE WHEN status = 'menunggu' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.alumni-inspiratif.index', compact('pengajuan'));
    }

    public function approve(Request $request, AlumniInspiratif $alumniInspiratif)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        if ($alumniInspiratif->status !== 'menunggu') {
            return back()->with('status', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $alumniInspiratif->update([
            'status' => 'disetujui',
            'peninjau_id' => auth()->id(),
            'catatan_admin' => $request->input('catatan_admin'),
            'diputuskan_pada' => now(),
        ]);

        return back()->with('status', 'Pengajuan Alumni Inspiratif berhasil disetujui.');
    }

    public function reject(Request $request, AlumniInspiratif $alumniInspiratif)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        if ($alumniInspiratif->status !== 'menunggu') {
            return back()->with('status', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:1000'],
        ]);

        $alumniInspiratif->update([
            'status' => 'ditolak',
            'peninjau_id' => auth()->id(),
            'catatan_admin' => $validated['catatan_admin'],
            'diputuskan_pada' => now(),
        ]);

        return back()->with('status', 'Pengajuan Alumni Inspiratif berhasil ditolak.');
    }
}
