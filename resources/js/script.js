/**
 * Portal Karang Taruna - Main JavaScript
 * Vanilla JS - No Framework Dependencies
 * * Modules:
 * - Navigation & UI Controls
 * - Article Management (Brief & Full)
 * - Form Validation & Submission
 * - File Upload & Preview
 * - LocalStorage Draft Management
 * - PDF Export (Client-side)
 * - Admin Panel
 * - Fake API Simulation
 */

// ==================== Global State ====================
const STATE = {
    articles: [],
    galleries: [], // Tambahan state untuk galeri
    currentPage: 1,
    articlesPerPage: 6,
    filteredArticles: [],
    submissions: {
        pengaduan: [],
        pengajuan: []
    },
    // State khusus Lightbox
    lightbox: {
        activeImages: [], // Array URL gambar yang sedang dibuka
        currentIndex: 0
    }
};



// Admin credentials (dummy - stored in JS)
const ADMIN_CREDENTIALS = {
    username: 'admin',
    password: 'admin123'
};

// ==================== Utility Functions ====================

function showToast(message, type = 'info') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = `toast toast--${type} toast--show`;
    setTimeout(() => toast.classList.remove('toast--show'), 3000);
}

function showModal(title, body, footerButtons = []) {
    const modal = document.getElementById('modal');
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalBody').innerHTML = body;

    const modalFooter = document.getElementById('modalFooter');
    modalFooter.innerHTML = '';
    footerButtons.forEach(btn => {
        const button = document.createElement('button');
        button.className = btn.className || 'btn btn--primary';
        button.textContent = btn.text;
        button.onclick = btn.onClick;
        modalFooter.appendChild(button);
    });

    modal.classList.add('modal--show');
}

function closeModal() {
    document.getElementById('modal').classList.remove('modal--show');
}

function generateTrackingNumber(prefix = 'CTR') {
    const date = new Date();
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    const rand = String(Math.floor(Math.random() * 10000)).padStart(4, '0');
    return `${prefix}-${y}${m}${d}-${rand}`;
}

function formatCurrency(amount) {
    if (typeof amount === 'string') {
        amount = amount.replace(/[^0-9]/g, '');
    }
    return new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(amount);
}

function formatDate(dateString) {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    }).format(new Date(dateString));
}

function validateFile(file, maxSizeMB, allowedTypes) {
    if (!file) return { valid: false, error: 'Tidak ada file yang dipilih' };
    const maxSize = maxSizeMB * 1024 * 1024;
    if (file.size > maxSize) return { valid: false, error: `Ukuran file maksimal ${maxSizeMB}MB` };

    const ext = file.name.split('.').pop().toLowerCase();
    const isValid = allowedTypes.some(type =>
        type.startsWith('.') ? ext === type.substring(1) : file.type.includes(type)
    );
    if (!isValid) return { valid: false, error: 'Tipe file tidak didukung' };
    return { valid: true };
}

function fakeApiCall(data, delay = 1000) {
    return new Promise(resolve => {
        setTimeout(() => resolve({ success: true, data, message: 'Data berhasil dikirim' }), delay);
    });
}

// ==================== NEW: GALLERY & LIGHTBOX LOGIC ====================

function initGallery() {
    const grid = document.getElementById('galleryGrid');
    if (!grid) return; // Hanya jalan di galeri.html

    // Ambil data dari LocalStorage (disimpan oleh Admin)
    const storedData = localStorage.getItem('db_galeri');
    let galleries = storedData ? JSON.parse(storedData) : [];

    // Kalau kosong, pakai dummy data
    if (galleries.length === 0) {
        galleries = [
            {
                id: 1,
                caption: "Kerja Bakti Lingkungan",
                image: ["https://via.placeholder.com/800x600?text=Kerja+Bakti+1", "https://via.placeholder.com/800x600?text=Kerja+Bakti+2"],
                date: "2025-01-15"
            },
            {
                id: 2,
                caption: "Rapat Bulanan",
                image: "https://via.placeholder.com/800x600?text=Rapat+KTSU", // Support string tunggal (legacy)
                date: "2025-02-01"
            }
        ];
    }

    STATE.galleries = galleries;
    renderGalleryGrid(grid);
    initLightboxControls();
}

function renderGalleryGrid(container) {
    if (STATE.galleries.length === 0) {
        container.innerHTML = '<p style="grid-column:1/-1;text-align:center;">Belum ada dokumentasi.</p>';
        return;
    }

    container.innerHTML = STATE.galleries.map((item, index) => {
        // Cek apakah image itu Array atau String tunggal
        // Kita ambil gambar pertama sebagai thumbnail
        let thumbnail = '';
        let countBadge = '';
        
        if (Array.isArray(item.image)) {
            thumbnail = item.image[0]; // Ambil yang pertama
            if (item.image.length > 1) {
                // Tambah badge icon layer kalau foto > 1
                countBadge = `<div style="position:absolute; top:10px; right:10px; background:rgba(0,0,0,0.6); color:white; padding:4px 8px; border-radius:4px; font-size:12px;">
                    📷 ${item.image.length} Foto
                </div>`;
            }
        } else {
            thumbnail = item.image; // Legacy support
        }

        return `
            <div class="gallery-item" onclick="openLightbox(${index})">
                <div style="position:relative;">
                    <img src="${thumbnail}" alt="${item.caption}">
                    ${countBadge}
                </div>
                <div class="gallery-item__caption">
                    <strong>${item.caption}</strong><br>
                    <small style="color:#666">${formatDate(item.date)}</small>
                </div>
            </div>
        `;
    }).join('');
}

