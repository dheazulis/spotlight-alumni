<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Spotlight Alumni FST | Beranda</title>
    <link rel="stylesheet" href="{{ asset('css/spotlight-public-home.css') }}">
</head>
<body class="sp-home">
    <header class="sp-header">
        <a class="sp-brand" href="{{ route('home') }}" aria-label="Spotlight Alumni FST - Beranda">
            <img class="sp-brand-logo" src="{{ asset('images/logo-fst.png') }}" alt="Logo Fakultas Sains dan Teknologi">
            <span class="sp-brand-copy"><strong>SPOTLIGHT ALUMNI</strong><small>Fakultas Sains dan Teknologi</small></span>
        </a>
        <button class="sp-menu-toggle" type="button" aria-label="Buka navigasi" aria-expanded="false" onclick="document.querySelector('.sp-nav').classList.toggle('is-open'); this.setAttribute('aria-expanded', document.querySelector('.sp-nav').classList.contains('is-open'))">☰</button>
        <nav class="sp-nav" aria-label="Navigasi utama">
            <a class="is-active" href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('public.alumni-directory.index') }}">Direktori Alumni</a>
            <a href="{{ route('agenda.index') }}">Newsletter</a>
            <a href="{{ route('public.job-vacancies.index') }}">Info Loker</a>
            <a class="sp-login" href="{{ route('login') }}">Masuk</a>
        </nav>
    </header>

    <main>
        <section class="sp-hero">
            <div class="sp-hero-content">
                <span class="sp-kicker">SPOTLIGHT ALUMNI FST</span>
                <h1>Shining Beyond Campus</h1>
                <p>Kenali perjalanan, cerita, dan pencapaian alumni FST yang telah melangkah lebih jauh.</p>
                <div class="sp-hero-actions">
                    <a class="sp-button sp-button-light" href="{{ route('public.alumni-directory.index') }}">Jelajahi Direktori <span aria-hidden="true">→</span></a>
                    <a class="sp-hero-link" href="{{ route('public.job-vacancies.index') }}">Lihat peluang karier</a>
                </div>
            </div>
            <div class="sp-hero-decoration" aria-hidden="true"><span></span><span></span><span></span></div>
        </section>

        @if(isset($alumniInspiratif) && $alumniInspiratif->isNotEmpty())
            <section class="sp-section" id="alumni-inspiratif">
                <div class="sp-section-heading">
                    <div><span class="sp-eyebrow"></span><h2>Alumni Spotlight</h2><p>Kenalan dengan alumni inspiratif yang telah memberikan dampak nyata.</p></div>
                </div>
                <div class="sp-alumni-grid">
                    @foreach($alumniInspiratif as $item)
                        <article class="sp-alumni-tile">
                            <div class="sp-alumni-tile-photo">
                                <img src="{{ asset('storage/' . $item->alumni->foto_profil) }}" alt="Foto {{ $item->alumni->name }}" loading="lazy">
                            </div>
                            <div class="sp-alumni-tile-body">
                                <h3>{{ $item->alumni->name }}</h3>
                                <p class="sp-alumni-tile-meta">{{ $item->alumni->program_studi }} <span>·</span> Lulus {{ $item->alumni->tahun_lulus }}</p>
                                <p class="sp-alumni-tile-status">{{ $item->alumni->status_setelah_lulus }}</p>
                                <blockquote>{{ $item->alumni->pesan_motivasi }}</blockquote>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="sp-section sp-directory-section">
            <div class="sp-directory-panel">
                <div><span class="sp-eyebrow">Direktori Alumni</span><h2>Kenali Alumni FST</h2><p>Temukan profil dan perjalanan alumni Fakultas Sains dan Teknologi.</p></div>
                <a class="sp-button sp-button-green" href="{{ route('public.alumni-directory.index') }}">Buka Direktori <span aria-hidden="true">→</span></a>
            </div>
        </section>

        <section class="sp-section" id="newsletter">
            <div class="sp-section-heading sp-heading-row">
                <div><span class="sp-eyebrow">Informasi Alumni</span><h2>Newsletter</h2><p>Informasi dan kabar terbaru dari alumni Fakultas Sains dan Teknologi.</p></div>
                <a class="sp-text-link" href="{{ route('agenda.index') }}">Lihat semua informasi <span aria-hidden="true">→</span></a>
            </div>
            @if(isset($agendas) && $agendas->isNotEmpty())
                <div class="sp-agenda-grid">
                    @foreach($agendas as $agenda)
                    <a href="{{ route('agenda.show', $agenda->id) }}"class="text-decoration-none text-reset"></a>
                        <article class="sp-agenda-card">
                            @if($agenda->gambar)
                                <div class="sp-agenda-image-wrap"><img src="{{ asset('storage/' . $agenda->gambar) }}" alt="{{ $agenda->judul }}" class="sp-agenda-image" loading="lazy"></div>
                            @else
                                <div class="sp-agenda-placeholder"><img src="{{ asset('images/logo-fst.png') }}" alt="Logo FST"><small>Newsletter FST</small></div>
                            @endif
                            <div class="sp-agenda-content">
                                <span class="sp-agenda-date">{{ $agenda->tanggal_mulai->translatedFormat('d F Y') }}</span>
                                <h3>{{ $agenda->judul }}</h3>
                                @if($agenda->lokasi)<p class="sp-agenda-location">⌖ {{ $agenda->lokasi }}</p>@endif
                                <p class="sp-agenda-description">{{ \Illuminate\Support\Str::limit(strip_tags($agenda->deskripsi), 125) }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="sp-empty"><span>▤</span><h3>Belum ada informasi terbaru</h3><p>Informasi newsletter dan agenda yang dipublikasikan akan tampil di bagian ini.</p></div>
            @endif
        </section>

    </main>

    <footer class="sp-footer">
        <a class="sp-brand sp-footer-brand" href="{{ route('home') }}"><img class="sp-brand-logo" src="{{ asset('images/logo-fst.png') }}" alt="Logo Fakultas Sains dan Teknologi"><span class="sp-brand-copy"><strong>SPOTLIGHT ALUMNI</strong><small>Fakultas Sains dan Teknologi</small></span></a>
        <p>Portal informasi, jejaring, dan pengembangan alumni Fakultas Sains dan Teknologi.</p>
        <small>© {{ date('Y') }} Spotlight Alumni FST. Seluruh hak cipta dilindungi.</small>
    </footer>
</body>
</html>
