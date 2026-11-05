<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $agenda->judul }} | Spotlight Alumni FST</title>

    <link rel="stylesheet" href="{{ asset('css/spotlight-public-home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/spotlight-directory.css') }}">

    <style>
        .sp-news-detail-main {
            max-width: 1000px;
            margin: 50px auto 80px;
            padding: 0 24px;
        }

        .sp-news-detail-back {
            display: inline-block;
            color: #315d43;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .sp-news-detail-card {
            background: #fff;
            border: 1px solid #e5ebe5;
            border-radius: 16px;
            overflow: hidden;
        }

        .sp-news-detail-image {
            display: block;
            width: 100%;
            max-height: 480px;
            object-fit: contain;
            background: #f1f4f1;
        }

        .sp-news-detail-content {
            padding: 35px;
        }

        .sp-news-detail-content h1 {
            color: #244c38;
            font-size: 30px;
            line-height: 1.4;
            margin: 0 0 20px;
        }

        .sp-news-detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 25px;
            color: #78847b;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .sp-news-detail-description {
            color: #46564b;
            font-size: 15px;
            line-height: 1.9;
            white-space: pre-line;
        }

        @media (max-width: 600px) {
            .sp-news-detail-content {
                padding: 22px;
            }

            .sp-news-detail-content h1 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body class="sp-home sp-directory-page">

    <header class="sp-header">
        <a class="sp-brand" href="{{ route('home') }}" aria-label="Spotlight Alumni FST - Beranda">
            <img class="sp-brand-logo" src="{{ asset('images/logo-fst.png') }}" alt="Logo Fakultas Sains dan Teknologi">
            <span class="sp-brand-copy">
                <strong>SPOTLIGHT ALUMNI</strong>
                <small>Fakultas Sains dan Teknologi</small>
            </span>
        </a>

        <button class="sp-menu-toggle" type="button" aria-label="Buka navigasi"
            aria-expanded="false"
            onclick="const nav=document.querySelector('.sp-nav');nav.classList.toggle('is-open');this.setAttribute('aria-expanded',nav.classList.contains('is-open'))">
            ☰
        </button>

        <nav class="sp-nav" aria-label="Navigasi utama">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('public.alumni-directory.index') }}">Direktori Alumni</a>
            <a class="is-active" href="{{ route('agenda.index') }}">Newsletter</a>
            <a href="{{ route('public.job-vacancies.index') }}">Info Loker</a>
            <a class="sp-login" href="{{ route('login') }}">Masuk</a>
        </nav>
    </header>

    <main class="sp-news-detail-main">

        <a href="{{ route('agenda.index') }}" class="sp-news-detail-back">
            ← Kembali ke Newsletter
        </a>

        <article class="sp-news-detail-card">

            @if ($agenda->gambar)
                <img
                    src="{{ asset('storage/' . $agenda->gambar) }}"
                    alt="{{ $agenda->judul }}"
                    class="sp-news-detail-image"
                >
            @endif

            <div class="sp-news-detail-content">

                <h1>{{ $agenda->judul }}</h1>

                <div class="sp-news-detail-meta">
                    <span>
                        📅
                        {{ \Carbon\Carbon::parse($agenda->tanggal_mulai)->translatedFormat('d F Y') }}

                        @if ($agenda->tanggal_selesai)
                            – {{ \Carbon\Carbon::parse($agenda->tanggal_selesai)->translatedFormat('d F Y') }}
                        @endif
                    </span>

                    @if ($agenda->lokasi)
                        <span>📍 {{ $agenda->lokasi }}</span>
                    @endif
                </div>

                <div class="sp-news-detail-description">{{ $agenda->deskripsi }}</div>

            </div>
        </article>

    </main>

    <footer class="sp-footer">
        <a class="sp-brand sp-footer-brand" href="{{ route('home') }}">
            <img class="sp-brand-logo" src="{{ asset('images/logo-fst.png') }}" alt="Logo Fakultas Sains dan Teknologi">
            <span class="sp-brand-copy">
                <strong>SPOTLIGHT ALUMNI</strong>
                <small>Fakultas Sains dan Teknologi</small>
            </span>
        </a>

        <p>Portal informasi, jejaring, dan pengembangan alumni Fakultas Sains dan Teknologi.</p>
        <small>© {{ date('Y') }} Spotlight Alumni FST. Seluruh hak cipta dilindungi.</small>
    </footer>

</body>
</html>