// --- LIGHTBOX LOGIC ---

function openLightbox(galleryIndex) {
    const item = STATE.galleries[galleryIndex];
    if (!item) return;

    // Normalisasi data gambar jadi Array, biar logic-nya seragam
    if (Array.isArray(item.image)) {
        STATE.lightbox.activeImages = item.image;
    } else {
        STATE.lightbox.activeImages = [item.image];
    }

    STATE.lightbox.currentIndex = 0;
    updateLightboxView();
    
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden'; // Disable scroll body
}

function updateLightboxView() {
    const imgEl = document.getElementById('lightboxImg');
    const prevBtn = document.getElementById('lightboxPrev');
    const nextBtn = document.getElementById('lightboxNext');
    const dotsContainer = document.getElementById('lightboxDots');

    // Set Gambar Utama
    const currentUrl = STATE.lightbox.activeImages[STATE.lightbox.currentIndex];
    imgEl.src = currentUrl;

    // Logic Tombol Navigasi (Carousel)
    if (STATE.lightbox.activeImages.length > 1) {
        prevBtn.style.display = 'flex';
        nextBtn.style.display = 'flex';
        
        // Render Dots
        dotsContainer.innerHTML = STATE.lightbox.activeImages.map((_, idx) => `
            <span class="lightbox__dot ${idx === STATE.lightbox.currentIndex ? 'active' : ''}" 
                  onclick="goToLightboxSlide(${idx})"></span>
        `).join('');
    } else {
        // Kalau cuma 1 foto, sembunyikan navigasi
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
        dotsContainer.innerHTML = '';
    }
}

// Fungsi global biar bisa dipanggil dari HTML (onclick)
window.goToLightboxSlide = (index) => {
    STATE.lightbox.currentIndex = index;
    updateLightboxView();
};

function initLightboxControls() {
    const closeBtn = document.getElementById('lightboxClose');
    const prevBtn = document.getElementById('lightboxPrev');
    const nextBtn = document.getElementById('lightboxNext');
    const lightbox = document.getElementById('lightbox');

    if(!lightbox) return;

    // Close
    const closeAction = () => {
        lightbox.classList.remove('active');
        document.body.style.overflow = 'auto'; // Enable scroll lagi
    };
    
    closeBtn.addEventListener('click', closeAction);
    
    // Klik background buat close
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) closeAction();
    });

    // Next Slide
    nextBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        STATE.lightbox.currentIndex = (STATE.lightbox.currentIndex + 1) % STATE.lightbox.activeImages.length;
        updateLightboxView();
    });

    // Prev Slide
    prevBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        STATE.lightbox.currentIndex = (STATE.lightbox.currentIndex - 1 + STATE.lightbox.activeImages.length) % STATE.lightbox.activeImages.length;
        updateLightboxView();
    });

    // Keyboard Support (Esc, Arrow Left, Arrow Right)
    document.addEventListener('keydown', (e) => {
        if (!lightbox.classList.contains('active')) return;
        
        if (e.key === 'Escape') closeAction();
        if (e.key === 'ArrowRight' && STATE.lightbox.activeImages.length > 1) nextBtn.click();
        if (e.key === 'ArrowLeft' && STATE.lightbox.activeImages.length > 1) prevBtn.click();
    });
}
if (document.getElementById('galleryGrid')) {
        initGallery();
    }

// ==================== PDF Export ====================

function exportToPDF(data, type) {
    // Dummy function
    console.log(`Exporting ${type} data to PDF:`, data);
    const content = `
        <h3>Detail ${type.charAt(0).toUpperCase() + type.slice(1)}</h3>
        <p>ID: ${data.id}</p>
        <p>Tanggal: ${formatDate(data.tanggalPengajuan || new Date().toISOString())}</p>
        ${type === 'pengaduan' ? `<p>Judul: ${data.judul}</p><p>Deskripsi: ${data.deskripsi.substring(0, 100)}...</p>` : ''}
        ${type === 'mediasi' ? `<p>Nama: ${data.nama}</p><p>Kasus: ${data.ringkasanKasus.substring(0, 100)}...</p>` : ''}
        ${type === 'pengajuan' ? `<p>Judul: ${data.judul}</p><p>Anggaran: ${formatCurrency(data.anggaran)}</p>` : ''}
        <p>Silakan simpan informasi ini.</p>
    `;
    showModal('Export PDF Berhasil', content, [
        { text: 'Tutup', className: 'btn btn--primary', onClick: closeModal }
    ]);
}

// ==================== Navigation & UI ====================

