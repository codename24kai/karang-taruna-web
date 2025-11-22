@extends('layouts.admin')

@section('header', 'Dashboard')

@section('content')
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-header">
                <div><div class="stat-number">{{ $stats['pengaduan'] }}</div><div class="stat-label">Total Pengaduan</div></div>
                <div class="stat-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6M9 16h6M9 8h6M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg></div>
            </div>
        </div>
        <div class="stat-card secondary">
            <div class="stat-header">
                <div><div class="stat-number">{{ $stats['galeri'] }}</div><div class="stat-label">Galeri Aktif</div></div>
                <div class="stat-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-header">
                <div><div class="stat-number">{{ $stats['proposal'] }}</div><div class="stat-label">Total Proposal</div></div>
                <div class="stat-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg></div>
            </div>
        </div>
        <div class="stat-card warning">
            <div class="stat-header">
                <div><div class="stat-number">{{ $stats['artikel'] }}</div><div class="stat-label">Artikel Dipublish</div></div>
                <div class="stat-icon"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></div>
            </div>
        </div>
    </div>

    <div class="content-grid">

        <div class="content-card dashboard-slider">
            <div class="slider-header">
                <h3 class="content-card-title" id="sliderTitle">Pengaduan Terbaru</h3>
                <div class="slider-controls">
                    <span class="dot active" onclick="setSlide(0)"></span>
                    <span class="dot" onclick="setSlide(1)"></span>
                    <span class="dot" onclick="setSlide(2)"></span>
                    <span class="dot" onclick="setSlide(3)"></span>
                </div>
            </div>

            <div class="slide-item active" id="slide-pengaduan">
                <table class="data-table">
                    <thead><tr><th>Tiket</th><th>Pelapor</th><th>Judul</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($slide_pengaduan as $p)
                        <tr onclick="window.location.href='{{ url('/admin/pengaduan') }}'" style="cursor:pointer;">
                            <td>{{ $p->ticket_number }}</td>
                            <td>{{ $p->nama }}</td>
                            <td>{{ Str::limit($p->judul, 20) }}</td>
                            <td><span class="status-badge {{ strtolower($p->status) }}">{{ $p->status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <a href="{{ url('/admin/pengaduan') }}" class="view-all-link mt-3">Lihat Semua Pengaduan →</a>
            </div>

            <div class="slide-item" id="slide-proposal">
                <table class="data-table">
                    <thead><tr><th>ID</th><th>Pengaju</th><th>Judul</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($slide_proposal as $p)
                        <tr onclick="window.location.href='{{ url('/admin/proposal') }}'" style="cursor:pointer;">
                            <td>{{ $p->proposal_number }}</td>
                            <td>{{ $p->nama_pengaju }}</td>
                            <td>{{ Str::limit($p->judul, 20) }}</td>
                            <td><span class="status-badge {{ strtolower($p->status) == 'disetujui' ? 'done' : 'pending' }}">{{ $p->status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <a href="{{ url('/admin/proposal') }}" class="view-all-link mt-3">Lihat Semua Proposal →</a>
            </div>

            <div class="slide-item" id="slide-galeri">
                <div class="gallery-preview-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                    @forelse($slide_galeri as $g)
                        @php $imgs = json_decode($g->images); @endphp
                        <div class="gallery-preview-item" onclick="window.location.href='{{ url('/admin/galeri') }}'" style="cursor:pointer;">
                            <img src="{{ asset($imgs[0]) }}" style="width:100%; height:100px; object-fit:cover; border-radius:8px;">
                            <div style="font-size:11px; margin-top:4px; font-weight:600;">{{ Str::limit($g->caption, 15) }}</div>
                        </div>
                    @empty
                        <p style="grid-column:1/-1; text-align:center;">Belum ada foto</p>
                    @endforelse
                </div>
                <a href="{{ url('/admin/galeri') }}" class="view-all-link mt-3">Lihat Semua Galeri →</a>
            </div>

            <div class="slide-item" id="slide-artikel">
                <div class="article-list-preview">
                    @forelse($slide_artikel as $a)
                        <div class="article-preview-item" onclick="window.location.href='{{ url('/admin/artikel') }}'" style="cursor:pointer; display:flex; gap:10px; margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid #eee;">
                            <img src="{{ asset($a->image) }}" style="width:60px; height:60px; object-fit:cover; border-radius:6px;">
                            <div>
                                <div style="font-weight:600; font-size:14px;">{{ $a->title }}</div>
                                <div style="font-size:12px; color:#666;">{{ $a->category }} • {{ $a->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <p style="text-align:center;">Belum ada artikel</p>
                    @endforelse
                </div>
                <a href="{{ url('/admin/artikel') }}" class="view-all-link mt-3">Lihat Semua Artikel →</a>
            </div>
        </div>

        <div class="content-card">
            <div class="content-card-header"><h3 class="content-card-title">Aktivitas Terkini</h3></div>
            <ul class="activity-list" style="list-style:none; padding:0;">
                @forelse($recent_activities as $act)
                    <li class="activity-item" onclick="window.location.href='{{ $act['url'] }}'" style="display:flex; gap:15px; padding:15px 0; border-bottom:1px solid #eee; cursor:pointer;">
                        <div class="activity-icon" style="width:40px; height:40px; border-radius:50%; background:{{ $act['color'] }}; color:{{ $act['text_color'] }}; display:flex; align-items:center; justify-content:center; font-size:18px;">
                            {{ $act['icon'] }}
                        </div>
                        <div class="activity-content">
                            <div class="activity-title" style="font-weight:600; font-size:14px;">{{ $act['title'] }}</div>
                            <div class="activity-desc" style="font-size:13px; color:#666;">{{ $act['desc'] }}</div>
                            <div class="activity-time" style="font-size:11px; color:#999; margin-top:2px;">{{ $act['time']->diffForHumans() }}</div>
                        </div>
                    </li>
                @empty
                    <li style="text-align:center; padding:20px; color:#666;">Belum ada aktivitas baru.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <script>
        let currentSlide = 0;
        const slides = ['slide-pengaduan', 'slide-proposal', 'slide-galeri', 'slide-artikel'];
        const titles = ['Pengaduan Terbaru', 'Proposal Terbaru', 'Galeri Terbaru', 'Artikel Terbaru'];
        let slideInterval;

        function showSlide(index) {
            // Hide all
            document.querySelectorAll('.slide-item').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.dot').forEach(el => el.classList.remove('active'));

            // Show active
            const slide = document.getElementById(slides[index]);
            if(slide) slide.classList.add('active');

            // Update dot
            const dots = document.querySelectorAll('.dot');
            if(dots[index]) dots[index].classList.add('active');

            // Update Title
            const titleEl = document.getElementById('sliderTitle');
            if(titleEl) titleEl.innerText = titles[index];

            currentSlide = index;
        }

        function setSlide(index) {
            clearInterval(slideInterval); // Stop auto kalau user ngeklik
            showSlide(index);
            startAutoSlide(); // Start lagi
        }

        function startAutoSlide() {
            slideInterval = setInterval(() => {
                let next = (currentSlide + 1) % slides.length;
                showSlide(next);
            }, 5000); // Ganti tiap 5 detik
        }

        // Jalanin pas loading
        document.addEventListener('DOMContentLoaded', () => {
            startAutoSlide();
        });
    </script>

    <style>
        /* Tambahan CSS dikit biar slide-nya jalan */
        .slide-item { display: none; animation: fadeIn 0.5s; }
        .slide-item.active { display: block; }
        .dot { height: 10px; width: 10px; background-color: #bbb; border-radius: 50%; display: inline-block; cursor: pointer; transition: 0.3s; }
        .dot.active { background-color: #A50104; width: 25px; border-radius: 10px; }
        .slider-controls { display: flex; gap: 5px; }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
    </style>
@endsection
