@extends('layouts.dashboard')

@section('title', 'Edit Akun Alumni')
@section('breadcrumb', 'Edit Akun Alumni')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/spotlight-form-alumni.css') }}">
@endpush

@section('content')
<div class="fa-page">

    <div class="fa-heading">
        <h1>Edit Akun Alumni</h1>
        <p>Perbarui informasi akun alumni yang sudah terdaftar.</p>
    </div>

    <section class="fa-card">
        <h2>Informasi Akun Alumni</h2>
        <p class="fa-card-description">
            Perbarui nama atau password akun alumni. NIM dan email bersifat tetap.
        </p>

        @if ($errors->any())
            <div class="fa-error">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.alumni.update', $alumni->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="fa-form-group">
                <label for="name">Nama Lengkap</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $alumni->name) }}"
                    maxlength="255"
                    required
                >
            </div>

            <div class="fa-form-group">
                <label for="nim">NIM</label>
                <input
                    type="text"
                    id="nim"
                    value="{{ $alumni->nim }}"
                    readonly
                >
                <p class="fa-help">
                    NIM bersifat tetap dan tidak dapat diubah.
                </p>
            </div>

            <div class="fa-form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    value="{{ $alumni->email }}"
                    readonly
                >
                <p class="fa-help">
                    Email tidak dapat diubah melalui halaman ini.
                </p>
            </div>

            <div class="fa-form-group">
                <label for="password">Password Baru (Opsional)</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password baru"
                    autocomplete="new-password"
                    minlength="8"
                >
                <p class="fa-help">
                    Minimal 8 karakter. Kosongkan jika tidak ingin mengganti password.
                </p>
            </div>

            <div class="fa-actions">
                <button type="submit" class="fa-button fa-button-primary">
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.alumni.index') }}" class="fa-button fa-button-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </section>
</div>
@endsection