function initNavigation() {
    const navToggle = document.getElementById('navToggle');
    const navClose = document.getElementById('navClose');
    const nav = document.getElementById('nav');
    const links = document.querySelectorAll('.nav__link');

    navToggle.addEventListener('click', () => nav.classList.add('nav--open'));
    navClose.addEventListener('click', () => nav.classList.remove('nav--open'));
    document.getElementById('navOverlay').addEventListener('click', () => nav.classList.remove('nav--open'));

    links.forEach(link => {
        link.addEventListener('click', () => {
            nav.classList.remove('nav--open');
        });
    });

    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
        const y = window.pageYOffset;
        header.style.boxShadow = y > 100
            ? '0 4px 12px rgba(0,0,0,0.1)'
            : '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
    });

    // Handle initial load for breadcrumb
    const activeLink = document.querySelector('.nav__link--active');
    if (activeLink) {
        const href = activeLink.getAttribute('href');
        let pageId = 'beranda';
        if (href !== 'index.html' && href.includes('.html')) {
             pageId = href.substring(0, href.indexOf('.html'));
        }
        updateBreadcrumb(activeLink.textContent, pageId);
    }
}

function updateBreadcrumb(text, id) {
    const breadcrumb = document.getElementById('breadcrumb');
    const list = document.getElementById('breadcrumbList');
    if (!breadcrumb) return; 
    
    if (id === 'beranda') {
        breadcrumb.style.display = 'none';
    } else {
        breadcrumb.style.display = 'block';
        list.innerHTML = `
            <li class="breadcrumb__item"><a href="index.html">Beranda</a></li>
            <li class="breadcrumb__item">${text}</li>`;
    }
}

function initModal() {
    const modal = document.getElementById('modal');
    document.getElementById('modalClose').addEventListener('click', closeModal);
    document.getElementById('modalOverlay').addEventListener('click', closeModal);
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && modal.classList.contains('modal--show')) closeModal();
    });
}

// === HERO CAROUSEL LOGIC (UPDATED) ===
// === HERO CAROUSEL LOGIC (Pastikan kode ini ada) ===
function initHeroCarousel() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.carousel-dots .dot');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    // Kalau gak ada carousel di halaman ini, stop biar gak error
    if (slides.length === 0) return;

    let currentSlide = 0;
    let slideInterval;
    const intervalTime = 3500; // 3.5 Detik

    // Fungsi Ganti Slide
    function goToSlide(index) {
        // 1. Hapus class active dari slide & dot yang sekarang
        slides[currentSlide].classList.remove('active');
        if(dots[currentSlide]) dots[currentSlide].classList.remove('active');

        // 2. Hitung index baru (biar muter terus/looping)
        currentSlide = (index + slides.length) % slides.length;

        // 3. Tambah class active ke slide & dot baru
        slides[currentSlide].classList.add('active');
        if(dots[currentSlide]) dots[currentSlide].classList.add('active');
    }

    // Fungsi Otomatis Jalan
    function startAutoSlide() {
        slideInterval = setInterval(() => {
            goToSlide(currentSlide + 1);
        }, intervalTime);
    }

    // Reset Timer (Biar gak loncat/bentrok pas abis diklik)
    function resetTimer() {
        clearInterval(slideInterval);
        startAutoSlide();
    }

    // Event Listeners Tombol Kiri/Kanan
    if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Biar gak refresh halaman kalau tombolnya tipe link
            goToSlide(currentSlide + 1);
            resetTimer();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            goToSlide(currentSlide - 1);
            resetTimer();
        });
    }

    // Mulai Otomatis pas pertama kali buka
    startAutoSlide();
}

// ==================== Articles (Data) ====================

function generateArticles() {
    const categories = ['pengumuman', 'kegiatan', 'berita', 'artikel'];
    const titles = [
        'Rapat Koordinasi Bulanan Karang Taruna',
        'Program Bakti Sosial di Desa Sejahtera',
        'Pelatihan Kewirausahaan untuk Pemuda',
        'Gotong Royong Bersih Kampung',
        'Pengumuman Seleksi Pengurus Baru',
        'Workshop Digital Marketing',
        'Kegiatan Donor Darah Massal',
        'Lomba Kreativitas Pemuda',
        'Sosialisasi Bahaya Narkoba',
        'Festival Seni dan Budaya Lokal',
        'Program Pendampingan UMKM',
        'Turnamen Olahraga Antar RT'
    ];
    return titles.map((title, i) => ({
        id: i + 1,
        title,
        excerpt: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        category: categories[Math.floor(Math.random() * categories.length)],
        date: new Date(2025, 9, Math.floor(Math.random() * 28) + 1).toISOString(),
        image: `Article ${i + 1}`
    }));
}

// ==================== Brief Articles (Homepage) ====================

/**
 * Renders a brief list of articles for the homepage.
 */
