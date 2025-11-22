<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal Karang Taruna - Layanan Mediasi, Pengaduan, dan Pengajuan Proposal">
    <title>@yield('title', 'Portal Karang Taruna')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo-karang-taruna.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="header" id="header">
        <div class="container">
            <div class="header__content">
                <div class="header__logo">
                    <img src="{{ asset('assets/img/logo-karang-taruna.svg') }}" alt="logo-karang-taruna">
                    <span class="header__title">Karang Taruna</span>
                </div>
                <nav class="nav" id="nav">
                    <div class="nav__overlay" id="navOverlay"></div>
                    <button class="nav__close" id="navClose">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                    <ul class="nav__list">
                        <li><a href="{{ url('/') }}" class="nav__link {{ request()->is('/') ? 'nav__link--active' : '' }}">Beranda</a></li>
                        <li><a href="{{ url('/informasi') }}" class="nav__link {{ request()->is('informasi') ? 'nav__link--active' : '' }}">Informasi</a></li>
                        <li><a href="{{ url('/galeri') }}" class="nav__link {{ request()->is('galeri') ? 'nav__link--active' : '' }}">Galeri</a></li>
                        <li><a href="{{ url('/pengaduan') }}" class="nav__link {{ request()->is('pengaduan') ? 'nav__link--active' : '' }}">Pengaduan</a></li>
                        <li><a href="{{ url('/pengajuan') }}" class="nav__link {{ request()->is('pengajuan') ? 'nav__link--active' : '' }}">Pengajuan</a></li>
                        <li style="display: flex; align-items: center;">
                            <button id="themeToggle" style="background:none; border:none; font-size:20px; cursor:pointer; padding:0 10px;">🌙</button>
                        </li>
                    </ul>
                </nav>
                <button class="nav__toggle" id="navToggle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
            </div>
        </div>
    </header>

    <main class="main">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__section">
                    <h4 class="footer__title">Karang Taruna</h4>
                    <p class="footer__text">Organisasi kepemudaan yang bergerak dalam pemberdayaan masyarakat dan pelayanan publik.</p>
                </div>
                <div class="footer__section">
                    <h4 class="footer__title">Kontak</h4>
                    <p class="footer__text">Email: karangtarunasu613@gmail.com<br>Alamat: Jl. Mawar 2 RT006/RW013, Kel.Bintaro, Kec.Pesanggrahan, Kota.Jakarta Selatan</p>
                </div>
                <div class="footer__section">
                    <h4 class="footer__title">Kunjungi Sosial Media Kami</h4>
                    <p class="footer__text">
                        Instagram: <a href="https://www.instagram.com/ktsu006013?igsh=OXBlaXd4aGUwM291" target="_blank">@ktsu006013</a><br>
                        Tiktok: <a href="https://www.tiktok.com/@ktsu006013?_r=1&_t=ZS-91ZmicLcYoG" target="_blank">ktsu006013</a>
                    </p>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2025 Karang Taruna Sub Unit 003/016. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <div id="toast" class="toast" role="alert"></div>
    <div id="modal" class="modal" role="dialog">
        <div class="modal__overlay" id="modalOverlay"></div>
        <div class="modal__content">
            <div class="modal__header">
                <h3 id="modalTitle" class="modal__title"></h3>
                <button class="modal__close" id="modalClose">×</button>
            </div>
            <div id="modalBody" class="modal__body"></div>
            <div id="modalFooter" class="modal__footer"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navToggle = document.getElementById('navToggle');
            const navClose = document.getElementById('navClose');
            const navMenu = document.getElementById('nav');
            const navOverlay = document.getElementById('navOverlay');

            // Fungsi Buka Menu
            if (navToggle) {
                navToggle.addEventListener('click', () => {
                    navMenu.classList.add('nav--open');
                });
            }

            // Fungsi Tutup Menu (Tombol Close)
            if (navClose) {
                navClose.addEventListener('click', () => {
                    navMenu.classList.remove('nav--open');
                });
            }

            // Fungsi Tutup Menu (Klik Overlay / Background Gelap)
            if (navOverlay) {
                navOverlay.addEventListener('click', () => {
                    navMenu.classList.remove('nav--open');
                });
            }

            // Fungsi Tutup Menu (Klik Link Apapun)
            const navLinks = document.querySelectorAll('.nav__link');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    navMenu.classList.remove('nav--open');
                });
            });
        });
    </script>
</body>
</html>
