<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Alumni | Spotlight Alumni FST</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/spotlight-detail-alumni.css') }}">
</head>
<body class="sp-detail-page">
    <header class="sp-detail-header">
        <a class="sp-detail-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo-fst.png') }}" alt="Logo Fakultas Sains dan Teknologi">
            <span><strong>SPOTLIGHT ALUMNI</strong><small>FAKULTAS SAINS DAN TEKNOLOGI</small></span>
        </a>
        <button class="sp-detail-menu" type="button" aria-label="Buka navigasi" aria-expanded="false">☰</button>
        <nav class="sp-detail-nav">
            <a href="{{ route('home') }}">Beranda</a>
            <a class="is-active" href="{{ route('public.alumni-directory.index') }}">Direktori Alumni</a>
            <a href="{{ route('agenda.index') }}">Newsletter</a>
            <a href="{{ route('public.job-vacancies.index') }}">Info Loker</a>
            <a class="sp-detail-login" href="{{ route('login') }}">Login</a>
        </nav>
    </header>

    <main class="sp-detail-main">
        <a href="{{ route('public.alumni-directory.index') }}" class="sp-detail-back">← Kembali ke Direktori Alumni</a>

        <section class="sp-profile-hero">
    <div class="sp-profile-identity">
        <div class="sp-profile-photo-wrap">
            @if ($alumni->foto_profil)
                <img src="{{ asset('storage/' . $alumni->foto_profil) }}"
                     alt="Foto {{ $alumni->name }}"
                     class="sp-profile-photo">
            @else
                <div class="sp-profile-photo sp-profile-initial">
                    {{ strtoupper(substr($alumni->name, 0, 1)) }}
                </div>
            @endif
        </div>

        <div class="sp-profile-heading">
            <span class="sp-detail-eyebrow">PROFIL ALUMNI</span>
            <h1>{{ $alumni->name }}</h1>

            <p>
                {{ $alumni->program_studi ?: 'Program studi belum diisi' }}
                @if($alumni->tahun_lulus)
                    <span>•</span> Lulus {{ $alumni->tahun_lulus }}
                @endif
            </p>

            @if($alumni->status_setelah_lulus)
                <span class="sp-profile-status">
                    {{ $alumni->status_setelah_lulus }}
                </span>
            @endif
        </div>
    </div>

    <div class="sp-profile-quote">
        <div class="sp-profile-quote-heading">
            <span class="sp-detail-icon">❝</span>
            <div>
                <h2>Quotes</h2>
                <p>Pesan dan motivasi dari alumni</p>
            </div>
        </div>

        @if ($alumni->pesan_motivasi)
            <blockquote class="sp-motivation">
                {{ $alumni->pesan_motivasi }}
            </blockquote>
        @else
            <p class="sp-detail-empty">Belum ada Quotes.</p>
        @endif
    </div>
</section>

        <div class="sp-detail-grid">
            <div class="sp-detail-main-column">
                <section class="sp-detail-card">
                    <div class="sp-detail-card-heading"><span class="sp-detail-icon">⌂</span><div><h2>Informasi Alumni</h2><p>Informasi umum mengenai alumni</p></div></div>
                    <div class="sp-info-grid">
                        <div class="sp-info-item"><small>Nama Lengkap</small><strong>{{ $alumni->name ?: '-' }}</strong></div>
                        <div class="sp-info-item"><small>Program Studi</small><strong>{{ $alumni->program_studi ?: '-' }}</strong></div>
                        <div class="sp-info-item"><small>Tahun Masuk</small><strong>{{ $alumni->tahun_masuk ?: '-' }}</strong></div>
                        <div class="sp-info-item"><small>Tahun Lulus</small><strong>{{ $alumni->tahun_lulus ?: '-' }}</strong></div>
                        <div class="sp-info-item"><small>Nomor HP</small><strong>{{ $alumni->nomor_hp ?: '-' }}</strong></div>
                        <div class="sp-info-item"><small>Status Setelah Lulus</small><strong>{{ $alumni->status_setelah_lulus ?: '-' }}</strong></div>
                    </div>
                </section>

                

               
            </div>

            <aside class="sp-detail-side-column">
                <section class="sp-detail-card sp-career-card">
                    <div class="sp-detail-card-heading"><span class="sp-detail-icon">↗</span><div><h2>Riwayat Karier</h2><p>Perjalanan karier alumni</p></div></div>
                    @forelse ($alumni->careerJourneys as $karier)
                        <article class="sp-career-item">
                            <span class="sp-career-dot"></span>
                            <h3>{{ $karier->jabatan }}</h3>
                            <strong>{{ $karier->nama_perusahaan }}</strong>
                            <p>Bidang: {{ $karier->bidang_karier ?: '-' }}</p>
                            <p>Mulai: {{ $karier->tanggal_mulai ?: '-' }}</p>
                            <p>Selesai: {{ $karier->masih_bekerja ? 'Masih bekerja' : ($karier->tanggal_selesai ?: '-') }}</p>
                            @if ($karier->deskripsi)<p class="sp-career-description">{{ $karier->deskripsi }}</p>@endif
                        </article>
                    @empty
                        <p class="sp-detail-empty">Belum ada riwayat karier.</p>
                    @endforelse
                </section>
        
            </aside>
        </div>
    </main>

    <footer class="sp-detail-footer">© {{ date('Y') }} Spotlight Alumni FST · Fakultas Sains dan Teknologi</footer>
    <script>
        const menuButton = document.querySelector('.sp-detail-menu');
        const navigation = document.querySelector('.sp-detail-nav');
        menuButton?.addEventListener('click', () => {
            const opened = navigation.classList.toggle('is-open');
            menuButton.setAttribute('aria-expanded', opened ? 'true' : 'false');
        });
    </script>
</body>
</html>