function initBriefArticles() {
    const grid = document.getElementById('briefArticlesGrid');
    if (!grid) return;

    if (STATE.articles.length === 0) {
        STATE.articles = generateArticles();
    }

    const briefArticleCount = 3;
    const show = STATE.articles.slice(0, briefArticleCount);

    if (!show.length) {
        grid.innerHTML = '<p class="admin-empty">Belum ada informasi terbaru.</p>';
        return;
    }
    // Kita tambahin onclick="openArticleDetail(${a.id})" di sini 👇
    grid.innerHTML = show.map(a => `
        <article class="article-card clickable" onclick="openArticleDetail(${a.id})">
            <div class="article-card__image">${a.image}</div>
            <div class="article-card__content">
                <span class="article-card__category">${a.category}</span>
                <h3 class="article-card__title">${a.title}</h3>
                <p class="article-card__excerpt">${a.excerpt}</p>
                <time class="article-card__date">${formatDate(a.date)}</time>
            </div>
        </article>`).join('');
}

// ==================== Articles (Full Page) ====================

/**
 * Renders the full, filterable list of articles for the info page.
 */
function initArticles() {
    const grid = document.getElementById('articlesGrid');
    if (!grid) return; // Hanya jalan di informasi.html

    // Buat data artikel JIKA BELUM DIBUAT oleh initBriefArticles
    if (STATE.articles.length === 0) {
        STATE.articles = generateArticles();
    }
    
    // Set filter awal ke semua artikel
    STATE.filteredArticles = [...STATE.articles];
    
    // Render artikel dan pasang event listener
    renderArticles(); // Fungsi ini akan merender ke 'articlesGrid'
    document.getElementById('searchInput').addEventListener('input', filterArticles);
    document.getElementById('categoryFilter').addEventListener('change', filterArticles);
}


function renderArticles() {
    const grid = document.getElementById('articlesGrid');
    if (!grid) return; // Safety check
    
    const start = (STATE.currentPage - 1) * STATE.articlesPerPage;
    const end = start + STATE.articlesPerPage;
    const show = STATE.filteredArticles.slice(start, end);

    if (!show.length) {
        grid.innerHTML = '<p class="admin-empty">Tidak ada artikel ditemukan</p>';
        return;
    }

    // UPDATE BAGIAN INI: Tambahkan 'clickable' dan 'onclick'
    grid.innerHTML = show.map(a => `
        <article class="article-card clickable" onclick="openArticleDetail(${a.id})">
            <div class="article-card__image">${a.image}</div>
            <div class="article-card__content">
                <span class="article-card__category">${a.category}</span>
                <h3 class="article-card__title">${a.title}</h3>
                <p class="article-card__excerpt">${a.excerpt}</p>
                <time class="article-card__date">${formatDate(a.date)}</time>
            </div>
        </article>`).join('');

    renderPagination();
}

function renderPagination() {
    const el = document.getElementById('pagination');
    if (!el) return;
    
    const total = Math.ceil(STATE.filteredArticles.length / STATE.articlesPerPage);
    if (total <= 1) return el.innerHTML = '';

    let btns = `<button class="pagination__btn" ${STATE.currentPage === 1 ? 'disabled' : ''}
        onclick="changePage(${STATE.currentPage - 1})" aria-label="Previous Page">‹ Prev</button>`;

    for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || (i >= STATE.currentPage - 1 && i <= STATE.currentPage + 1))
            btns += `<button class="pagination__btn ${i === STATE.currentPage ? 'pagination__btn--active' : ''}"
                onclick="changePage(${i})" aria-label="Page ${i}">${i}</button>`;
        else if (i === STATE.currentPage - 2 || i === STATE.currentPage + 2)
            btns += `<span class="pagination__spacer" aria-hidden="true">...</span>`;
    }

    btns += `<button class="pagination__btn" ${STATE.currentPage === total ? 'disabled' : ''}
        onclick="changePage(${STATE.currentPage + 1})" aria-label="Next Page">Next ›</button>`;
    el.innerHTML = btns;
}

function changePage(p) {
    STATE.currentPage = p;
    renderArticles();
    document.getElementById('informasi').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function filterArticles() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const cat = document.getElementById('categoryFilter').value;
    STATE.filteredArticles = STATE.articles.filter(a =>
        (a.title.toLowerCase().includes(q) || a.excerpt.toLowerCase().includes(q)) &&
        (!cat || a.category === cat)
    );
    STATE.currentPage = 1;
    renderArticles();
}

// ==================== Form Validation ====================

function validateField(id, rules = {}) {
    const f = document.getElementById(id);
    if (!f) return true; 

    const err = document.getElementById(id + 'Error');
    const v = f.value.trim();

    if (rules.required && (!v || (f.type === 'file' && f.files.length === 0) || (f.tagName === 'SELECT' && v === ''))) 
        return (err.textContent = 'Field ini wajib diisi', f.setAttribute('aria-invalid', 'true'), false);

    if (rules.email && v && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)) return (err.textContent = 'Format email tidak valid', f.setAttribute('aria-invalid', 'true'), false);
    if (rules.phone && v && !/^[\d\s\-\+\(\)]+$/.test(v)) return (err.textContent = 'Format nomor telepon tidak valid', f.setAttribute('aria-invalid', 'true'), false);
    if (rules.minLength && v.length < rules.minLength) return (err.textContent = `Minimal ${rules.minLength} karakter`, f.setAttribute('aria-invalid', 'true'), false);
    if (rules.futureDate && v && new Date(v) < new Date().setHours(0, 0, 0, 0))
        return (err.textContent = 'Tanggal tidak boleh di masa lalu', f.setAttribute('aria-invalid', 'true'), false);
    if (rules.currency) {
        const cleanV = v.replace(/[^0-9]/g, '');
        if (isNaN(cleanV) || cleanV.length < 4) 
            return (err.textContent = 'Anggaran tidak valid', f.setAttribute('aria-invalid', 'true'), false);
    }

    err.textContent = ''; f.removeAttribute('aria-invalid'); return true;
}

