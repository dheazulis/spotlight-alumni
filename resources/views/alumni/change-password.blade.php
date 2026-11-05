
@extends('layouts.dashboard')

@section('title', 'Ganti Password')
@section('breadcrumb', 'Ganti Password')

@section('content')
<div class="password-page">
    <div class="password-card">
        <div class="password-heading">
            <h1>Ganti Password</h1>

            <p class="subtitle">
                Perbarui password akun kamu untuk menjaga keamanan
                informasi pribadi.
            </p>
        </div>

        <div class="info">
            Password baru harus memiliki minimal 8 karakter.
            Pastikan kamu mengingat password yang baru.
        </div>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="success">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('alumni.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="field">
                <label for="current_password">Password Lama</label>

                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    placeholder="Masukkan password lama"
                    autocomplete="current-password"
                    required
                >
            </div>

            <div class="field">
                <label for="password">Password Baru</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password baru"
                    autocomplete="new-password"
                    required
                >
            </div>

            <div class="field">
                <label for="password_confirmation">
                    Konfirmasi Password Baru
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password baru"
                    autocomplete="new-password"
                    required
                >
            </div>

            <button type="submit" class="btn-save">
                Simpan Password Baru
            </button>
        </form>

        <a href="{{ route('alumni.profile') }}" class="back-link">
            ← Kembali ke Profil Saya
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
    .password-page {
        width: 100%;
        color: #26344b;
    }

    .password-card {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 30px;
        background: #fff;
        border: 1px solid #e4f0e8;
        border-radius: 14px;
        box-shadow: 0 8px 25px rgba(44, 72, 120, 0.05);
    }

    .password-heading {
        margin-bottom: 24px;
    }

    .password-heading h1 {
        margin: 0 0 10px;
        color: #1d6045;
        font-size: 25px;
    }

    .subtitle {
        margin: 0;
        color: #758198;
        font-size: 14px;
        line-height: 1.6;
    }

    .info {
        padding: 13px 15px;
        margin-bottom: 22px;
        border: 1px solid #d5e9dc;
        border-radius: 8px;
        background: #e4f0e8;
        color: #1d6045;
        font-size: 13px;
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

    .field input {
        width: 100%;
        padding: 13px 14px;
        border: 1px solid #dce9df;
        border-radius: 8px;
        background: #fff;
        color: #26344b;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .field input:focus {
        border-color: #1d6045;
        box-shadow: 0 0 0 3px rgba(29, 96, 69, 0.08);
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

    .success {
        padding: 13px 15px;
        margin-bottom: 20px;
        border: 1px solid #cce8d2;
        border-radius: 8px;
        background: #e8f5e9;
        color: #276738;
        font-size: 14px;
    }

    .btn-save {
        width: 100%;
        padding: 13px 18px;
        border: none;
        border-radius: 8px;
        background: #1d6045;
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-save:hover {
        background: #164b35;
    }

    .back-link {
        display: inline-block;
        margin-top: 22px;
        color: #1d6045;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .password-card {
            padding: 22px 17px;
        }

        .password-heading h1 {
            font-size: 22px;
        }
    }
</style>
@endpush
