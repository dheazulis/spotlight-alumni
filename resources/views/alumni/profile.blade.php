
@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('breadcrumb', 'Profil Saya')

@section('content')
<div class="profile-page">
    <div class="profile-card">
        <div class="profile-heading">
            <h1>Profil Saya</h1>
            <p>Periksa dan perbarui informasi profil alumni kamu.</p>
        </div>

        @if (session('status'))
            <div class="notice">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('alumni.profile.update') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="foto_profil">Foto Profil</label>

                @if ($user->foto_profil)
                    <div class="profile-photo">
                        <img
                            src="{{ asset('storage/' . $user->foto_profil) }}"
                            alt="Foto Profil"
                        >
                    </div>
                @endif

                <input
                    type="file"
                    id="foto_profil"
                    name="foto_profil"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                <small>Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.</small>
            </div>

            <div class="field">
                <label for="nama">Nama Lengkap</label>
                <input
                    type="text"
                    id="nama"
                    value="{{ $user->name }}"
                    readonly
                >
            </div>

            <div class="field">
                <label for="nim">NIM</label>
                <input
                    type="text"
                    id="nim"
                    value="{{ $user->nim ?? '-' }}"
                    readonly
                >
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    value="{{ $user->email }}"
                    readonly
                >
            </div>

            <div class="field">
                <label for="program_studi">Program Studi</label>
                <input
                    type="text"
                    id="program_studi"
                    name="program_studi"
                    value="{{ old('program_studi', $user->program_studi) }}"
                >
            </div>

            <div class="field">
                <label for="tahun_masuk">Tahun Masuk</label>
                <input
                    type="number"
                    id="tahun_masuk"
                    name="tahun_masuk"
                    min="1990"
                    max="{{ date('Y') }}"
                    value="{{ old('tahun_masuk', $user->tahun_masuk) }}"
                >
            </div>

            <div class="field">
                <label for="tahun_lulus">Tahun Lulus</label>
                <input
                    type="number"
                    id="tahun_lulus"
                    name="tahun_lulus"
                    min="1990"
                    max="{{ date('Y') + 10 }}"
                    value="{{ old('tahun_lulus', $user->tahun_lulus) }}"
                >
            </div>

            <div class="field">
                <label for="nomor_hp">Nomor HP</label>
                <input
                    type="text"
                    id="nomor_hp"
                    name="nomor_hp"
                    maxlength="20"
                    value="{{ old('nomor_hp', $user->nomor_hp) }}"
                >
            </div>

            <div class="field">
                <label for="status_setelah_lulus">Status Setelah Lulus</label>

                <select id="status_setelah_lulus" name="status_setelah_lulus">
                    <option value="">-- Pilih Status --</option>

                    <option value="Bekerja"
                        @selected(old('status_setelah_lulus', $user->status_setelah_lulus) === 'Bekerja')>
                        Bekerja
                    </option>

                    <option value="Wirausaha"
                        @selected(old('status_setelah_lulus', $user->status_setelah_lulus) === 'Wirausaha')>
                        Wirausaha
                    </option>

                    <option value="Melanjutkan Studi"
                        @selected(old('status_setelah_lulus', $user->status_setelah_lulus) === 'Melanjutkan Studi')>
                        Melanjutkan Studi
                    </option>

                    <option value="Belum Bekerja"
                        @selected(old('status_setelah_lulus', $user->status_setelah_lulus) === 'Belum Bekerja')>
                        Belum Bekerja
                    </option>
                </select>
            </div>

            <div class="field">
                <label for="tentang_saya">Tentang Saya</label>

                <textarea
                    id="tentang_saya"
                    name="tentang_saya"
                    maxlength="3000"
                    placeholder="Ceritakan tentang diri kamu, latar belakang, keahlian, pengalaman, dan minat..."
                >{{ old('tentang_saya', $user->tentang_saya) }}</textarea>

                <small>
                    Ceritakan secara singkat tentang diri kamu agar pengunjung
                    dapat mengenal profil dan latar belakangmu.
                    Maksimal 3.000 karakter.
                </small>
            </div>

            <div class="field">
                <label for="pesan_motivasi">Quotes</label>

                <textarea
                    id="pesan_motivasi"
                    name="pesan_motivasi"
                    maxlength="1000"
                    placeholder="Tuliskan pesan atau motivasi untuk mahasiswa dan sesama alumni..."
                >{{ old('pesan_motivasi', $user->pesan_motivasi) }}</textarea>

                <small>
                    Pesan ini dapat ditampilkan apabila kamu terpilih
                    sebagai Alumni Inspiratif dan pengajuannya disetujui admin.
                    Maksimal 1.000 karakter.
                </small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    Simpan Perubahan
                </button>

                <a href="{{ route('alumni.password.edit') }}" class="btn-password">
                    Ganti Password
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-page {
        width: 100%;
        color: #26344b;
    }

    .profile-card {
        width: 100%;
        max-width: 850px;
        margin: 0 auto;
        padding: 28px;
        background: #fff;
        border: 1px solid #e4f0e8;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(29, 96, 69, 0.04);
    }

    .profile-heading {
        margin-bottom: 25px;
    }

    .profile-heading h1 {
        margin: 0 0 8px;
        color: #1d6045;
        font-size: 25px;
    }

    .profile-heading p {
        margin: 0;
        color: #68758a;
        font-size: 14px;
        line-height: 1.6;
    }

    .field {
        margin-bottom: 20px;
    }

    .field label {
        display: block;
        margin-bottom: 8px;
        color: #26344b;
        font-size: 14px;
        font-weight: 600;
    }

    .field input,
    .field select,
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
    .field select:focus,
    .field textarea:focus {
        border-color: #1d6045;
        box-shadow: 0 0 0 3px rgba(29, 96, 69, 0.08);
    }

    .field input[readonly] {
        background: #f0f3f8;
        color: #68758a;
        cursor: not-allowed;
    }

    .field textarea {
        min-height: 130px;
        resize: vertical;
        line-height: 1.6;
    }

    .field small {
        display: block;
        margin-top: 7px;
        color: #68758a;
        font-size: 12px;
        line-height: 1.6;
    }

    .profile-photo {
        margin: 12px 0;
    }

    .profile-photo img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 3px solid #e4f0e8;
        border-radius: 50%;
    }

    .notice {
        padding: 13px 15px;
        margin-bottom: 20px;
        border: 1px solid #cce8d2;
        border-radius: 8px;
        background: #e8f5e9;
        color: #276738;
        font-size: 14px;
    }

    .error-box {
        padding: 12px 15px;
        margin-bottom: 20px;
        border: 1px solid #f5c2c0;
        border-radius: 8px;
        background: #fff1f0;
        color: #b42318;
        font-size: 13px;
    }

    .error-box ul {
        margin: 0;
        padding-left: 20px;
    }

    .error-box li {
        margin-bottom: 5px;
    }

    .form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 25px;
    }

    .btn-save,
    .btn-password {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 43px;
        padding: 12px 18px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-save {
        background: #1d6045;
        color: #fff;
    }

    .btn-save:hover {
        background: #164b35;
    }

    .btn-password {
        background: #e4f0e8;
        color: #1d6045;
    }

    .btn-password:hover {
        background: #d4e8da;
    }

    @media (max-width: 600px) {
        .profile-card {
            padding: 20px 16px;
        }

        .profile-heading h1 {
            font-size: 22px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-save,
        .btn-password {
            width: 100%;
        }
    }
</style>
@endpush
