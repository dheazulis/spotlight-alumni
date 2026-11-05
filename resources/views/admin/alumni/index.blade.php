@extends('layouts.dashboard')

@section('title', 'Data Alumni')
@section('breadcrumb', 'Data Alumni')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/spotlight-data-alumni.css') }}">
@endpush

@section('content')
@php
    $isAdmin = auth()->user()->role === 'admin';
    $routeIndex = $isAdmin ? route('admin.alumni.index') : route('pengelola.alumni.index');
@endphp

<div class="da-page">
    <div class="da-heading">
        <div>
            <span class="da-eyebrow">PENGELOLAAN DATA</span>
            <h1>Data Alumni</h1>
            <p>Kelola dan telusuri data alumni Fakultas Sains dan Teknologi.</p>
        </div>

        @if ($isAdmin)
            <a href="{{ route('admin.alumni.create') }}" class="da-primary-button">
                <span aria-hidden="true">＋</span> Tambah Alumni
            </a>
        @endif
    </div>

    @if (session('status'))
        <div class="da-alert da-alert-success">{{ session('status') }}</div>
    @endif

    @if (session('success'))
        <div class="da-alert da-alert-success">{{ session('success') }}</div>
    @endif

    <form class="da-filter-panel" action="{{ $routeIndex }}" method="GET">
        <label class="da-filter-field">
            <span>Jurusan</span>
            <select name="jurusan">
                <option value="">Semua Jurusan</option>
                @foreach ($daftarJurusan as $itemJurusan)
                    <option value="{{ $itemJurusan }}" @selected($jurusan === $itemJurusan)>
                        {{ $itemJurusan }}
                    </option>
                @endforeach
            </select>
        </label>

        <label class="da-filter-field">
            <span>Tahun Lulus</span>
            <select name="tahun_lulus">
                <option value="">Semua Tahun Lulus</option>
                @foreach ($daftarTahunLulus as $itemTahun)
                    <option value="{{ $itemTahun }}" @selected((string) $tahunLulus === (string) $itemTahun)>
                        {{ $itemTahun }}
                    </option>
                @endforeach
            </select>
        </label>

        <label class="da-filter-field">
            <span>Status</span>
            <select name="status">
                <option value="">Semua Status</option>
                @foreach ($daftarStatus as $itemStatus)
                    <option value="{{ $itemStatus }}" @selected($status === $itemStatus)>
                        {{ $itemStatus }}
                    </option>
                @endforeach
            </select>
        </label>

        <label class="da-search-field">
            <span>Cari Alumni</span>
            <div class="da-search-control">
                <span aria-hidden="true">⌕</span>
                <input type="search" name="cari" value="{{ $kataKunci }}" placeholder="Cari nama alumni, NIM, email...">
            </div>
        </label>

        <div class="da-filter-actions">
            <button type="submit" class="da-primary-button">Terapkan</button>

            @if ($kataKunci || $jurusan || $tahunLulus || $status)
                <a href="{{ $routeIndex }}" class="da-reset-button">Reset</a>
            @endif
        </div>
    </form>

    <section class="da-table-card">
        <div class="da-table-topline">
            <div>
                <h2>Daftar Alumni</h2>
                <p>
                    Menampilkan {{ $alumni->firstItem() ?? 0 }}–{{ $alumni->lastItem() ?? 0 }}
                    dari {{ $alumni->total() }} alumni
                </p>
            </div>
            <span class="da-total-pill">{{ $alumni->total() }} Alumni</span>
        </div>

        <div class="da-table-wrap">
            <table class="da-table">
                <thead>
                    <tr>
                        <th class="da-number">No.</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Jurusan</th>
                        <th>Tahun Lulus</th>
                        <th>Status</th>
                        <th class="da-actions-heading">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($alumni as $item)
                        @php
                            $statusLabel = $item->status_setelah_lulus ?: 'Belum diisi';
                            $statusClass = str_contains(strtolower($statusLabel), 'kerja')
                                || str_contains(strtolower($statusLabel), 'bekerja')
                                ? 'working'
                                : (str_contains(strtolower($statusLabel), 'wirausaha') ? 'business' : 'other');
                        @endphp

                        <tr>
                            <td class="da-number">{{ $alumni->firstItem() + $loop->index }}</td>

                            <td>
                                <strong class="da-name">{{ $item->name }}</strong>
                                <small class="da-email">{{ $item->email }}</small>
                            </td>

                            <td>{{ $item->nim ?: '—' }}</td>
                            <td>{{ $item->program_studi ?: '—' }}</td>
                            <td>{{ $item->tahun_lulus ?: '—' }}</td>

                            <td>
                                <span class="da-status {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>

                            <td>
                                @if ($isAdmin)
                                    <div class="da-row-actions">
                                        <a href="{{ route('admin.alumni.edit', $item->id) }}"
                                            class="da-icon-action"
                                            title="Edit alumni"
                                            aria-label="Edit alumni">✎</a>

                                        <form action="{{ route('admin.alumni.destroy', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus akun alumni ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="da-icon-action danger"
                                                title="Hapus alumni"
                                                aria-label="Hapus alumni">⌫</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="da-no-action">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="da-empty">
                                <span>◎</span>
                                <strong>Data alumni belum ditemukan</strong>
                                <small>Coba ubah kata kunci atau filter pencarian.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($alumni->hasPages())
            <div class="da-pagination">
                {{ $alumni->links() }}
            </div>
        @endif
    </section>
</div>
@endsection