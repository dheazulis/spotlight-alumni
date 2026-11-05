@extends('layouts.dashboard')

@section('title', 'Tambah Akun Alumni')
@section('breadcrumb', 'Tambah Akun Alumni')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/spotlight-form-alumni.css') }}">
@endpush

@section('content')
<div class="fa-page">

    <div class="fa-heading">
        <h1>Tambah Akun Alumni</h1>
        <p>Buat akun alumni untuk mengakses sistem Spotlight Alumni FST.</p>
    </div>

    <section class="fa-card">
        <h2>Informasi Akun Alumni</h2>
        <p class="fa-card-description">
            Masukkan nama lengkap, NIM, dan email alumni.
        </p>

        @if (session('status'))
            <div class="fa-notice">{{ session('status') }}</div>
        @endif

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

        <form action="{{ route('admin.alumni.store') }}" method="POST">
            @csrf

            <div class="fa-form-group">
                <label for="name">Nama Lengkap</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap alumni"
                    maxlength="255"
                    required
                >
            </div>

            <div class="fa-form-group">
                <label for="nim">NIM</label>
                <input
                    type="text"
                    id="nim"
                    name="nim"
                    value="{{ old('nim') }}"
                    placeholder="Masukkan NIM alumni"
                    required
                >
                <p class="fa-help">
                    NIM digunakan sebagai password awal akun alumni.
                </p>
            </div>

            <div class="fa-form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email aktif alumni"
                    required
                >
                <p class="fa-help">
                    Email digunakan untuk proses verifikasi akun alumni.
                </p>
            </div>

            <div class="fa-notice">
                Password awal alumni menggunakan NIM. Alumni perlu
                menyelesaikan verifikasi email sebelum mengakses data akun.
            </div>

            <div class="fa-actions">
                <button type="submit" class="fa-button fa-button-primary">
                    Buat Akun Alumni
                </button>

                <a href="{{ route('admin.alumni.index') }}" class="fa-button fa-button-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </section>
</div>
@endsection