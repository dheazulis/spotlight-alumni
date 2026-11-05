<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicAlumniDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $kataKunci = trim($request->query('cari', ''));

        $alumni = User::query()
            ->where('role', 'alumni')
            ->whereNotNull('foto_profil')
            ->whereNotNull('program_studi')
            ->whereNotNull('tahun_lulus')
            ->whereNotNull('status_setelah_lulus')
            ->where('program_studi', '!=', '')
            ->where('status_setelah_lulus', '!=', '')
            ->when($kataKunci !== '', function ($query) use ($kataKunci) {
                $query->where(function ($q) use ($kataKunci) {
                    $q->where('name', 'like', "%{$kataKunci}%")
                        ->orWhere('program_studi', 'like', "%{$kataKunci}%")
                        ->orWhere('tahun_lulus', 'like', "%{$kataKunci}%");
                });
            })
            ->orderByDesc('tahun_lulus')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('public.alumni-directory.index', compact(
            'alumni',
            'kataKunci'
        ));
    }

    public function show($id)
    {
        $alumni = User::query()
            ->where('role', 'alumni')
            ->where('id', $id)
            ->whereNotNull('foto_profil')
            ->whereNotNull('program_studi')
            ->whereNotNull('tahun_lulus')
            ->whereNotNull('status_setelah_lulus')
            ->where('program_studi', '!=', '')
            ->where('status_setelah_lulus', '!=', '')
            ->firstOrFail();

        return view('public.alumni-directory.show', compact('alumni'));
    }
}