function clearFormErrors(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    form.querySelectorAll('.form__error').forEach(e => e.textContent = '');
    form.querySelectorAll('[aria-invalid="true"]').forEach(f => f.removeAttribute('aria-invalid'));
}

// ==================== File Uploads ====================

function initPengaduanFileUpload() {
    const input = document.getElementById('pengaduanFile');
    if (!input) return; // Guard clause

    const label = document.getElementById('fileLabel');
    const preview = document.getElementById('filePreview');
    const content = document.getElementById('filePreviewContent');
    const remove = document.getElementById('removeFileBtn');
    const error = document.getElementById('pengaduanFileError');

    input.addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) {
            label.textContent = 'Pilih file atau drag & drop';
            preview.style.display = 'none';
            return;
        }

        const val = validateFile(file, 5, ['image/', '.pdf']);
        if (!val.valid) {
            error.textContent = val.error;
            input.value = ''; 
            label.textContent = 'Pilih file atau drag & drop';
            preview.style.display = 'none';
            return;
        }

        error.textContent = ''; 
        label.textContent = file.name; 
        preview.style.display = 'block';

        if (file.type.startsWith('image/')) {
            const r = new FileReader();
            r.onload = ev => content.innerHTML = `<img src="${ev.target.result}" alt="Preview">`;
            r.readAsDataURL(file);
        } else if (file.type === 'application/pdf') {
            content.innerHTML = `<p>📄 ${file.name}</p><p>Ukuran: ${(file.size / 1024 / 1024).toFixed(2)} MB</p>`;
        } else {
            content.innerHTML = `<p>File: ${file.name}</p><p>Tipe tidak ditampilkan</p>`;
        }
    });

    remove.addEventListener('click', () => {
        input.value = ''; 
        label.textContent = 'Pilih file atau drag & drop';
        preview.style.display = 'none'; 
        error.textContent = '';
    });
}

function initPengajuanFileUpload() {
    const input = document.getElementById('pengajuanDokumen');
    if (!input) return;

    const label = document.getElementById('dokumenLabel');
    const error = document.getElementById('pengajuanDokumenError');

    input.addEventListener('change', e => {
        const file = e.target.files[0];
        if (!file) {
            label.textContent = 'Pilih file PDF';
            return;
        }

        const val = validateFile(file, 10, ['.pdf']);
        if (!val.valid) {
            error.textContent = val.error;
            input.value = '';
            label.textContent = 'Pilih file PDF';
            return;
        }

        error.textContent = '';
        label.textContent = file.name;
    });
}

// ==================== Draft Management ====================

function saveDraft(data, type) {
    try {
        localStorage.setItem(`draft_${type}`, JSON.stringify({ ...data, savedAt: new Date().toISOString() }));
        showToast('Draft berhasil disimpan', 'success');
    } catch {
        showToast('Gagal menyimpan draft', 'error');
    }
}

function loadDraft(type, formId) {
    try {
        const data = localStorage.getItem(`draft_${type}`);
        if (!data) return showToast('Tidak ada draft tersimpan', 'info'), null;
        
        const draft = JSON.parse(data);
        const form = document.getElementById(formId);
        if (!form) return null;

        Object.keys(draft).forEach(key => {
            const field = form.querySelector(`#${key}`);
            if (field) {
                field.value = draft[key];
            }
        });

        if (type === 'pengaduan' && draft.file) {
            document.getElementById('fileLabel').textContent = `[Draft] ${draft.file}`;
            document.getElementById('filePreview').style.display = 'block';
            document.getElementById('filePreviewContent').innerHTML = `<p>File terakhir: ${draft.file}</p><p>Upload ulang jika perlu.</p>`;
        }
        
        showToast(`Draft dimuat (tersimpan ${formatDate(draft.savedAt)})`, 'success');
        return draft;
    } catch {
        showToast('Gagal memuat draft', 'error'); return null;
    }
}

function clearDraft(type) {
    localStorage.removeItem(`draft_${type}`);
}

// ==================== Mediasi Form (Sudah tidak terpakai) ====================

function initMediasiForm() {
    const form = document.getElementById('mediasiForm');
    if (!form) return; // Akan di-skip di semua halaman kecuali 'mediasi.html' (yang sudah tidak ada)

    form.addEventListener('submit', async e => {
        e.preventDefault();
        // ... (Logika form mediasi)
    });
}

// ==================== Pengaduan Form ====================

