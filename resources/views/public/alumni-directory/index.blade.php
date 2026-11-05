<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Direktori Alumni | Spotlight Alumni FST</title>
    <link rel="stylesheet" href="{{ asset('css/spotlight-public-home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/spotlight-directory.css') }}">
</head>
<body class="sp-home sp-directory-page">
    <header class="sp-header">
        <a class="sp-brand" href="{{ route('home') }}" aria-label="Spotlight Alumni FST - Beranda">
            <img class="sp-brand-logo" src="{{ asset('images/logo-fst.png') }}" alt="Logo Fakultas Sains dan Teknologi">
            <span class="sp-brand-copy"><strong>SPOTLIGHT ALUMNI</strong><small>Fakultas Sains dan Teknologi</small></span>
        </a>
        <button class="sp-menu-toggle" type="button" aria-label="Buka navigasi" aria-expanded="false" onclick="const nav=document.querySelector('.sp-nav');nav.classList.toggle('is-open');this.setAttribute('aria-expanded',nav.classList.contains('is-open'))">☰</button>
        <nav class="sp-nav" aria-label="Navigasi utama">
            <a href="{{ route('home') }}">Beranda</a>
            <a class="is-active" href="{{ route('public.alumni-directory.index') }}">Direktori Alumni</a>
            <a href="{{ route('agenda.index') }}">Newsletter</a>
            <a href="{{ route('public.job-vacancies.index') }}">Info Loker</a>
            <a class="sp-login" href="{{ route('login') }}">Masuk</a>
        </nav>
    </header>

    <main class="sp-directory-main">
        <section class="sp-directory-heading">
            <div>
                <span class="sp-eyebrow">Jejaring Alumni FST</span>
                <h1>Alumni Directory</h1>
                <p>Temukan dan kenali alumni Fakultas Sains dan Teknologi.</p>
            </div>
            <div class="sp-directory-count">
                <strong>{{ $alumni->total() }}</strong>
                <span>Alumni ditampilkan</span>
            </div>
        </section>

        <section class="sp-directory-tools" aria-label="Pencarian alumni">
            <form class="sp-directory-search" method="GET" action="{{ route('public.alumni-directory.index') }}">
                <label for="cari-alumni">Cari alumni</label>
                <div class="sp-directory-search-row">
                    <span class="sp-search-icon" aria-hidden="true">⌕</span>
                    <input id="cari-alumni" type="search" name="cari" value="{{ $kataKunci }}" placeholder="Cari nama, program studi, atau tahun lulus...">
                    <button type="submit">Cari Alumni</button>
                    @if($kataKunci !== '')
                        <a class="sp-directory-reset" href="{{ route('public.alumni-directory.index') }}">Reset</a>
                    @endif
                </div>
            </form>
        </section>

        @if ($alumni->count())
            <section class="sp-directory-grid" aria-label="Daftar alumni">
                @foreach ($alumni as $item)
                    <article class="sp-directory-card">
                        <a class="sp-directory-photo-link" href="{{ route('public.alumni.show', $item->id) }}" aria-label="Lihat profil {{ $item->name }}">
                            @if($item->foto_profil)
                                <img class="sp-directory-photo" src="{{ asset('storage/' . $item->foto_profil) }}" alt="Foto {{ $item->name }}" loading="lazy">
                            @else
                                <div class="sp-directory-photo-fallback" aria-label="Foto belum tersedia">{{ mb_strtoupper(mb_substr($item->name, 0, 1)) }}</div>
                            @endif
                        </a>
                        <div class="sp-directory-card-body">
                            <h2>{{ $item->name }}</h2>
                            <p class="sp-directory-prodi">{{ $item->program_studi }}</p>
                            <div class="sp-directory-meta">
                                <span class="sp-directory-year">Lulusan {{ $item->tahun_lulus }}</span>
                                <span class="sp-directory-status">{{ $item->status_setelah_lulus }}</span>
                            </div>
                            <a class="sp-directory-detail" href="{{ route('public.alumni.show', $item->id) }}">Lihat Profil <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                @endforeach
            </section>

            <nav class="sp-directory-pagination" aria-label="Navigasi halaman">
                {{ $alumni->links() }}
            </nav>
        @else
            <section class="sp-directory-empty">
                <span class="sp-directory-empty-icon" aria-hidden="true">◎</span>
                <h2>{{ $kataKunci !== '' ? 'Alumni tidak ditemukan' : 'Belum ada data alumni' }}</h2>
                <p>{{ $kataKunci !== '' ? 'Coba gunakan kata kunci nama, program studi, atau tahun lulus yang berbeda.' : 'Profil alumni yang telah memenuhi persyaratan akan ditampilkan di halaman ini.' }}</p>
                @if($kataKunci !== '')
                    <a href="{{ route('public.alumni-directory.index') }}">Tampilkan semua alumni</a>
                @endif
            </section>
        @endif
    </main>

    <footer class="sp-footer">
        <a class="sp-brand sp-footer-brand" href="{{ route('home') }}"><img class="sp-brand-logo" src="{{ asset('images/logo-fst.png') }}" alt="Logo Fakultas Sains dan Teknologi"><span class="sp-brand-copy"><strong>SPOTLIGHT ALUMNI</strong><small>Fakultas Sains dan Teknologi</small></span></a>
        <p>Portal informasi, jejaring, dan pengembangan alumni Fakultas Sains dan Teknologi.</p>
        <small>© {{ date('Y') }} Spotlight Alumni FST. Seluruh hak cipta dilindungi.</small>
    </footer>
</body>
</html>
