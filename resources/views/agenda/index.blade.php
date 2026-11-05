<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Newsletter | Spotlight Alumni FST</title>

    <link rel="stylesheet" href="{{ asset('css/spotlight-public-home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/spotlight-directory.css') }}">

    <style>
        .sp-newsletter-main {
            width: min(1120px, 88%);
            margin: 0 auto;
            padding: 55px 0 75px;
        }

        .sp-newsletter-heading {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 25px;
            margin-bottom: 30px;
        }

        .sp-newsletter-heading h1 {
            color: #244c38;
            font-size: clamp(30px, 4vw, 42px);
            margin: 10px 0;
        }

        .sp-newsletter-heading p {
            color: #718076;
            margin: 0;
        }

        .sp-newsletter-count {
            color: #315d43;
            text-align: right;
        }

        .sp-newsletter-count strong {
            display: block;
            font-size: 30px;
        }

        .sp-newsletter-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        .sp-newsletter-card {
            background: #fff;
            border: 1px solid #e6ebe6;
            border-radius: 13px;
            overflow: hidden;
            transition: .2s;
        }

        .sp-newsletter-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(30, 65, 43, .09);
        }

        .sp-newsletter-card a {
            display: block;
            height: 100%;
            color: inherit;
            text-decoration: none;
        }

        .sp-newsletter-image {
            width: 100%;
            height: 205px;
            object-fit: cover;
            background: #edf2ed;
        }

        .sp-newsletter-placeholder {
            height: 205px;
            background: #edf2ed;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 9px;
            color: #718076;
        }

        .sp-newsletter-placeholder img {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }

        .sp-newsletter-body {
            padding: 21px;
        }

        .sp-newsletter-date {
            color: #718076;
            font-size: 12px;
        }

        .sp-newsletter-body h2 {
            color: #294c38;
            font-size: 19px;
            line-height: 1.4;
            margin: 12px 0;
        }

        .sp-newsletter-location {
            font-size: 13px;
            color: #718076;
            margin-bottom: 12px;
        }

        .sp-newsletter-description {
            color: #66766b;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 18px;
        }

        .sp-newsletter-detail {
            color: #315d43;
            font-size: 13px;
            font-weight: 700;
        }

        .sp-newsletter-empty {
            background: white;
            border: 1px solid #e6ebe6;
            border-radius: 13px;
            padding: 55px 20px;
            text-align: center;
            color: #718076;
        }

        .sp-newsletter-empty h2 {
            color: #294c38;
            font-size: 21px;
        }

        .sp-newsletter-pagination {
            display: flex;
            justify-content: center;
            margin-top: 35px;
        }

        .sp-newsletter-pagination nav {
            background: transparent;
        }

        @media (max-width: 850px) {
            .sp-newsletter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 560px) {
            .sp-newsletter-main {
                width: 90%;
                padding-top: 35px;
            }

            .sp-newsletter-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .sp-newsletter-count {
                text-align: left;
            }

            .sp-newsletter-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="sp-home sp-directory-page">

    <header class="sp-header">
        <a class="sp-brand" href="{{ route('home') }}">
            <img class="sp-brand-logo" src="{{ asset('images/logo-fst.png') }}" alt="Logo Fakultas Sains dan Teknologi">
            <span class="sp-brand-copy">
                <strong>SPOTLIGHT ALUMNI</strong>
                <small>Fakultas Sains dan Teknologi</small>
            </span>
        </a>

        <button class="sp-menu-toggle" type="button"
            aria-label="Buka navigasi"
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

    <main class="sp-newsletter-main">

        <section class="sp-newsletter-heading">
            <div>
                <span class="sp-eyebrow">Informasi Alumni FST</span>
                <h1>Newsletter</h1>
                <p>Informasi kegiatan, berita, dan kabar terbaru Fakultas Sains dan Teknologi.</p>
            </div>

            <div class="sp-newsletter-count">
                <strong>{{ $agendas->total() }}</strong>
                <span>Informasi dipublikasikan</span>
            </div>
        </section>

        @if($agendas->count())

            <section class="sp-newsletter-grid">

                @foreach($agendas as $agenda)
                    <article class="sp-newsletter-card">
                        <a href="{{ route('agenda.show', $agenda->id) }}">

                            @if($agenda->gambar)
                                <img
                                    src="{{ asset('storage/' . $agenda->gambar) }}"
                                    alt="{{ $agenda->judul }}"
                                    class="sp-newsletter-image"
                                    loading="lazy"
                                >
                            @else
                                <div class="sp-newsletter-placeholder">
                                    <img src="{{ asset('images/logo-fst.png') }}" alt="">
                                    <small>Newsletter FST</small>
                                </div>
                            @endif

                            <div class="sp-newsletter-body">

                                <div class="sp-newsletter-date">
                                    {{ \Carbon\Carbon::parse($agenda->tanggal_mulai)->translatedFormat('d F Y') }}

                                    @if($agenda->tanggal_selesai)
                                        – {{ \Carbon\Carbon::parse($agenda->tanggal_selesai)->translatedFormat('d F Y') }}
                                    @endif
                                </div>

                                <h2>{{ $agenda->judul }}</h2>

                                @if($agenda->lokasi)
                                    <div class="sp-newsletter-location">
                                        📍 {{ $agenda->lokasi }}
                                    </div>
                                @endif

                                <p class="sp-newsletter-description">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($agenda->deskripsi), 125) }}
                                </p>

                                <span class="sp-newsletter-detail">
                                    Baca selengkapnya →
                                </span>

                            </div>
                        </a>
                    </article>
                @endforeach

            </section>

            <div class="sp-newsletter-pagination">
                {{ $agendas->links() }}
            </div>

        @else

            <section class="sp-newsletter-empty">
                <h2>Belum Ada Newsletter</h2>
                <p>Informasi yang telah dipublikasikan akan ditampilkan di halaman ini.</p>
            </section>

        @endif

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