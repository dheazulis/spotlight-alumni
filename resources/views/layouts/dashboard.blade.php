
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Spotlight Alumni FST</title>
    <link rel="stylesheet" href="{{ asset('css/spotlight-dashboard.css') }}">
    @stack('styles')

    <style>
        /* Perbaikan khusus tombol Logout */
        .sd-sidebar {
            display: flex;
            flex-direction: column;
        }

        .sd-nav {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .sd-logout-wrapper {
            margin-top: auto;
            padding: 20px 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
        }

        .sd-logout-wrapper form {
            width: 100%;
            margin: 0;
        }

        .sd-logout-button {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 14px;
            width: 100%;
            padding: 14px 18px;
            background: #216348;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .sd-logout-button:hover {
            background: #2c7959;
            transform: translateY(-2px);
        }

        .sd-logout-icon {
            font-size: 19px;
            line-height: 1;
        }
    </style>
</head>

<body class="sd-body">

@php($currentRole = auth()->user()->role ?? '')

<div class="sd-shell" id="sdShell">

    <aside class="sd-sidebar">

        <a class="sd-brand" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo-fst.png') }}"
                alt="Logo Fakultas Sains dan Teknologi"
                class="sd-brand-logo">

            <span>
                <strong>SPOTLIGHT ALUMNI</strong>
                <small>Fakultas Sains dan Teknologi</small>
            </span>
        </a>

        <nav class="sd-nav" aria-label="Navigasi utama">

            <div class="sd-nav-label">Menu utama</div>

            <a href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="sd-nav-icon">⌂</span>
                Dashboard
            </a>

            @if($currentRole === 'admin')

                <a href="{{ route('admin.alumni.index') }}"
                    class="{{ request()->routeIs('admin.alumni.*') ? 'active' : '' }}">
                    <span class="sd-nav-icon">♙</span>
                    Data Alumni
                </a>

                <a href="{{ route('admin.alumni-inspiratif.index') }}"
                    class="{{ request()->routeIs('admin.alumni-inspiratif.*') ? 'active' : '' }}">
                    <span class="sd-nav-icon">✦</span>
                    Persetujuan Alumni
                </a>

                <a href="{{ route('admin.job-vacancies.index') }}"
                    class="{{ request()->routeIs('admin.job-vacancies.*') ? 'active' : '' }}">
                    <span class="sd-nav-icon">▤</span>
                    Persetujuan Loker
                </a>

                <a href="{{ route('admin.agenda.index') }}"
                    class="{{ request()->routeIs('admin.agenda.*') ? 'active' : '' }}">
                    <span class="sd-nav-icon">▦</span>
                    Newsletter
                </a>

                <a href="{{ route('admin.laporan-alumni.index') }}"
                    class="{{ request()->routeIs('admin.laporan-alumni.*') ? 'active' : '' }}">
                    <span class="sd-nav-icon">▥</span>
                    Rekapitulasi
                </a>

                <div class="sd-nav-label" style="margin-top:14px">
                    Sistem
                </div>

                <a href="{{ route('database-backup.index') }}">
                    <span class="sd-nav-icon">⇧</span>
                    Backup Database
                </a>

                <a href="{{ route('admin.database-restore.index') }}">
                    <span class="sd-nav-icon">↻</span>
                    Restore Database
                </a>

            @elseif($currentRole === 'pengelola_data')

                <a href="{{ route('pengelola.alumni.index') }}">
                    <span class="sd-nav-icon">♙</span>
                    Data Alumni
                </a>

                <a href="{{ route('pengelola.alumni-inspiratif.index') }}">
                    <span class="sd-nav-icon">✦</span>
                    Rekomendasi Alumni Inspiratif
                </a>

                <a href="{{ route('pengelola.laporan-alumni.index') }}">
                    <span class="sd-nav-icon">▥</span>
                    Rekapitulasi
                </a>

                <div class="sd-nav-label" style="margin-top:14px">
                    Sistem
                </div>

                <a href="{{ route('database-backup.index') }}">
                    <span class="sd-nav-icon">⇧</span>
                    Backup Database
                </a>

            @elseif($currentRole === 'alumni')

                <a href="{{ route('alumni.profile') }}">
                    <span class="sd-nav-icon">♙</span>
                    Profil Saya
                </a>

                <a href="{{ route('alumni.career-journeys.index') }}">
                    <span class="sd-nav-icon">↗</span>
                    Perjalanan Karier
                </a>

                <a href="{{ route('alumni.job-vacancies.index') }}">
                    <span class="sd-nav-icon">▤</span>
                    Informasi Lowongan Kerja
                </a>

            @endif

            <!-- TOMBOL LOGOUT -->
            <div class="sd-logout-wrapper">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="sd-logout-button">
                        <span class="sd-logout-icon">⇥</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>

        </nav>

        <div class="sd-sidebar-bottom">
            Spotlight Alumni FST<br>
            Portal informasi dan jejaring alumni
        </div>

    </aside>

    <main class="sd-main">

        <header class="sd-topbar">

            <div style="display:flex;align-items:center;gap:12px">

                <button class="sd-mobile-toggle"
                    type="button"
                    aria-label="Buka menu"
                    onclick="document.getElementById('sdShell').classList.toggle('sd-menu-open')">
                    ☰
                </button>

                <span class="sd-breadcrumb">
                    Spotlight Alumni / @yield('breadcrumb', 'Dashboard')
                </span>

            </div>

            <div class="sd-user">

                <span class="sd-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </span>

                <span class="sd-user-name">
                    {{ auth()->user()->name ?? 'Pengguna' }}
                </span>

            </div>

        </header>

        <section class="sd-content">

            @if(session('status'))
                <div class="sd-alert">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('success'))
                <div class="sd-alert">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')

        </section>

    </main>

</div>

@stack('scripts')

</body>
</html>