function initPengaduanForm() {
    const form = document.getElementById('pengaduanForm');
    if (!form) return;

    const saveBtn = document.getElementById('saveDraftBtn');
    const loadBtn = document.getElementById('loadDraftBtn');

    loadBtn?.addEventListener('click', () => {
        loadDraft('pengaduan', 'pengaduanForm');
    });

    saveBtn?.addEventListener('click', () => {
    const data = {
        pengaduanNama: form.pengaduanNama.value,
        pengaduanKontak: form.pengaduanKontak.value,
        pengaduanJudul: form.pengaduanJudul.value,
        pengaduanKategori: form.pengaduanKategori.value,
        pengaduanLokasi: form.pengaduanLokasi.value,
        pengaduanDeskripsi: form.pengaduanDeskripsi.value,
        file: form.pengaduanFile.files[0]?.name || null,
    };
    saveDraft(data, 'pengaduan');
    });

    form.addEventListener('submit', async e => {
        e.preventDefault();
        clearFormErrors('pengaduanForm');

        const valid =
            validateField('pengaduanJudul', { required: true, minLength: 5 }) &
            validateField('pengaduanKategori', { required: true }) &
            validateField('pengaduanLokasi', { required: true }) &
            validateField('pengaduanDeskripsi', { required: true, minLength: 20 });
        
        const fileInput = document.getElementById('pengaduanFile');
        let fileValid = true;
        if (fileInput.files.length > 0) {
            const fileValidation = validateFile(fileInput.files[0], 5, ['image/', '.pdf']);
            if (!fileValidation.valid) {
                document.getElementById('pengaduanFileError').textContent = fileValidation.error;
                fileValid = false;
            }
        }

        if (!valid || !fileValid) return showToast('Mohon lengkapi form dengan benar', 'error');

        const data = {
            id: generateTrackingNumber('CTR'),
            judul: form.pengaduanJudul.value,
            kategori: form.pengaduanKategori.value,
            lokasi: form.pengaduanLokasi.value,
            deskripsi: form.pengaduanDeskripsi.value,
            file: form.pengaduanFile.files[0]?.name || null,
            status: 'baru',
            tanggalPengaduan: new Date().toISOString()
        };

        const btn = form.querySelector('button[type="submit"]');
        const txt = btn.textContent;
        btn.disabled = true; 
        btn.textContent = 'Mengirim...';

        try {
            await fakeApiCall(data);
            STATE.submissions.pengaduan.push(data);
            showPengaduanConfirmation(data);
            form.reset();
            clearDraft('pengaduan'); 
            document.getElementById('removeFileBtn').click(); 
            showToast('Pengaduan berhasil dikirim', 'success');
        } catch {
            showToast('Gagal mengirim pengaduan', 'error');
        } finally {
            btn.disabled = false; btn.textContent = txt;
        }
    });

    document.getElementById('trackingBtn')?.addEventListener('click', trackPengaduan);
}

function showPengaduanConfirmation(data) {
    const body = `
        <div>
            <p><strong>Nomor Pengaduan:</strong> <span class="tracking-number-highlight">${data.id}</span></p>
            <p><strong>Judul:</strong> ${data.judul}</p>
            <p><strong>Tanggal Pengaduan:</strong> ${formatDate(data.tanggalPengaduan)}</p>
            <p style="background:#f3f4f6;padding:1rem;border-radius:6px;border-left: 3px solid var(--color-success);">
                Pengaduan Anda telah tercatat. Gunakan nomor tracking di atas untuk memantau status.
            </p>
        </div>`;
    showModal('Pengaduan Berhasil Dikirim', body, [
        { text: 'Export PDF', className: 'btn btn--secondary', onClick: () => exportToPDF(data, 'pengaduan') },
        { text: 'Tutup', className: 'btn btn--primary', onClick: closeModal }
    ]);
}

function trackPengaduan() {
    const trackingId = document.getElementById('trackingInput').value.trim();
    const resultEl = document.getElementById('trackingResult');
    if (!resultEl) return;

    if (!trackingId) {
        resultEl.style.display = 'block';
        resultEl.innerHTML = `<p class="error-text">Mohon masukkan nomor pengaduan.</p>`;
        return;
    }

    const submission = STATE.submissions.pengaduan.find(s => s.id === trackingId);
    
    if (submission) {
        let statusClass = '';
        let statusText = '';
        if (submission.status === 'baru') { statusClass = 'tracking-result__status--new'; statusText = 'Baru'; }
        else if (submission.status === 'proses') { statusClass = 'tracking-result__status--processing'; statusText = 'Diproses'; }
        else if (submission.status === 'selesai') { statusClass = 'tracking-result__status--completed'; statusText = 'Selesai'; }
        
        resultEl.style.display = 'block';
        resultEl.innerHTML = `
            <h4>Status Pengaduan Ditemukan</h4>
            <p><strong>Nomor:</strong> ${submission.id}</p>
            <p><strong>Judul:</strong> ${submission.judul}</p>
            <p><strong>Lokasi:</strong> ${submission.lokasi}</p>
            <p><strong>Tanggal:</strong> ${formatDate(submission.tanggalPengaduan)}</p>
            <span class="tracking-result__status ${statusClass}">${statusText}</span>
        `;
    } else {
        resultEl.style.display = 'block';
        resultEl.innerHTML = `<p class="error-text">Nomor pengaduan <strong>${trackingId}</strong> tidak ditemukan.</p>`;
    }
}

