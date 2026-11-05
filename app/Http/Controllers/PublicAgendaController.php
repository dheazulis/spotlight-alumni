<?php

namespace App\Http\Controllers;

use App\Models\Agenda;

class PublicAgendaController extends Controller
{
    // Daftar newsletter
    public function index()
    {
        $agendas = Agenda::where('status', 'publikasi')
            ->orderByDesc('tanggal_mulai')
            ->paginate(9);

        return view('agenda.index', compact('agendas'));
    }

    // Detail newsletter
    public function show($id)
    {
        $agenda = Agenda::where('status', 'publikasi')
            ->findOrFail($id);

        return view('agenda.show', compact('agenda'));
    }
}