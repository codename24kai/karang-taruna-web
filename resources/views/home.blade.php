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
                                    <div class="hero-caption"><span>{{ Str::limit($item->caption, 50) }}</span></div>
                                @endif
                            </div>
                        @empty
                            <div class="hero-slide active">
                                <img src="https://images.unsplash.com/photo-1529070538774-1843cb6e65b3?q=80&w=1000" class="slide-img">
                            </div>
                        @endforelse

                        @if($hero_galleries->count() > 1)
                            <button class="custom-nav prev-btn" id="heroPrev">&#10094;</button>
                            <button class="custom-nav next-btn" id="heroNext">&#10095;</button>
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

            <div class="gallery-grid">
                @forelse($home_galleries as $key => $item)
                    @php
                        $images = json_decode($item->images);
                        $thumb = !empty($images) ? $images[0] : null;
                        $count = is_array($images) ? count($images) : 0;
                    @endphp
                    <div class="gallery-item" onclick="openHomeLightbox({{ $key }})" style="cursor: pointer;">
                        <div class="gallery-thumb-wrapper" style="position: relative; height: 220px; overflow: hidden; border-radius: 12px;">
                            @if($thumb)
                                <img src="{{ asset($thumb) }}" alt="{{ $item->caption }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                            @else
                                <div style="width:100%; height:100%; background:#eee; display:flex; align-items:center; justify-content:center; color:#888;">No Image</div>
                            @endif
                            @if($count > 1)
                                <div style="position:absolute; top:10px; right:10px; background:rgba(0,0,0,0.6); color:white; padding:4px 8px; border-radius:4px; font-size:12px;">📷 {{ $count }} Foto</div>
                            @endif
                        </div>
                        <div style="padding: 15px; background: var(--color-white);">
                            <strong style="display:block; font-size:16px; margin-bottom:5px;">{{ $item->caption }}</strong>
                            <small style="color: var(--color-gray-500);">{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}</small>
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
                    <article class="article-card clickable" onclick='openArticleModal(@json($article))'>
                        <div class="article-card__image" style="position: relative; overflow: hidden;">
                            @if($article->image)
                                <img src="{{ asset($article->image) }}" alt="{{ $article->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width:100%; height:100%; background: linear-gradient(135deg, #A50104, #FCBA04); display:flex; align-items:center; justify-content:center; color:white; font-weight:bold;">
                                    {{ $article->category }}
                                </div>
                            @endif
                        </div>

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
        <div class="lightbox__content" style="flex-direction: column; max-width: 640px; width: 95%;">
            <div style="position: relative; width: 100%; display: flex; justify-content: center; align-items: center;">
                <button class="lightbox__nav lightbox__prev" id="lbPrev" style="left: -50px;">&#10094;</button>
                <img src="" id="lbImg" style="max-width: 100%; max-height: 480px; object-fit: contain; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                <button class="lightbox__nav lightbox__next" id="lbNext" style="right: -50px;">&#10095;</9>
            </div>
            <div id="lbCaption" style="color: white; margin-top: 15px; text-align: center; font-size: 16px;"></div>
            <div class="lightbox__dots" id="lbDots" style="margin-top: 10px; display: flex; gap: 8px; justify-content: center;"></div>
        </div>
    </div>

    <div class="modal-overlay" id="articleModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 10000; justify-content: center; align-items: center; padding: 20px;">
        <div class="modal-box" style="background: white; width: 100%; max-width: 800px; border-radius: 12px; max-height: 90vh; display: flex; flex-direction: column; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative;">

            <div style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <h3 style="margin: 0; font-size: 18px; color: #333;">Detail Informasi</h3>
                <button onclick="closeArticleModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
            </div>

            <div style="padding: 20px; overflow-y: auto; flex-grow: 1;">
                <div style="width: 100%; margin-bottom: 20px; border-radius: 8px; overflow: hidden; background: #f3f4f6; display: flex; justify-content: center; align-items: center;">
                    <img id="modalImg" src="" style="width: 100%; height: auto; max-height: 600px; object-fit: contain; display: none;">
                    <div id="modalPlaceholder" style="width: 100%; height: 200px; background: linear-gradient(135deg, #A50104, #FCBA04); display: none; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold;"></div>
                </div>

                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <span id="modalCategory" style="background: #3b82f6; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase;">ARTIKEL</span>
                    <span id="modalDate" style="color: #666; font-size: 13px;">-</span>
                </div>

                <h2 id="modalTitle" style="margin: 0 0 15px 0; font-size: 24px; color: #111; line-height: 1.3;"></h2>
                <div id="modalContent" style="color: #4b5563; line-height: 1.7; font-size: 16px; white-space: pre-line;"></div>

                <div id="modalLinkContainer" style="margin-top: 30px; display: none;">
                    <a id="modalLink" href="#" target="_blank" class="btn btn--primary" style="display: inline-block; text-decoration: none;">Baca Selengkapnya / Daftar →</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* CSS Lokal untuk Hero & Lightbox Home */
        .hero__image { width: 100%; height: 400px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); position: relative; overflow: hidden; background-color: #000; }
        .hero-carousel { width: 100%; height: 100%; position: relative; }
        .hero-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; z-index: 1; transition: opacity 0.8s ease-in-out; }
        .hero-slide.active { opacity: 1; z-index: 2; }
        .slide-img { width: 100%; height: 100%; object-fit: cover; }
        .hero-caption { position: absolute; bottom: 0; left: 0; width: 100%; padding: 40px 20px 20px; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); color: white; font-weight: 600; font-size: 18px; text-align: center; transform: translateY(20px); opacity: 0; transition: all 0.5s ease 0.3s; }
        .hero-slide.active .hero-caption { transform: translateY(0); opacity: 1; }
        .hero-caption span{position: relative; top: -15px}
        .custom-nav { position: absolute; top: 50%; transform: translateY(-50%); width: 50px; height: 50px; background-color: rgba(0, 0, 0, 0.3); border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background-color 0.3s; z-index: 10; color: white; font-size: 20px; }
        .custom-nav:hover { background-color: rgba(165, 1, 4, 0.8); }
        .prev-btn { left: 20px; }
        .next-btn { right: 20px; }
        .carousel-dots { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; z-index: 10; }
        .dot { width: 10px; height: 10px; background: rgba(255, 255, 255, 0.5); border-radius: 50%; cursor: pointer; transition: all 0.3s; }
        .dot.active { background: #FCBA04; width: 30px; border-radius: 10px; }

        @media (max-width: 768px) {
            .hero__image { height: 250px; }
            .custom-nav { width: 40px; height: 40px; font-size: 16px; }
            .prev-btn { left: 10px; }
            .next-btn { right: 10px; }
            .lightbox__prev { left: -10px; }
            .lightbox__next { right: -10px; }
        }
    </style>

    <script>
            // === 2. LIGHTBOX GALERI ===
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

                if (currentImages.length > 1) {
                    lbPrev.style.display = 'flex'; lbNext.style.display = 'flex';
                    lbDots.innerHTML = currentImages.map((_, idx) => `<span class="lightbox__dot ${idx === currentIndex ? 'active' : ''}" style="width:8px; height:8px; background:${idx === currentIndex ? '#A50104' : 'rgba(255,255,255,0.5)'}; border-radius:50%; cursor:pointer; display:inline-block;" onclick="window.goToLbImage(${idx})"></span>`).join('');
                } else {
                    lbPrev.style.display = 'none'; lbNext.style.display = 'none'; lbDots.innerHTML = '';
                }
            }

            window.goToLbImage = function(idx) { currentIndex = idx; updateLightbox(); };
            const closeLightbox = () => { lightbox.classList.remove('active'); document.body.style.overflow = 'auto'; };
            lbClose.onclick = closeLightbox;
            lightbox.onclick = (e) => { if (e.target === lightbox) closeLightbox(); };
            lbNext.onclick = () => { currentIndex = (currentIndex + 1) % currentImages.length; updateLightbox(); };
            lbPrev.onclick = () => { currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length; updateLightbox(); };

            document.addEventListener('keydown', (e) => {
                if (!lightbox.classList.contains('active')) return;
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowRight' && currentImages.length > 1) lbNext.click();
                if (e.key === 'ArrowLeft' && currentImages.length > 1) lbPrev.click();
            });

            // === 3. MODAL ARTIKEL (SAMA DENGAN INFORMASI) ===
            const articleModal = document.getElementById('articleModal');
            window.openArticleModal = function(data) {
                const baseUrl = "{{ asset('') }}";
                document.getElementById('modalTitle').innerText = data.title;
                document.getElementById('modalContent').innerText = data.content;

                const dateObj = new Date(data.published_at);
                document.getElementById('modalDate').innerText = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                const catEl = document.getElementById('modalCategory');
                catEl.innerText = data.category;
                if(data.category === 'Pengumuman') catEl.style.background = '#ef4444';
                else if(data.category === 'Kegiatan') catEl.style.background = '#f59e0b';
                else catEl.style.background = '#3b82f6';

                const imgEl = document.getElementById('modalImg');
                const phEl = document.getElementById('modalPlaceholder');
                if(data.image) {
                    imgEl.src = baseUrl + data.image;
                    imgEl.style.display = 'block'; phEl.style.display = 'none';
                } else {
                    imgEl.style.display = 'none'; phEl.style.display = 'flex'; phEl.innerText = data.category;
                }

                const linkContainer = document.getElementById('modalLinkContainer');
                if(data.link) {
                    document.getElementById('modalLink').href = data.link;
                    linkContainer.style.display = 'block';
                } else {
                    linkContainer.style.display = 'none';
                }

                articleModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            };

            window.closeArticleModal = function() {
                articleModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            };

            articleModal.addEventListener('click', function(e) {
                if(e.target === articleModal) closeArticleModal();
            });
        });
    </script>
@endsection
