@extends('layouts.dashboard')

@section('title', 'Kelola Newsletter')
@section('breadcrumb', 'Kelola Newsletter')

@push('styles')
<style>
    .agenda-page { color: var(--sd-ink, #23342b); }
    .agenda-page, .agenda-page * { box-sizing: border-box; }

    .agenda-heading {
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:18px;
        margin-bottom:24px;
    }

    .agenda-heading h1 {
        margin:0;
        color:var(--sd-green-950,#123d2d);
        font-size:26px;
        line-height:1.2;
        font-weight:750;
    }

    .agenda-heading p {
        margin:9px 0 0;
        color:var(--sd-muted,#758279);
        font-size:14px;
        line-height:1.6;
    }

    .agenda-btn {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        min-height:38px;
        padding:9px 15px;
        border-radius:8px;
        font:600 13px/1.3 Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif;
        text-decoration:none;
        cursor:pointer;
        transition:.18s;
    }

    .agenda-btn-primary,
    .agenda-btn-edit {
        color:#fff;
        background:var(--sd-green-800,#1d6045);
        border:1px solid var(--sd-green-800,#1d6045);
    }

    .agenda-btn-primary:hover,
    .agenda-btn-edit:hover {
        color:#fff;
        background:var(--sd-green-900,#174b37);
    }

    .agenda-btn-delete {
        color:#c23c3c;
        background:#fff1f1;
        border:1px solid #f2d6d6;
    }

    .agenda-btn-delete:hover {
        color:#a92d2d;
        background:#ffe5e5;
    }

    .agenda-panel {
        overflow:hidden;
        background:#fff;
        border:1px solid var(--sd-line,#e3eae3);
        border-radius:13px;
        box-shadow:var(--sd-shadow,0 8px 24px rgba(24,59,42,.06));
    }

    .agenda-panel-head {
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:14px;
        padding:19px 21px;
        border-bottom:1px solid var(--sd-line,#e3eae3);
        background:#fff;
    }

    .agenda-panel-head h2 {
        margin:0;
        color:var(--sd-green-950,#123d2d);
        font-size:15px;
        font-weight:750;
    }

    .agenda-panel-head p {
        margin:5px 0 0;
        color:var(--sd-muted,#758279);
        font-size:13px;
        line-height:1.5;
    }

    .agenda-total {
        flex-shrink:0;
        padding:7px 11px;
        border-radius:20px;
        background:var(--sd-sage-100,#e8f1e9);
        color:var(--sd-green-900,#174b37);
        font-size:12px;
        font-weight:700;
    }

    .agenda-table-wrap { overflow-x:auto; }

    .agenda-table {
        width:100%;
        margin:0;
        border-collapse:collapse;
        font-size:13px;
    }

    .agenda-table th {
        padding:13px 14px;
        background:var(--sd-sage-50,#f4f7f2);
        color:#405448;
        border-bottom:1px solid var(--sd-line,#e3eae3);
        text-align:left;
        font-size:12px;
        font-weight:750;
        white-space:nowrap;
    }

    .agenda-table td {
        padding:14px;
        color:#526258;
        border-bottom:1px solid #edf1ed;
        vertical-align:middle;
        line-height:1.55;
    }

    .agenda-table tbody tr:last-child td { border-bottom:0; }
    .agenda-table tbody tr:hover { background:#fbfdfb; }

    .agenda-cover {
        width:88px;
        height:62px;
        object-fit:contain;
        padding:3px;
        border:1px solid var(--sd-line,#e3eae3);
        border-radius:8px;
        background:#f8faf7;
    }

    .agenda-name {
        display:block;
        max-width:260px;
        color:var(--sd-green-950,#123d2d);
        font-size:13px;
        font-weight:700;
    }

    .agenda-status {
        display:inline-flex;
        padding:6px 10px;
        border-radius:20px;
        font-size:11px;
        font-weight:700;
        white-space:nowrap;
    }

    .agenda-status-published {
        color:#187344;
        background:#e8f4e9;
    }

    .agenda-status-draft {
        color:#68736c;
        background:#eef1ee;
    }

    .agenda-actions {
        display:flex;
        align-items:center;
        gap:6px;
    }

    .agenda-actions .agenda-btn {
        min-height:32px;
        padding:7px 10px;
        font-size:12px;
    }

    .agenda-empty {
        padding:42px 20px;
        text-align:center;
        color:var(--sd-muted,#758279);
        font-size:13px;
    }

    .agenda-empty h3 {
        margin:0 0 7px;
        color:var(--sd-green-900,#174b37);
        font-size:16px;
        font-weight:750;
    }

    .agenda-pagination {
        padding:16px 20px;
        border-top:1px solid var(--sd-line,#e3eae3);
    }

    .agenda-pagination .pagination { margin:0; }

    .agenda-alert {
        margin-bottom:16px;
        padding:12px 15px;
        border-radius:9px;
        font-size:13px;
    }

    @media(max-width:680px) {
        .agenda-heading {
            flex-direction:column;
            align-items:stretch;
        }

        .agenda-heading h1 { font-size:22px; }
        .agenda-heading .agenda-btn { align-self:flex-start; }
        .agenda-panel-head { align-items:flex-start; padding:16px; }
        .agenda-table th,
        .agenda-table td { padding:11px; }
    }
</style>
@endpush

@section('content')
<div class="agenda-page">

    <header class="agenda-heading">
        <div>
            <p class="sd-eyebrow">Publikasi Fakultas</p>
            <h1>Kelola Newsletter</h1>
            <p>Kelola informasi dan publikasi kegiatan Fakultas Sains dan Teknologi.</p>
        </div>

        <a href="{{ route('admin.agenda.create') }}"
           class="agenda-btn agenda-btn-primary">
            + Tambah Newsletter
        </a>
    </header>

    @if (session('success'))
        <div class="sd-alert agenda-alert">
            {{ session('success') }}
        </div>
    @endif

    <section class="agenda-panel">

        <div class="agenda-panel-head">
            <div>
                <h2>Daftar Newsletter</h2>
                <p>Informasi newsletter yang telah ditambahkan dan dikelola.</p>
            </div>

            <span class="agenda-total">
                {{ $agendas->total() }} Newsletter
            </span>
        </div>

        @if ($agendas->count() > 0)

            <div class="agenda-table-wrap">
                <table class="agenda-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Sampul</th>
                            <th>Judul Newsletter</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($agendas as $agenda)
                            <tr>
                                <td>{{ $agendas->firstItem() + $loop->index }}</td>

                                <td>
                                    @if ($agenda->gambar)
                                        <img
                                            src="{{ asset('storage/' . $agenda->gambar) }}"
                                            alt="Sampul {{ $agenda->judul }}"
                                            class="agenda-cover"
                                        >
                                    @else
                                        <span style="color:#929c95">Belum ada</span>
                                    @endif
                                </td>

                                <td>
                                    <strong class="agenda-name">
                                        {{ $agenda->judul }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $agenda->tanggal_mulai->format('d-m-Y') }}

                                    @if ($agenda->tanggal_selesai)
                                        – {{ $agenda->tanggal_selesai->format('d-m-Y') }}
                                    @endif
                                </td>

                                <td>{{ $agenda->lokasi ?? '-' }}</td>

                                <td>
                                    @if ($agenda->status === 'publikasi')
                                        <span class="agenda-status agenda-status-published">
                                            Dipublikasikan
                                        </span>
                                    @else
                                        <span class="agenda-status agenda-status-draft">
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="agenda-actions">

                                        <a
                                            href="{{ route('admin.agenda.edit', $agenda->id) }}"
                                            class="agenda-btn agenda-btn-edit"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('admin.agenda.destroy', $agenda->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus newsletter ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="agenda-btn agenda-btn-delete"
                                            >
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="agenda-pagination">
                {{ $agendas->links() }}
            </div>

        @else

            <div class="agenda-empty">
                <h3>Belum Ada Newsletter</h3>
                Silakan tambahkan informasi newsletter terlebih dahulu.
            </div>

        @endif

    </section>
</div>
@endsection