// ==================== Pengajuan Form ====================

function initPengajuanForm() {
    const form = document.getElementById('pengajuanForm');
    if (!form) return;

    const anggaranInput = document.getElementById('pengajuanAnggaran');
    anggaranInput?.addEventListener('input', e => {
        const value = e.target.value.replace(/[^0-9]/g, '');
        e.target.value = formatCurrency(value).replace('Rp', '').trim();
    });
    anggaranInput?.addEventListener('focus', e => {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    });
    anggaranInput?.addEventListener('blur', e => {
        if (e.target.value) {
            e.target.value = formatCurrency(e.target.value).replace('Rp', '').trim();
        }
    });

    // SUBMIT
form.addEventListener('submit', async e => {
    e.preventDefault();
    clearFormErrors('pengajuanForm');

    const cleanAnggaran = anggaranInput.value.replace(/[^0-9]/g, '');
    anggaranInput.value = cleanAnggaran; 

    // 1. UPDATE BAGIAN VALIDASI:
    const valid =
        validateField('pengajuanNama', { required: true, minLength: 3 }) & // Cek Nama
        validateField('pengajuanKontak', { required: true, minLength: 5 }) & // Cek Kontak
        validateField('pengajuanJudul', { required: true, minLength: 5 }) &
        validateField('pengajuanRingkasan', { required: true, minLength: 20 }) &
        validateField('pengajuanAnggaran', { required: true, currency: true }) &
        validateField('pengajuanPIC', { required: true, minLength: 3 }) &
        validateField('pengajuanDokumen', { required: true });

    anggaranInput.value = formatCurrency(cleanAnggaran).replace('Rp', '').trim();

    // ... (kode validasi file upload tetap sama) ...
    const fileInput = document.getElementById('pengajuanDokumen');
    let fileValid = true;
    // ... (logika validasi file lama) ...

    if (!valid || !fileValid) return showToast('Mohon lengkapi form dengan benar', 'error');

    // 2. UPDATE DATA YANG DIKIRIM:
    const data = {
        id: generateTrackingNumber('PRO'),
        // Tambahin 2 baris ini:
        pengaju: form.pengajuanNama.value, // Nama Pengaju
        kontak: form.pengajuanKontak.value, // Kontak Pengaju
        // ... Sisa data lama ...
        judul: form.pengajuanJudul.value,
        ringkasan: form.pengajuanRingkasan.value,
        anggaran: parseInt(cleanAnggaran),
        pic: form.pengajuanPIC.value,
        dokumen: form.pengajuanDokumen.files[0]?.name || null,
        status: 'menunggu',
        tanggalPengajuan: new Date().toISOString()
    };

    // ... (Sisa kode submit fakeApiCall, dll tetap sama) ...

        const btn = form.querySelector('button[type="submit"]');
        const txt = btn.textContent;
        btn.disabled = true; btn.textContent = 'Mengirim...';

        try {
            await fakeApiCall(data);
            STATE.submissions.pengajuan.push(data);
            showPengajuanConfirmation(data);
            form.reset();
            document.getElementById('dokumenLabel').textContent = 'Pilih file PDF';
            showToast('Pengajuan proposal berhasil dikirim', 'success');
        } catch {
            showToast('Gagal mengirim proposal', 'error');
        } finally {
            btn.disabled = false; btn.textContent = txt;
        }
    });

    document.getElementById('statusBtn')?.addEventListener('click', trackPengajuanStatus);
    
    // Di dalam function initPengajuanForm() ...
    
    // LOGIKA BARU: DOWNLOAD TEMPLATE
    document.getElementById('downloadTemplateBtn')?.addEventListener('click', e => {
        e.preventDefault();
        
        // HTML untuk isi Modal
        const templateMenu = `
            <div class="template-list">
                <a href="assets/docs/TEMPLATE PROPOSAL SEKULIR.docx" download class="template-item">
                    <div class="template-icon">📝</div>
                    <div class="template-info">
                        <h4>Proposal Kegiatan</h4>
                        <p>Format lengkap untuk acara HUT RI & kegiatan warga.</p>
                    </div>
                </a>

                <a href="assets/docs/Surat Peminjaman Barang.docx" download class="template-item">
                    <div class="template-icon">📦</div>
                    <div class="template-info">
                        <h4>Surat Peminjaman Barang</h4>
                        <p>Format surat untuk meminjam inventaris RT/RW.</p>
                    </div>
                </a>

                <a href="assets/docs/Surat Permohonan Dana.docx" download class="template-item">
                    <div class="template-icon">💰</div>
                    <div class="template-info">
                        <h4>Surat Permohonan Dana</h4>
                        <p>Format pengajuan dana bantuan ke donatur.</p>
                    </div>
                </a>
            </div>
            <div style="margin-top: 20px; font-size: 12px; color: #666; text-align: center;">
                *Klik salah satu untuk mengunduh file .docx
            </div>
        `;

        // Panggil Modal
        showModal('Pilih Template Dokumen', templateMenu, [
            { text: 'Tutup', className: 'btn btn--tertiary', onClick: closeModal }
        ]);
    });
    
}

