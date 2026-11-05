
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Info Loker | Spotlight Alumni FST</title>

    <link rel="stylesheet" href="{{ asset('css/spotlight-public-home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/spotlight-directory.css') }}">

    <style>
        .sp-loker-main {
            max-width: 1200px;
            margin: 50px auto 80px;
            padding: 0 24px;
        }

        .sp-loker-heading {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 20px;
            margin-bottom: 35px;
        }

        .sp-loker-heading h1 {
            color: #244c38;
            font-size: 32px;
            margin: 0 0 10px;
        }

        .sp-loker-heading p {
            color: #78847b;
            line-height: 1.7;
            margin: 0;
        }

        .sp-loker-count {
            color: #78847b;
            font-size: 14px;
            white-space: nowrap;
        }

        /* CARD INFO LOKER */
        .sp-loker-list {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .sp-loker-card {
            display: flex;
            align-items: center;
            gap: 22px;
            background: #fff;
            border: 1px solid #e8eee9;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 5px 18px rgba(25, 65, 43, .04);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .sp-loker-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(25, 65, 43, .08);
        }

        .sp-loker-icon {
            width: 68px;
            height: 68px;
            min-width: 68px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eaf3ed;
            color: #176044;
            border-radius: 16px;
            font-size: 30px;
        }

        .sp-loker-content {
            flex: 1;
            min-width: 0;
        }

        .sp-loker-position {
            color: #173b5c;
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 7px;
        }

        .sp-loker-company {
            color: #526157;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .sp-loker-meta {
            color: #78847b;
            font-size: 14px;
            line-height: 1.8;
        }

        .sp-loker-empty {
            background: #fff;
            border: 1px solid #e5ebe5;
            border-radius: 16px;
            padding: 40px 25px;
            text-align: center;
            color: #78847b;
        }

        .sp-loker-empty h2 {
            color: #315d43;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .sp-loker-empty p {
            margin: 0;
            line-height: 1.7;
        }

        @media (max-width: 750px) {
            .sp-loker-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .sp-loker-heading h1 {
                font-size: 26px;
            }

            .sp-loker-card {
                align-items: flex-start;
                gap: 15px;
                padding: 22px;
            }

            .sp-loker-icon {
                width: 54px;
                height: 54px;
                min-width: 54px;
                font-size: 24px;
                border-radius: 13px;
            }

            .sp-loker-position {
                font-size: 17px;
            }

            .sp-loker-company {
                font-size: 14px;
            }

            .sp-loker-meta {
                font-size: 13px;
            }
        }

        @media (max-width: 420px) {
            .sp-loker-card {
                padding: 18px;
                gap: 12px;
            }

            .sp-loker-icon {
                width: 45px;
                height: 45px;
                min-width: 45px;
                font-size: 21px;
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
            <a href="{{ route('agenda.index') }}">Newsletter</a>
            <a class="is-active" href="{{ route('public.job-vacancies.index') }}">Info Loker</a>
            <a class="sp-login" href="{{ route('login') }}">Masuk</a>
        </nav>
    </header>

    <main class="sp-loker-main">

        <section class="sp-loker-heading">
            <div>
                <span class="sp-eyebrow">Peluang Karier Alumni</span>
                <h1>Info Lowongan Kerja</h1>
                <p>
                    Temukan peluang karier dan informasi lowongan kerja
                    yang telah melalui proses persetujuan admin.
                </p>
            </div>

            <div class="sp-loker-count">
                {{ $lowongans->count() }} lowongan kerja tersedia
            </div>
        </section>

        <section class="sp-loker-list">

            @forelse ($lowongans as $lowongan)

                <article class="sp-loker-card">

                    <div class="sp-loker-icon" aria-hidden="true">
                        ▤
                    </div>

                    <div class="sp-loker-content">

                        <h2 class="sp-loker-position">
                            {{ $lowongan->posisi }}
                        </h2>

                        <div class="sp-loker-company">
                            {{ $lowongan->nama_perusahaan }}
                        </div>

                        <div class="sp-loker-meta">

                            @if ($lowongan->bidang_karier)
                                <span>Bidang: {{ $lowongan->bidang_karier }}</span>
                            @endif

                            @if ($lowongan->lokasi)
                                <span>
                                    @if ($lowongan->bidang_karier) &nbsp;•&nbsp; @endif
                                    Lokasi: {{ $lowongan->lokasi }}
                                </span>
                            @endif

                            @if ($lowongan->tanggal_tutup)
                                <span>
                                    &nbsp;•&nbsp;
                                    Batas Lamaran:
                                    {{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->translatedFormat('d F Y') }}
                                </span>
                            @endif

                        </div>

                    </div>

                </article>

            @empty

                <div class="sp-loker-empty">
                    <h2>Belum Ada Lowongan Kerja</h2>
                    <p>
                        Saat ini belum ada lowongan yang dipublikasikan.
                        Silakan cek kembali nanti.
                    </p>
                </div>

            @endforelse

        </section>

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
