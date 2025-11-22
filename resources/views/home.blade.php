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
                        <p class="service-card__description">Dokumentasi kegiatan terkini.</p>
                        <a href="#home-gallery" class="service-card__link">Lihat Foto →</a>
                    </div>
                    <div class="service-card">
                        <div class="service-card__icon"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#A50104" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg></div>
                        <h3 class="service-card__title">Pengaduan</h3>
                        <p class="service-card__description">Sampaikan aspirasi Anda.</p>
                        <a href="{{ url('/pengaduan') }}" class="service-card__link">Buat Pengaduan →</a>
                    </div>
                    <div class="service-card">
                        <div class="service-card__icon"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none" stroke="#A50104" stroke-width="2"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg></div>
                        <h3 class="service-card__title">Proposal</h3>
                        <p class="service-card__description">Ajukan proposal kegiatan.</p>
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
        /* Style Tombol Navigasi Hero */
        .custom-nav {
            position: absolute;
            top: 50%; transform: translateY(-50%);
            width: 40px; height: 40px;
            background: rgba(0, 0, 0, 0.3);
            border: none; border-radius: 50%;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: background 0.3s; z-index: 10; color: white;
            font-size: 18px;
        }
        .custom-nav:hover { background: #A50104; }
        .prev-btn { left: 20px; }
        .next-btn { right: 20px; }

        /* CSS Lightbox Home */
        .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.95); z-index: 9999; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s; }
        .lightbox.active { display: flex; opacity: 1; }
        .lightbox__close { position: absolute; top: 20px; right: 30px; color: white; font-size: 40px; background: none; border: none; cursor: pointer; z-index: 10001; }
        .lightbox__nav { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.1); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; transition: 0.3s; }
        .lightbox__nav:hover { background: #A50104; }
        .lightbox__dot { width: 8px; height: 8px; background: rgba(255,255,255,0.3); border-radius: 50%; margin: 0 4px; cursor: pointer; display: inline-block; }
        .lightbox__dot.active { background: #A50104; transform: scale(1.2); }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- 1. LOGIC HERO CAROUSEL ---
            const heroSlides = document.querySelectorAll('.hero-slide');
            const heroPrev = document.getElementById('heroPrev');
            const heroNext = document.getElementById('heroNext');

            if(heroSlides.length > 0) {
                let heroIndex = 0;

                function showHero(index) {
                    heroSlides.forEach(s => s.classList.remove('active'));
                    // Update dots juga kalau ada
                    const dots = document.querySelectorAll('.carousel-dots .dot');
                    dots.forEach(d => d.classList.remove('active'));

                    heroSlides[index].classList.add('active');
                    if(dots[index]) dots[index].classList.add('active');
                }

                function nextHero() {
                    heroIndex = (heroIndex + 1) % heroSlides.length;
                    showHero(heroIndex);
                }

                function prevHero() {
                    heroIndex = (heroIndex - 1 + heroSlides.length) % heroSlides.length;
                    showHero(heroIndex);
                }

                // Tombol
                if(heroNext) heroNext.onclick = nextHero;
                if(heroPrev) heroPrev.onclick = prevHero;

                // Auto Play
                setInterval(nextHero, 4000);
            }

            // --- 2. LOGIC LIGHTBOX GALERI ---
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

            // Expose function to global scope for onclick
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

                if (currentImages.length > 1) {
                    lbPrev.style.display = 'flex';
                    lbNext.style.display = 'flex';
                    lbDots.innerHTML = currentImages.map((_, idx) =>
                        `<span class="lightbox__dot ${idx === currentIndex ? 'active' : ''}" onclick="window.goToImage(${idx})"></span>`
                    ).join('');
                } else {
                    lbPrev.style.display = 'none';
                    lbNext.style.display = 'none';
                    lbDots.innerHTML = '';
                }
            }

            window.goToImage = function(idx) {
                currentIndex = idx;
                updateLightbox();
            };

            lbClose.onclick = () => {
                lightbox.classList.remove('active');
                document.body.style.overflow = 'auto';
            };

            lbNext.onclick = () => {
                currentIndex = (currentIndex + 1) % currentImages.length;
                updateLightbox();
            };

            lbPrev.onclick = () => {
                currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
                updateLightbox();
            };
        });
    </script>
@endsection
