<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgendaController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $agendas = Agenda::latest()->paginate(10);

        return view('admin.agenda.index', compact('agendas'));
    }

    public function create()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('admin.agenda.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5048',
            'status' => 'required|in:draft,publikasi',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('agenda', 'public');
        }

        $validated['admin_id'] = auth()->id();

        Agenda::create($validated);

        return redirect()
            ->route('admin.agenda.index')
            ->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function edit(Agenda $agenda)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('admin.agenda.edit', compact('agenda'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5048',
            'status' => 'required|in:draft,publikasi',
        ]);

        if ($request->hasFile('gambar')) {
            if ($agenda->gambar) {
                Storage::disk('public')->delete($agenda->gambar);
            }

            $validated['gambar'] = $request->file('gambar')->store('agenda', 'public');
        }

        $agenda->update($validated);

        return redirect()
            ->route('admin.agenda.index')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        if ($agenda->gambar) {
            Storage::disk('public')->delete($agenda->gambar);
        }

        $agenda->delete();

        return redirect()
            ->route('admin.agenda.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}