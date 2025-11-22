@extends('layouts.app')

@section('title', 'Galeri - Portal Karang Taruna')

@section('content')
    <section class="section" id="galeri">
        <div class="container">
            <h2 class="section__title">Galeri Kegiatan</h2>

            <div class="gallery-grid" id="galleryGrid">
                @forelse($galleries as $key => $item)
                    @php
                        // 1. Decode JSON data gambar
                        $images = json_decode($item->images);

                        // 2. Ambil gambar pertama sebagai cover
                        $thumb = !empty($images) ? $images[0] : null;

                        // 3. Hitung jumlah foto
                        $count = is_array($images) ? count($images) : 0;
                    @endphp

                    <div class="gallery-item" onclick="openDatabaseLightbox({{ $key }})">
                        <div style="position:relative; height: 250px; overflow: hidden; border-radius: 8px;">
                            @if($thumb)
                                <img src="{{ asset($thumb) }}" alt="{{ $item->caption }}"
                                     style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                            @else
                                <div style="width:100%; height:100%; background:#eee; display:flex; align-items:center; justify-content:center; color:#888;">
                                    No Image
                                </div>
                            @endif

                            @if($count > 1)
                                <div style="position:absolute; top:10px; right:10px; background:rgba(0,0,0,0.6); color:white; padding:4px 8px; border-radius:4px; font-size:12px;">
                                    📷 {{ $count }} Foto
                                </div>
                            @endif
                        </div>

                        <div class="gallery-item__caption" style="padding: 15px; text-align: center;">
                            <strong style="font-size: 16px; display: block; margin-bottom: 5px;">{{ $item->caption }}</strong>
                            <small style="color:#666; font-size: 13px;">
                                📅 {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}
                            </small>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: #f9f9f9; border-radius: 8px;">
                        <p style="color: #666; margin-bottom: 10px;">Belum ada dokumentasi kegiatan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <div id="lightbox" class="lightbox">
        <button class="lightbox__close" id="lightboxClose">&times;</button>

        <div class="lightbox__content" style="flex-direction: column; max-width: 1280px; width: 95%;">

            <div style="position: relative; width: 100%; display: flex; justify-content: center; align-items: center;">
                <button class="lightbox__nav lightbox__prev" id="lightboxPrev" style="left: -50px;">&#10094;</button>

                <img src="" alt="Full View" class="lightbox__img" id="lightboxImg"
                    style="max-width: 640px; max-height: 480px; width: auto; height: auto; object-fit: contain; border-radius: 4px; box-shadow: 0 5px 20px rgba(0,0,0,0.5);">

                <button class="lightbox__nav lightbox__next" id="lightboxNext" style="right: -50px;">&#10095;</button>
            </div>

            <div id="lightboxCaption" style="color: white; margin-top: 15px; text-align: center; font-size: 18px; font-weight: 500;"></div>

            <div class="lightbox__dots" id="lightboxDots" style="margin-top: 10px; display:flex; gap:8px; justify-content:center;"></div>
        </div>
    </div>

    <script>
        // Oper data PHP ke JS
        const dbGalleries = @json($galleries);
        let activeImages = [];
        let currentIndex = 0;
        let currentCaption = "";

        function openDatabaseLightbox(index) {
            const item = dbGalleries[index];
            if(!item) return;

            // Parsing JSON gambar
            activeImages = JSON.parse(item.images);
            currentCaption = item.caption;

            if (activeImages.length > 0) {
                currentIndex = 0;
                updateLightboxUI();
                document.getElementById('lightbox').classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function updateLightboxUI() {
            const imgEl = document.getElementById('lightboxImg');
            const prevBtn = document.getElementById('lightboxPrev');
            const nextBtn = document.getElementById('lightboxNext');
            const dotsContainer = document.getElementById('lightboxDots');
            const captionEl = document.getElementById('lightboxCaption');
            const baseUrl = "{{ asset('') }}"; // Ambil URL dasar Laravel

            // Set Source Gambar & Caption
            imgEl.src = baseUrl + activeImages[currentIndex];

            // Logic Caption: Nama Kegiatan (1/3)
            const counter = activeImages.length > 1 ? ` (${currentIndex + 1}/${activeImages.length})` : '';
            captionEl.innerText = currentCaption + counter;

            // Logic Navigasi (Carousel)
            // Kalo cuma 1 gambar, sembunyikan tombol next/prev
            if (activeImages.length > 1) {
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';

                // Render Dots
                dotsContainer.innerHTML = activeImages.map((_, idx) => `
                    <span class="lightbox__dot ${idx === currentIndex ? 'active' : ''}"
                          style="width:10px; height:10px; background:${idx === currentIndex ? '#A50104' : 'rgba(255,255,255,0.5)'}; border-radius:50%; cursor:pointer; display:inline-block;"
                          onclick="goToSlide(${idx})"></span>
                `).join('');
            } else {
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
                dotsContainer.innerHTML = '';
            }
        }

        function goToSlide(index) {
            currentIndex = index;
            updateLightboxUI();
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            const lightbox = document.getElementById('lightbox');
            const closeBtn = document.getElementById('lightboxClose');
            const prevBtn = document.getElementById('lightboxPrev');
            const nextBtn = document.getElementById('lightboxNext');

            const closeAction = () => {
                lightbox.classList.remove('active');
                document.body.style.overflow = 'auto';
            };

            closeBtn.addEventListener('click', closeAction);
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) closeAction();
            });

            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % activeImages.length;
                updateLightboxUI();
            });

            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + activeImages.length) % activeImages.length;
                updateLightboxUI();
            });

            // Keyboard Support
            document.addEventListener('keydown', (e) => {
                if (!lightbox.classList.contains('active')) return;
                if (e.key === 'Escape') closeAction();
                if (e.key === 'ArrowRight' && activeImages.length > 1) nextBtn.click();
                if (e.key === 'ArrowLeft' && activeImages.length > 1) prevBtn.click();
            });
        });
    </script>

    <style>
        /* CSS Khusus Lightbox */
        .lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.95); z-index: 9999; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s; }
        .lightbox.active { display: flex; opacity: 1; }
        .lightbox__close { position: absolute; top: 20px; right: 30px; color: white; font-size: 40px; background: none; border: none; cursor: pointer; z-index: 10001; }
        .lightbox__nav { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.1); color: white; border: none; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; font-size: 24px; display: flex; justify-content: center; align-items: center; transition: 0.3s; }
        .lightbox__nav:hover { background: #A50104; }
    </style>
@endsection
