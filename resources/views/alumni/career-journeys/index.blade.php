
@extends('layouts.dashboard')

@section('title', 'Perjalanan Karier')
@section('breadcrumb', 'Perjalanan Karier')

@section('content')
<div class="cj-container">

    <div class="cj-heading">
        <div>
            <h2>Perjalanan Karier</h2>
            <p>Daftar pengalaman dan perjalanan karier yang sudah kamu tambahkan.</p>
        </div>

        <a href="{{ route('alumni.career-journeys.create') }}" class="btn-add">
            + Tambah Karier
        </a>
    </div>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    @forelse ($careerJourneys as $career)
        <div class="cj-card">

            <div class="cj-card-header">
                <div class="cj-title-group">
                    <h3>{{ $career->jabatan }}</h3>
                    <span class="company-name">{{ $career->nama_perusahaan }}</span>
                </div>

                <div class="cj-actions">
                    <a href="{{ route('alumni.career-journeys.edit', $career->id) }}"
                       class="btn-edit">
                        Edit
                    </a>

                    <form action="{{ route('alumni.career-journeys.destroy', $career->id) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus riwayat karier ini?')">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn-delete">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>

            <div class="cj-info">
                @if ($career->bidang_karier)
                    <span class="info-item">
                        <strong>Bidang:</strong> {{ $career->bidang_karier }}
                    </span>
                @endif

                <span class="info-item">
                    <strong>Mulai:</strong>
                    {{ \Carbon\Carbon::parse($career->tanggal_mulai)->format('d M Y') }}
                </span>

                <span class="info-item">
                    <strong>Selesai:</strong>
                    @if ($career->masih_bekerja)
                        <span class="status-active">Masih Bekerja</span>
                    @elseif ($career->tanggal_selesai)
                        {{ \Carbon\Carbon::parse($career->tanggal_selesai)->format('d M Y') }}
                    @else
                        -
                    @endif
                </span>
            </div>

            @if ($career->deskripsi)
                <div class="cj-description">
                    {{ $career->deskripsi }}
                </div>
            @endif

        </div>
    @empty
        <div class="cj-empty">
            <h3>Belum Ada Perjalanan Karier</h3>
            <p>Kamu belum menambahkan pengalaman karier.</p>
            <a href="{{ route('alumni.career-journeys.create') }}" class="btn-add">
                Tambah Karier
            </a>
        </div>
    @endforelse

</div>
@endsection

@push('styles')
<style>
.cj-container {
    width: 100%;
}

.cj-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.cj-heading h2 {
    margin: 0 0 6px;
    font-size: 22px;
    font-weight: 700;
    color: #263449;
}

.cj-heading p {
    margin: 0;
    color: #7b8493;
    font-size: 14px;
}

/* Tombol Tambah dan Edit - Hijau */
.btn-add,
.btn-edit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 9px 14px;
    border-radius: 7px;
    background: #198754;
    color: #fff;
    border: 1px solid #198754;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
}

.btn-add:hover,
.btn-edit:hover {
    background: #157347;
    border-color: #157347;
    color: #fff;
}

/* Card Perjalanan Karier */
.cj-card {
    background: #fff;
    border: 1px solid #e7ebf0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 16px;
}

.cj-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
}

.cj-title-group h3 {
    margin: 0 0 5px;
    color: #263449;
    font-size: 17px;
    font-weight: 700;
}

.company-name {
    color: #697586;
    font-size: 14px;
}

/* Tombol Edit dan Hapus */
.cj-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.cj-actions form {
    margin: 0;
}

.btn-edit,
.btn-delete {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 7px 12px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}

/* Edit tetap hijau */
.cj-actions .btn-edit {
    background: #198754;
    color: #fff;
    border: 1px solid #198754;
}

.cj-actions .btn-edit:hover {
    background: #157347;
    border-color: #157347;
    color: #fff;
}

/* Hapus tetap merah */
.btn-delete {
    background: #fff0f0;
    color: #d14343;
    border: 1px solid #f5d5d5;
}

.btn-delete:hover {
    background: #ffe1e1;
    color: #bd3030;
}

/* Informasi Karier */
.cj-info {
    display: flex;
    flex-wrap: wrap;
    gap: 10px 24px;
    margin-top: 17px;
    padding-top: 14px;
    border-top: 1px solid #edf0f3;
}

.info-item {
    color: #687386;
    font-size: 13px;
}

.info-item strong {
    color: #39465a;
    font-weight: 600;
}

.status-active {
    color: #16845b;
    font-weight: 600;
}

.cj-description {
    margin-top: 14px;
    color: #687386;
    font-size: 13px;
    line-height: 1.7;
    white-space: pre-line;
}

/* Tampilan Kosong */
.cj-empty {
    padding: 45px 20px;
    background: #fff;
    border: 1px dashed #d7dde5;
    border-radius: 12px;
    text-align: center;
}

.cj-empty h3 {
    margin: 0 0 8px;
    color: #263449;
    font-size: 17px;
}

.cj-empty p {
    margin: 0 0 18px;
    color: #7b8493;
    font-size: 14px;
}

/* Notifikasi */
.alert-success,
.alert-error {
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 13px;
}

.alert-success {
    background: #eaf7ef;
    color: #187344;
}

.alert-error {
    background: #fff0f0;
    color: #bd3030;
}

/* Responsif */
@media (max-width: 600px) {
    .cj-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .cj-card-header {
        flex-direction: column;
    }

    .cj-actions {
        margin-top: 5px;
    }

    .cj-info {
        flex-direction: column;
        gap: 9px;
    }
}
</style>
@endpush