function showPengajuanConfirmation(data) {
    const body = `
        <div>
            <p><strong>Nomor Pengajuan:</strong> <span class="tracking-number-highlight">${data.id}</span></p>
            <p><strong>Judul Proposal:</strong> ${data.judul}</p>
            <p><strong>Anggaran:</strong> ${formatCurrency(data.anggaran)}</p>
            <p style="background:#f3f4f6;padding:1rem;border-radius:6px;border-left: 3px solid var(--color-warning);">
                Proposal Anda telah diterima dan akan segera diulas. Gunakan nomor di atas untuk cek status.
            </p>
        </div>`;
    showModal('Pengajuan Proposal Diterima', body, [
        { text: 'Export PDF', className: 'btn btn--secondary', onClick: () => exportToPDF(data, 'pengajuan') },
        { text: 'Tutup', className: 'btn btn--primary', onClick: closeModal }
    ]);
}

function trackPengajuanStatus() {
    const trackingId = document.getElementById('statusInput').value.trim();
    const resultEl = document.getElementById('statusResult');
    if (!resultEl) return;

    if (!trackingId) {
        resultEl.style.display = 'block';
        resultEl.innerHTML = `<p class="error-text">Mohon masukkan nomor pengajuan.</p>`;
        return;
    }

    const submission = STATE.submissions.pengajuan.find(s => s.id === trackingId);
    
    if (submission) {
        let statusClass = '';
        let statusText = '';
        if (submission.status === 'menunggu') { statusClass = 'tracking-result__status--new'; statusText = 'Menunggu Review'; }
        else if (submission.status === 'review') { statusClass = 'tracking-result__status--processing'; statusText = 'Dalam Review'; }
        else if (submission.status === 'disetujui') { statusClass = 'tracking-result__status--completed'; statusText = 'Disetujui'; }
        else if (submission.status === 'ditolak') { statusClass = 'tracking-result__status--error'; statusText = 'Ditolak'; }
        
        resultEl.style.display = 'block';
        resultEl.innerHTML = `
            <h4>Status Pengajuan Ditemukan</h4>
            <p><strong>Nomor:</strong> ${submission.id}</p>
            <p><strong>Judul:</strong> ${submission.judul}</p>
            <p><strong>Anggaran:</strong> ${formatCurrency(submission.anggaran)}</p>
            <p><strong>Tanggal:</strong> ${formatDate(submission.tanggalPengajuan)}</p>
            <span class="tracking-result__status ${statusClass}">${statusText}</span>
        `;
    } else {
        resultEl.style.display = 'block';
        resultEl.innerHTML = `<p class="error-text">Nomor pengajuan <strong>${trackingId}</strong> tidak ditemukan.</p>`;
    }
}

// === FUNGSI BUKA DETAIL ARTIKEL (Popup) ===
function openArticleDetail(id) {
    // Cari artikel berdasarkan ID dari data STATE
    const article = STATE.articles.find(a => a.id === id);
    
    if (!article) {
        console.error("Artikel tidak ditemukan:", id);
        return;
    }

    // Konten HTML Popup
    const fullContent = `
        <div class="article-detail">
            <div class="article-detail__header">
                <div class="article-detail__image">${article.image}</div>
                <div class="article-detail__meta">
                    <span class="status-badge status-badge--info">${article.category}</span>
                    <span class="article-detail__date">${formatDate(article.date)}</span>
                </div>
                <h2 class="article-detail__title">${article.title}</h2>
            </div>
            <div class="article-detail__body">
                <p><strong>${article.excerpt}</strong></p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <p>Semoga informasi ini bermanfaat bagi seluruh warga Karang Taruna. Mari kita terus berkarya dan berkontribusi untuk lingkungan kita tercinta.</p>
            </div>
        </div>
    `;

    // Tampilkan Modal
    showModal('Detail Informasi', fullContent, [
        { text: 'Tutup', className: 'btn btn--secondary', onClick: closeModal }
    ]);
}

// ==================== Admin Panel (Tidak di-render di halaman utama) ====================

function initAdminPanel() {
    const loginForm = document.getElementById('adminLoginForm');
    if (!loginForm) return; // Guard clause
    
    // ... (Sisa fungsi admin)
}

// ... (Fungsi-fungsi admin lainnya: showAdminDetail, updateSubmissionStatus, dll.)


// ==================== Initialization ====================

document.addEventListener('DOMContentLoaded', () => {
    initNavigation();
    initModal();
    initHeroCarousel();
    
    // Panggil kedua fungsi artikel.
    // Hanya fungsi yang menemukan ID-nya yang akan berjalan.
    initBriefArticles(); // Akan jalan di index.html
    initArticles();      // Akan jalan di informasi.html
    
    initPengaduanFileUpload();
    initPengajuanFileUpload();
    initMediasiForm(); // Aman, akan di-skip
    initPengaduanForm();
    initPengajuanForm();
    initAdminPanel(); // Aman, akan di-skip
});