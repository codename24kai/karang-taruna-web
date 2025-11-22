@extends('layouts.app')

@section('title', 'Beranda - Karang Taruna')

@section('content')
    <section class="section section--hero" id="beranda">
        <div class="container">
            <div class="hero">
                <div class="hero__content">
                    <h1 class="hero__title">Selamat Datang di Website Karang Taruna Sub-unit 006/013</h1>
                    <p class="hero__subtitle">Melayani masyarakat dengan integritas, transparansi, dan profesionalisme</p>
                    <div class="hero__actions">
                        <a href="{{ url('/pengaduan') }}" class="btn btn--primary">Laporkan Pengaduan</a>
                        <a href="{{ url('/informasi') }}" class="btn btn--secondary">Lihat Informasi</a>
                    </div>
                </div>

                <div class="hero__image">
                    <div class="hero-carousel">
                        <img src="{{ asset('assets/img/photo-2.jpeg') }}" alt="Kegiatan 1" class="hero-slide active" style="object-fit: cover;">
                        <img src="{{ asset('assets/img/photo-3.jpeg') }}" alt="Kegiatan 2" class="hero-slide" style="object-fit: cover;">
                        <img src="{{ asset('assets/img/photo-4.jpeg') }}" alt="Kegiatan 3" class="hero-slide" style="object-fit: cover;">
                        <img src="{{ asset('assets/img/photo-7.jpeg') }}" alt="Kegiatan 4" class="hero-slide" style="object-fit: cover;">

                        <button class="custom-nav prev-btn" id="heroPrev">&#10094;</button>
                        <button class="custom-nav next-btn" id="heroNext">&#10095;</button>

                        <div class="carousel-dots">
                            <span class="dot active"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                            <span class="dot"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stats">
                <div class="stats__item">
                    <div class="stats__number">{{ $stats['pengaduan'] }}</div>
                    <div class="stats__label">Pengaduan Selesai</div>
                </div>
                <div class="stats__item">
                    <div class="stats__number">{{ $stats['kegiatan'] }}</div>
                    <div class="stats__label">Jumlah Kegiatan</div>
                </div>
                <div class="stats__item">
                    <div class="stats__number">{{ $stats['proposal'] }}</div>
                    <div class="stats__label">Proposal Disetujui</div>
                </div>
            </div>

            <div class="services">
                <h2 class="section__title">Layanan Kami</h2>
                <div class="services__grid">
                    <div class="service-card">
                        <div class="service-card__icon"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#A50104" stroke-width="2"><path d="m22 11-1.296-1.296a2.4 2.4 0 0 0-3.408 0L11 16"/><path d="M4 8a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2"/><circle cx="13" cy="7" r="1" fill="currentColor"/><rect x="8" y="2" width="14" height="14" rx="2"/></svg></div>
                        <h3 class="service-card__title">Galeri Kegiatan</h3>
                        <p class="service-card__description">Kumpulan dokumentasi visual dari setiap agenda yang telah terlaksana.</p>
                        <a href="#home-gallery" class="service-card__link">Lihat Foto →</a>
                    </div>
                    <div class="service-card">
                        <div class="service-card__icon"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#A50104" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg></div>
                        <h3 class="service-card__title">Pengaduan</h3>
                        <p class="service-card__description">Layanan Aspirasi. Sampaikan masukan secara langsung</p>
                        <a href="{{ url('/pengaduan') }}" class="service-card__link">Buat Pengaduan →</a>
                    </div>
                    <div class="service-card">
                        <div class="service-card__icon"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#A50104" stroke-width="2"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg></div>
                        <h3 class="service-card__title">Proposal</h3>
                        <p class="service-card__description">Pengajuan Event. Kirim rancangan kegiatanmu di sini.</p>
                        <a href="{{ url('/pengajuan') }}" class="service-card__link">Ajukan Proposal →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="home-gallery" style="background: #f9fafb;">
        <div class="container">
            <div class="section__header">
                <h2 class="section__title" style="text-align: left; margin-bottom: 0;">Galeri Kegiatan</h2>
                <a href="{{ url('/galeri') }}" class="btn btn--secondary btn--sm">Lihat Semua Foto</a>
            </div>

            <div class="gallery-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                @forelse($home_galleries as $key => $item)
                    @php
                        $images = json_decode($item->images);
                        $thumb = !empty($images) ? $images[0] : null;
                        $count = is_array($images) ? count($images) : 0;
                    @endphp
                    <div class="gallery-item" onclick="openHomeLightbox({{ $key }})" style="cursor: pointer; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); transition: transform 0.2s;">
                        <div style="position:relative; height: 220px; overflow: hidden;">
                            @if($thumb)
                                <img src="{{ asset($thumb) }}" alt="{{ $item->caption }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                            @else
                                <div style="width:100%; height:100%; background:#eee; display:flex; align-items:center; justify-content:center; color:#888;">No Image</div>
                            @endif
                            @if($count > 1)
                                <div style="position:absolute; top:10px; right:10px; background:rgba(0,0,0,0.6); color:white; padding:4px 8px; border-radius:4px; font-size:12px;">📷 {{ $count }} Foto</div>
                            @endif
                        </div>
                        <div style="padding: 15px;">
                            <strong style="display:block; font-size:16px; margin-bottom:5px; color:#333;">{{ $item->caption }}</strong>
                            <small style="color:#666;">{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}</small>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 40px;"><p style="color: #666;">Belum ada kegiatan.</p></div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section section--gray">
        <div class="container">
            <div class="section__header">
                <h2 class="section__title" style="text-align: left; margin-bottom: 0;">Informasi Terbaru</h2>
                <a href="{{ url('/informasi') }}" class="btn btn--secondary btn--sm">Lihat Semua Informasi</a>
            </div>
            <div class="articles-grid">
                @forelse($latest_articles as $article)
                    <article class="article-card clickable" onclick="window.location.href='{{ url('/informasi') }}'">
                        <div class="article-card__image" style="background-image: url('{{ $article->image ? asset($article->image) : 'https://via.placeholder.com/400x200' }}'); background-size: cover;"></div>
                        <div class="article-card__content">
                            <span class="article-card__category">{{ $article->category }}</span>
                            <h3 class="article-card__title">{{ $article->title }}</h3>
                            <p class="article-card__excerpt">{{ Str::limit($article->excerpt, 80) }}</p>
                            <time class="article-card__date">{{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}</time>
                        </div>
                    </article>
                @empty
                    <p style="grid-column: 1/-1; text-align: center; color: #666;">Belum ada informasi terbaru.</p>
                @endforelse
            </div>
        </div>
    </section>

    <div id="homeLightbox" class="lightbox">
        <button class="lightbox__close" id="lbClose">&times;</button>
        <div class="lightbox__content" style="flex-direction: column; max-width: 640px; width: 100%;">
            <div style="position: relative; width: 100%; display: flex; justify-content: center; align-items: center;">
                <button class="lightbox__nav lightbox__prev" id="lbPrev" style="left: -50px;">&#10094;</button>
                <img src="" id="lbImg" style="max-width: 100%; max-height: 480px; object-fit: contain; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                <button class="lightbox__nav lightbox__next" id="lbNext" style="right: -50px;">&#10095;</button>
            </div>
            <div id="lbCaption" style="color: white; margin-top: 15px; text-align: center; font-size: 16px;"></div>
            <div class="lightbox__dots" id="lbDots" style="margin-top: 10px;"></div>
        </div>
    </div>

    <style>
    /* === FIX CSS CAROUSEL ANTI-DUPLIKAT === */
    .hero__image {
        width: 100%;
        height: 400px; /* Tinggi Fix */
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        position: relative;
    }

    .hero-carousel {
        width: 100%;
        height: 100%;
        position: relative;
    }

    /* SLIDE ITEM */
    .hero-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0; /* Default sembunyi */
        z-index: 1;
        transition: opacity 0.8s ease-in-out; /* Transisi lebih cepat dikit biar tegas */
    }

    /* SLIDE AKTIF */
    .hero-slide.active {
        opacity: 1;
        z-index: 2;
    }

    /* Caption di atas gambar */
    .hero-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 40px 20px 20px;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
        font-weight: 600;
        font-size: 18px;
        text-align: center;
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.5s ease 0.3s; /* Delay dikit biar muncul belakangan */
    }

    .hero-slide.active .hero-caption {
        transform: translateY(0);
        opacity: 1;
    }

    /* Navigasi */
    .custom-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        background: rgba(0,0,0,0.3);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: 0.3s;
    }
    .custom-nav:hover { background: #A50104; }
    .prev-btn { left: 20px; }
    .next-btn { right: 20px; }

    /* Dots */
    .carousel-dots {
        position: absolute;
        bottom: 15px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 10;
    }
    .dot {
        width: 10px;
        height: 10px;
        background: rgba(255,255,255,0.5);
        border-radius: 50%;
        cursor: pointer;
        transition: 0.3s;
    }
    .dot.active {
        background: #FCBA04;
        width: 30px;
        border-radius: 10px;
    }

    /* RESPONSIVE HP */
    @media (max-width: 768px) {
        .hero__image { height: 250px; margin-top: 20px; }
        .hero-caption { font-size: 14px; padding: 30px 15px 15px; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.carousel-dots .dot');
        const prevBtn = document.getElementById('heroPrev');
        const nextBtn = document.getElementById('heroNext');

        if (slides.length <= 1) {
            // Kalau cuma 1 slide, sembunyikan navigasi
            if(prevBtn) prevBtn.style.display = 'none';
            if(nextBtn) nextBtn.style.display = 'none';
            return;
        }

        let currentSlide = 0;
        let slideInterval;

        // Fungsi Ganti Slide Utama
        function goToSlide(index) {
            // Hilangkan active dari slide sekarang
            slides[currentSlide].classList.remove('active');
            if(dots[currentSlide]) dots[currentSlide].classList.remove('active');

            // Update index (Looping)
            currentSlide = (index + slides.length) % slides.length;

            // Tambah active ke slide baru
            slides[currentSlide].classList.add('active');
            if(dots[currentSlide]) dots[currentSlide].classList.add('active');
        }

        function nextSlide() {
            goToSlide(currentSlide + 1);
        }

        function prevSlide() {
            goToSlide(currentSlide - 1);
        }

        // Auto Play
        function startAuto() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 4000);
        }

        // Tombol Next
        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                nextSlide();
                startAuto(); // Reset timer biar gak bentrok
            });
        }

        // Tombol Prev
        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                prevSlide();
                startAuto();
            });
        }

        // Klik Dot Manual (Fungsi global)
        window.manualSlide = function(index) {
            goToSlide(index);
            startAuto();
        }

        // Jalankan
        startAuto();
    });
</script>
@endsection
