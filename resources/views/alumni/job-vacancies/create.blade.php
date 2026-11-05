
@extends('layouts.dashboard')

@section('title', 'Ajukan Lowongan Kerja')
@section('breadcrumb', 'Ajukan Lowongan Kerja')

@section('content')
<div class="job-page">
    <div class="page-heading">
        <h1>Ajukan Lowongan Kerja</h1>
        <p>Isi informasi lowongan dengan benar. Pengajuan akan diperiksa oleh admin sebelum dipublikasikan.</p>
    </div>

    <section class="job-card">
        <div class="notice">
            Lowongan yang kamu kirim tidak langsung tampil ke publik.
            Tunggu sampai admin menyetujui pengajuan.
        </div>

        @if ($errors->any())
            <div class="error-box">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('alumni.job-vacancies.store') }}" method="POST">
            @csrf

            <div class="field">
                <label for="nama_perusahaan">Nama Perusahaan/Instansi *</label>
                <input
                    type="text"
                    id="nama_perusahaan"
                    name="nama_perusahaan"
                    value="{{ old('nama_perusahaan') }}"
                    maxlength="255"
                    required
                >
            </div>

            <div class="field">
                <label for="posisi">Posisi Pekerjaan *</label>
                <input
                    type="text"
                    id="posisi"
                    name="posisi"
                    value="{{ old('posisi') }}"
                    maxlength="255"
                    required
                >
            </div>

            <div class="field">
                <label for="bidang_karier">Bidang Karier</label>
                <input
                    type="text"
                    id="bidang_karier"
                    name="bidang_karier"
                    value="{{ old('bidang_karier') }}"
                    maxlength="255"
                    placeholder="Contoh: Teknologi Informasi"
                >
            </div>

            <div class="field">
                <label for="lokasi">Lokasi Pekerjaan</label>
                <input
                    type="text"
                    id="lokasi"
                    name="lokasi"
                    value="{{ old('lokasi') }}"
                    maxlength="255"
                    placeholder="Contoh: Padang, Sumatera Barat"
                >
            </div>

            <div class="field">
                <label for="deskripsi">Deskripsi Pekerjaan *</label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    maxlength="10000"
                    required
                >{{ old('deskripsi') }}</textarea>
            </div>

            <div class="field">
                <label for="kualifikasi">Kualifikasi/Persyaratan</label>
                <textarea
                    id="kualifikasi"
                    name="kualifikasi"
                    maxlength="10000"
                >{{ old('kualifikasi') }}</textarea>
            </div>

            <div class="field">
                <label for="tanggal_tutup">Batas Akhir Lamaran</label>
                <input
                    type="date"
                    id="tanggal_tutup"
                    name="tanggal_tutup"
                    value="{{ old('tanggal_tutup') }}"
                    min="{{ now()->toDateString() }}"
                >
            </div>

            <div class="field">
                <label for="kontak_lamaran">Kontak Lamaran</label>
                <input
                    type="text"
                    id="kontak_lamaran"
                    name="kontak_lamaran"
                    value="{{ old('kontak_lamaran') }}"
                    maxlength="255"
                    placeholder="Email atau nomor kontak"
                >
            </div>

            <div class="field">
                <label for="tautan_lamaran">Tautan Lamaran</label>
                <input
                    type="url"
                    id="tautan_lamaran"
                    name="tautan_lamaran"
                    value="{{ old('tautan_lamaran') }}"
                    maxlength="2000"
                    placeholder="https://..."
                >
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    Kirim Pengajuan
                </button>

                <a
                    href="{{ route('alumni.job-vacancies.index') }}"
                    class="btn-secondary"
                >
                    Batal
                </a>
            </div>
        </form>
    </section>
</div>
@endsection

@push('styles')
<style>
    .job-page {
        width: 100%;
        color: #26344b;
    }

    .page-heading {
        margin-bottom: 22px;
    }

    .page-heading h1 {
        margin: 0 0 8px;
        color: #1d6045;
        font-size: 25px;
    }

    .page-heading p {
        margin: 0;
        color: #68758a;
        font-size: 14px;
        line-height: 1.6;
    }

    .job-card {
        width: 100%;
        max-width: 850px;
        margin: 0 auto;
        padding: 26px;
        background: #fff;
        border: 1px solid #e4f0e8;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(29, 96, 69, 0.04);
    }

    .notice {
        padding: 13px 15px;
        margin-bottom: 22px;
        border: 1px solid #d5e9dc;
        border-radius: 8px;
        background: #e8f5e9;
        color: #276738;
        font-size: 13px;
        line-height: 1.6;
    }

    .error-box {
        padding: 13px 15px;
        margin-bottom: 20px;
        border: 1px solid #f5c2c0;
        border-radius: 8px;
        background: #fff1f0;
        color: #b42318;
        font-size: 13px;
    }

    .error-box ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .error-box li {
        margin-bottom: 5px;
    }

    .field {
        margin-bottom: 19px;
    }

    .field label {
        display: block;
        margin-bottom: 8px;
        color: #26344b;
        font-size: 14px;
        font-weight: 600;
    }

    .field input,
    .field textarea {
        display: block;
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #dce9df;
        border-radius: 8px;
        background: #fff;
        color: #26344b;
        font-family: Arial, sans-serif;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .field input:focus,
    .field textarea:focus {
        border-color: #1d6045;
        box-shadow: 0 0 0 3px rgba(29, 96, 69, 0.08);
    }

    .field textarea {
        min-height: 120px;
        resize: vertical;
        line-height: 1.6;
    }

    .form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 25px;
    }

    .btn-primary,
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 11px 17px;
        border: none;
        border-radius: 7px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-primary {
        background: #1d6045;
        color: #fff;
    }

    .btn-primary:hover {
        background: #164b35;
    }

    .btn-secondary {
        background: #64748b;
        color: #fff;
    }

    .btn-secondary:hover {
        background: #475569;
    }

    @media (max-width: 600px) {
        .job-card {
            padding: 20px 16px;
        }

        .page-heading h1 {
            font-size: 22px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
        }
    }
</style>
@endpush
