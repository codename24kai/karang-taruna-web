@extends('layouts.app')

@section('title', 'Beranda - Karang Taruna')

@section('content')
    <section class="section section--hero" id="beranda">
        <div class="container">
            <div class="hero">
                <div class="hero__content">
                    <h1 class="hero__title">Selamat Datang di Karang Taruna Sub-unit 006/013</h1>
                    <p class="hero__subtitle">Melayani masyarakat dengan integritas, transparansi, dan profesionalisme</p>
                    <div class="hero__actions">
                        <a href="{{ url('/pengaduan') }}" class="btn btn--primary">Laporkan Pengaduan</a>
                        <a href="{{ url('/informasi') }}" class="btn btn--secondary">Lihat Informasi</a>
                    </div>
                </div>

                <div class="hero__image">
                    <div class="hero-carousel">
                        @forelse($hero_galleries as $key => $item)
                            <div class="hero-slide {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ asset($item->image) }}" alt="{{ $item->caption }}" class="slide-img">

                                @if($item->caption)
                                    <div class="hero-caption">
                                        <span>{{ Str::limit($item->caption, 50) }}</span>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="hero-slide active">
                                <img src="https://images.unsplash.com/photo-1529070538774-1843cb6e65b3?q=80&w=1000" alt="Default Banner" class="slide-img">
                            </div>
                        @endforelse

                        @if($hero_galleries->count() > 1)
                            <button class="custom-nav prev-btn" id="heroPrev" aria-label="Previous Slide">&#10094;</button>
                            <button class="custom-nav next-btn" id="heroNext" aria-label="Next Slide">&#10095;</button>

                            <div class="carousel-dots">
                                @foreach($hero_galleries as $key => $item)
                                    <span class="dot {{ $key == 0 ? 'active' : '' }}" onclick="manualSlide({{ $key }})"></span>
                                @endforeach
                            </div>
                        @endif
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
                        <div class="service-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#A50104" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 11-1.296-1.296a2.4 2.4 0 0 0-3.408 0L11 16"/><path d="M4 8a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2"/><circle cx="13" cy="7" r="1" fill="currentColor"/><rect x="8" y="2" width="14" height="14" rx="2"/></svg>
                        </div>
                        <h3 class="service-card__title">Galeri Kegiatan</h3>
                        <p class="service-card__description">Lihat dokumentasi foto dari berbagai kegiatan dan program yang telah kami laksanakan.</p>
                        <a href="#home-gallery" class="service-card__link">Lihat Foto →</a>
                    </div>
                    <div class="service-card">
                        <div class="service-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#A50104" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                        </div>
                        <h3 class="service-card__title">Pengaduan Masyarakat</h3>
                        <p class="service-card__description">Sampaikan keluhan dan aspirasi Anda dengan mudah dan terpantau secara real-time.</p>
                        <a href="{{ url('/pengaduan') }}" class="service-card__link">Buat Pengaduan →</a>
                    </div>
                    <div class="service-card">
                        <div class="service-card__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#A50104" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                        </div>
                        <h3 class="service-card__title">Pengajuan Proposal</h3>
                        <p class="service-card__description">Ajukan proposal kegiatan atau program dengan proses yang transparan dan terstruktur.</p>
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

            <div class="gallery-grid" id="homeGalleryGrid">
                @forelse($home_galleries as $key => $item)
                    @php
                        $images = json_decode($item->images);
                        $thumb = !empty($images) ? $images[0] : null;
                        $count = is_array($images) ? count($images) : 0;
                    @endphp

                    <div class="gallery-item" onclick="openHomeLightbox({{ $key }})" role="button" tabindex="0">
                        <div class="gallery-thumb-wrapper">
                            @if($thumb)
                                <img src="{{ asset($thumb) }}" alt="{{ $item->caption }}" class="gallery-thumb-img">
                            @else
                                <div class="gallery-no-image">No Image</div>
                            @endif

                            @if($count > 1)
                                <div class="gallery-count-badge">📷 {{ $count }} Foto</div>
                            @endif
                        </div>
                        <div class="gallery-item__caption">
                            <strong>{{ $item->caption }}</strong>
                            <small>{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}</small>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">
                        Belum ada kegiatan yang diupload.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section section--gray" id="informasi-singkat">
        <div class="container">
            <div class="section__header">
                <h2 class="section__title" style="text-align: left; margin-bottom: 0;">Informasi Terbaru</h2>
                <a href="{{ url('/informasi') }}" class="btn btn--secondary btn--sm">Lihat Semua Informasi</a>
            </div>

            <div class="articles-grid">
                @forelse($latest_articles as $article)
                    <article class="article-card clickable" onclick="window.location.href='{{ url('/informasi') }}'">
                        <div class="article-card__image" style="background-image: url('{{ $article->image ? asset($article->image) : 'https://via.placeholder.com/400x200?text=No+Image' }}');"></div>

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
        <div class="lightbox__content">
            <div class="lightbox__image-container">
                <button class="lightbox__nav lightbox__prev" id="lbPrev">&#10094;</button>
                <img src="" id="lbImg" class="lightbox__main-img" alt="Preview">
                <button class="lightbox__nav lightbox__next" id="lbNext">&#10095;</button>
            </div>
            <div id="lbCaption" class="lightbox__caption"></div>
            <div class="lightbox__dots" id="lbDots"></div>
        </div>
    </div>

    <style>
        /* --- HERO CAROUSEL FIX --- */
        .hero__image {
            width: 100%;
            height: 400px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
            background-color: #000; /* Background hitam mencegah kedip putih */
        }

        .hero-carousel {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .hero-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            z-index: 1;
            transition: opacity 0.8s ease-in-out; /* Transisi mulus */
        }

        .hero-slide.active {
            opacity: 1;
            z-index: 2;
        }

        .slide-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Caption Slide */
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
            transition: all 0.5s ease 0.3s;
        }

        .hero-slide.active .hero-caption {
            transform: translateY(0);
            opacity: 1;
        }

        /* Navigasi Bulat */
        .custom-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            background-color: rgba(0, 0, 0, 0.3);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
            z-index: 10;
            color: white;
            font-size: 20px;
        }

        .custom-nav:hover {
            background-color: rgba(165, 1, 4, 0.8);
        }

        .prev-btn { left: 20px; }
        .next-btn { right: 20px; }

        /* Dots */
        .carousel-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .dot {
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
        }

        .dot.active {
            background: #FCBA04;
            width: 30px;
            border-radius: 10px;
        }

        /* --- GALERI GRID STYLES --- */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .gallery-item {
            cursor: pointer;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            border: 1px solid #eee;
        }
        .gallery-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        .gallery-thumb-wrapper {
            position: relative;
            height: 220px;
            overflow: hidden;
        }
        .gallery-thumb-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .gallery-item:hover .gallery-thumb-img {
            transform: scale(1.05);
        }
        .gallery-no-image {
            width: 100%;
            height: 100%;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
        }
        .gallery-count-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.6);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            backdrop-filter: blur(2px);
        }
        .gallery-item__caption {
            padding: 15px;
        }
        .gallery-item__caption strong {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }
        .gallery-item__caption small {
            color: #666;
        }

        /* --- LIGHTBOX STYLES --- */
        .lightbox {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.95);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .lightbox.active {
            display: flex;
            opacity: 1;
        }
        .lightbox__content {
            display: flex;
            flex-direction: column;
            max-width: 640px; /* Max 640px sesuai request */
            width: 95%;
            position: relative;
        }
        .lightbox__image-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .lightbox__main-img {
            max-width: 100%;
            max-height: 480px; /* Max Height 480px */
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .lightbox__nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.1);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: 0.3s;
            font-size: 20px;
        }
        .lightbox__nav:hover { background: #A50104; }
        .lightbox__prev { left: -50px; }
        .lightbox__next { right: -50px; }

        .lightbox__caption {
            color: white;
            margin-top: 15px;
            text-align: center;
            font-size: 16px;
            font-weight: 500;
        }
        .lightbox__dots {
            margin-top: 10px;
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        .lightbox__dot {
            width: 8px;
            height: 8px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            cursor: pointer;
            display: inline-block;
        }
        .lightbox__dot.active {
            background: #A50104;
            transform: scale(1.2);
        }
        .lightbox__close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 40px;
            background: none;
            border: none;
            cursor: pointer;
            z-index: 10001;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .hero__image { height: 250px; }
            .custom-nav { width: 40px; height: 40px; font-size: 16px; }
            .prev-btn { left: 10px; }
            .next-btn { right: 10px; }
            .gallery-grid { grid-template-columns: 1fr; }
            .lightbox__nav { width: 35px; height: 35px; font-size: 16px; }
            .lightbox__prev { left: -10px; } /* Geser tombol nav ke dalam layar di HP */
            .lightbox__next { right: -10px; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. HERO CAROUSEL LOGIC
            const heroSlides = document.querySelectorAll('.hero-slide');
            const heroPrev = document.getElementById('heroPrev');
            const heroNext = document.getElementById('heroNext');

            if(heroSlides.length > 0) {
                let heroIndex = 0;
                let heroInterval;

                function showHero(index) {
                    // Reset class active
                    heroSlides.forEach(s => s.classList.remove('active'));
                    const dots = document.querySelectorAll('.carousel-dots .dot');
                    dots.forEach(d => d.classList.remove('active'));

                    // Set active
                    heroSlides[index].classList.add('active');
                    if(dots[index]) dots[index].classList.add('active');

                    heroIndex = index;
                }

                function nextHero() {
                    const nextIndex = (heroIndex + 1) % heroSlides.length;
                    showHero(nextIndex);
                }

                function prevHero() {
                    const prevIndex = (heroIndex - 1 + heroSlides.length) % heroSlides.length;
                    showHero(prevIndex);
                }

                function startHeroAuto() {
                    clearInterval(heroInterval);
                    heroInterval = setInterval(nextHero, 5000); // 5 Detik
                }

                // Event Listeners Hero
                if (heroNext) heroNext.onclick = () => { nextHero(); startHeroAuto(); };
                if (heroPrev) heroPrev.onclick = () => { prevHero(); startHeroAuto(); };

                // Expose manualSlide ke global scope untuk onclick dot
                window.manualSlide = function(index) {
                    showHero(index);
                    startHeroAuto();
                }

                startHeroAuto();
            }

            // 2. LIGHTBOX GALERI LOGIC
            const galleryData = @json($home_galleries);
            let currentImages = [];
            let currentIndex = 0;
            let currentCaption = "";

            const lightbox = document.getElementById('homeLightbox');
            const lbImg = document.getElementById('lbImg');
            const lbCaption = document.getElementById('lbCaption');
            const lbPrev = document.getElementById('lbPrev');
            const lbNext = document.getElementById('lbNext');
            const lbDots = document.getElementById('lbDots');
            const lbClose = document.getElementById('lbClose');

            window.openHomeLightbox = function(index) {
                const item = galleryData[index];
                if (!item) return;

                currentImages = JSON.parse(item.images);
                currentCaption = item.caption;
                currentIndex = 0;

                if (currentImages.length > 0) {
                    updateLightbox();
                    lightbox.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            };

            function updateLightbox() {
                const baseUrl = "{{ asset('') }}";
                lbImg.src = baseUrl + currentImages[currentIndex];

                const counter = currentImages.length > 1 ? ` (${currentIndex + 1}/${currentImages.length})` : '';
                lbCaption.innerText = currentCaption + counter;

                // Logic Tombol Navigasi (Hanya jika > 1 foto)
                if (currentImages.length > 1) {
                    lbPrev.style.display = 'flex';
                    lbNext.style.display = 'flex';
                    lbDots.innerHTML = currentImages.map((_, idx) =>
                        `<span class="lightbox__dot ${idx === currentIndex ? 'active' : ''}" onclick="window.goToLbImage(${idx})"></span>`
                    ).join('');
                } else {
                    lbPrev.style.display = 'none';
                    lbNext.style.display = 'none';
                    lbDots.innerHTML = '';
                }
            }

            window.goToLbImage = function(idx) {
                currentIndex = idx;
                updateLightbox();
            };

            const closeLightbox = () => {
                lightbox.classList.remove('active');
                document.body.style.overflow = 'auto';
            };

            lbClose.onclick = closeLightbox;
            lightbox.onclick = (e) => { if (e.target === lightbox) closeLightbox(); };

            lbNext.onclick = () => {
                currentIndex = (currentIndex + 1) % currentImages.length;
                updateLightbox();
            };

            lbPrev.onclick = () => {
                currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
                updateLightbox();
            };

            // Keyboard Navigation
            document.addEventListener('keydown', (e) => {
                if (!lightbox.classList.contains('active')) return;
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowRight' && currentImages.length > 1) lbNext.click();
                if (e.key === 'ArrowLeft' && currentImages.length > 1) lbPrev.click();
            });
        });
    </script>
@endsection
