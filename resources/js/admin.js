// =========================================
// 1. LOGIC SIDEBAR MOBILE
// =========================================
const sidebar = document.getElementById('sidebar');
const mobileMenuToggle = document.getElementById('mobileMenuToggle');

if (mobileMenuToggle) {
    mobileMenuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });
}

document.addEventListener('click', function(event) {
    if (window.innerWidth <= 768) {
        if (sidebar && mobileMenuToggle && !sidebar.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
            sidebar.classList.remove('active');
        }
    }
});

// =========================================
// 2. LOGIC NOTIFIKASI PINTAR (CLIENT-SIDE FILTER)
// =========================================

let currentNotifs = []; // Simpan data notif di memori

// Toggle Dropdown
window.toggleNotifications = function(e) {
    e.stopPropagation();
    const dropdown = document.getElementById('notificationDropdown');
    if(dropdown) {
        // Tutup modal profil kalo lagi buka
        if(document.getElementById('profileModal')) document.getElementById('profileModal').style.display = 'none';

        dropdown.classList.toggle('show');

        // Kalau dibuka, refresh data biar update
        if (dropdown.classList.contains('show')) {
            fetchNotifications();
        }
    }
}

// Fetch Data dari API
async function fetchNotifications() {
    try {
        const response = await fetch('/admin/api/notifications');
        const result = await response.json();

        // Simpan data mentah dari server
        currentNotifs = result.data;

        // Filter: Hanya tampilkan yang BELUM ada di localStorage
        const unreadNotifs = filterUnread(result.data);

        updateNotificationUI(unreadNotifs);
    } catch (error) {
        console.error('Gagal ambil notif:', error);
    }
}

// Logic Filter: Cek LocalStorage
function filterUnread(data) {
    // Ambil daftar ID yang sudah dibaca dari LocalStorage
    const readIds = JSON.parse(localStorage.getItem('read_notifications') || '[]');

    // Filter data server: Ambil yang ID-nya TIDAK ADA di daftar readIds
    return data.filter(item => !readIds.includes(item.id));
}

// Update Tampilan HTML
function updateNotificationUI(items) {
    const badge = document.getElementById('notifBadge');
    const list = document.querySelector('.notif-list');
    const total = items.length;

    // A. Update Badge Angka
    if (badge) {
        if (total > 0) {
            badge.style.display = 'block'; // Pakai block biar bulet
            badge.innerText = total;
        } else {
            badge.style.display = 'none';
        }
    }

    // B. Update List Notifikasi
    // Cuma update list kalau dropdown lagi GAK dibuka user (biar gak kedip pas lagi baca)
    // ATAU kalau listnya kosong (initial load)
    const dropdown = document.getElementById('notificationDropdown');

    if (list && (!dropdown.classList.contains('show') || list.innerHTML.includes('Memuat'))) {
        if (total === 0) {
            list.innerHTML = '<div style="padding: 20px; text-align: center; color: #888;">Tidak ada notifikasi baru.</div>';
        } else {
            let html = '';
            items.forEach(item => {
                html += `
                    <div onclick="window.location.href='${item.url}'" style="padding: 12px 15px; border-bottom: 1px solid #f5f5f5; cursor: pointer; display: flex; gap: 10px; align-items: start;">
                        <div style="width: 30px; height: 30px; background: ${item.bg}; color: ${item.color}; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 16px;">
                            ${item.icon}
                        </div>
                        <div>
                            <div style="font-size: 13px; font-weight: 600; color: #333; margin-bottom: 2px;">${item.title}</div>
                            <div style="font-size: 11px; color: #666; line-height: 1.3;">${item.desc}</div>
                        </div>
                    </div>
                `;
            });
            list.innerHTML = html;
        }
    }
}

// FUNGSI CLEAR ALL (Simpan ID ke LocalStorage)
window.markAllRead = function() {
    // 1. Ambil semua ID dari notifikasi yang sekarang tampil
    const readIds = JSON.parse(localStorage.getItem('read_notifications') || '[]');

    // 2. Masukkan ID notifikasi saat ini ke daftar "Sudah Dibaca"
    currentNotifs.forEach(item => {
        if (!readIds.includes(item.id)) {
            readIds.push(item.id);
        }
    });

    // 3. Simpan balik ke LocalStorage
    localStorage.setItem('read_notifications', JSON.stringify(readIds));

    // 4. Update UI Langsung (Jadi 0)
    updateNotificationUI([]);
}

// Auto Polling Tiap 5 Detik
setInterval(fetchNotifications, 5000);


// =========================================
// 3. LOGIC MODAL (PROFIL & LOGOUT)
// =========================================

window.openProfileModal = function() {
    const notif = document.getElementById('notificationDropdown');
    if(notif) notif.classList.remove('show');
    document.getElementById('profileModal').style.display = 'flex';
}
window.closeProfileModal = function() {
    document.getElementById('profileModal').style.display = 'none';
}

window.openLogoutModal = function(e) {
    if(e) e.preventDefault();
    document.getElementById('logoutModal').style.display = 'flex';
}
window.closeLogoutModal = function() {
    document.getElementById('logoutModal').style.display = 'none';
}

// =========================================
// 5. LOGIC UPLOAD & PREVIEW (GLOBAL)
// =========================================

// Fungsi Preview Gambar (Support Multiple & Single)
window.previewImages = function(input, containerId) {
    const container = document.getElementById(containerId);
    const files = Array.from(input.files);

    container.innerHTML = ''; // Reset preview lama

    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'preview-item';
            // Styling inline biar aman
            div.style.width = '80px';
            div.style.height = '80px';

            div.innerHTML = `
                <img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover; border-radius:8px;">
                <button type="button" class="btn-remove-preview"
                    onclick="removeFile('${input.id}', ${index}, '${containerId}')">&times;</button>
            `;
            container.appendChild(div);
        }
        reader.readAsDataURL(file);
    });
}

    // Fungsi Hapus File Tertentu
    window.removeFile = function(inputId, indexToRemove, containerId) {
        const input = document.getElementById(inputId);
        const dt = new DataTransfer(); // Object ajaib buat manipulasi file input
        const { files } = input;

        // Loop semua file, masukkan ke DT kecuali yang mau dihapus
        for (let i = 0; i < files.length; i++) {
            if (i !== indexToRemove) {
                dt.items.add(files[i]);
            }
        }

        input.files = dt.files; // Update input aslinya

        // Render ulang preview biar urutannya bener lagi
        previewImages(input, containerId);
    }

    // =========================================
// 5. LOGIC HAPUS GLOBAL (Untuk Semua Halaman)
// =========================================

window.openGlobalDeleteModal = function(actionUrl) {
    const form = document.getElementById('globalDeleteForm');
    const modal = document.getElementById('globalDeleteModal');

    // Set URL tujuan hapus (misal: /admin/artikel/1)
    form.action = actionUrl;

    // Tampilkan Modal
    modal.style.display = 'flex';
}

window.closeGlobalDeleteModal = function() {
    document.getElementById('globalDeleteModal').style.display = 'none';
}

// Update Event Listener Global (tambahkan penutup modal delete)
window.addEventListener('click', function(e) {
    // ... kode lama ...

    const globalDelete = document.getElementById('globalDeleteModal');
    if (e.target === globalDelete) window.closeGlobalDeleteModal();
});

// =========================================
// 5. LOGIC DARK MODE ADMIN
// =========================================
const adminThemeToggle = document.getElementById('adminThemeToggle');
const htmlRoot = document.documentElement;

// Cek LocalStorage pas load
if (localStorage.getItem('admin_theme') === 'dark') {
    htmlRoot.setAttribute('data-theme', 'dark');
    if(adminThemeToggle) adminThemeToggle.innerText = '☀️';
}

// Fungsi Toggle Global
window.toggleAdminTheme = function() {
    const btn = document.getElementById('adminThemeToggle');
    if (htmlRoot.getAttribute('data-theme') === 'dark') {
        htmlRoot.removeAttribute('data-theme');
        localStorage.setItem('admin_theme', 'light');
        if(btn) btn.innerText = '🌙';
    } else {
        htmlRoot.setAttribute('data-theme', 'dark');
        localStorage.setItem('admin_theme', 'dark');
        if(btn) btn.innerText = '☀️';
    }
}

// =========================================
// 4. GLOBAL CLICK LISTENER
// =========================================
window.addEventListener('click', function(e) {
    const dropdown = document.getElementById('notificationDropdown');
    const wrapper = document.querySelector('.notification-wrapper');
    if (dropdown && dropdown.classList.contains('show') && wrapper && !wrapper.contains(e.target)) {
        dropdown.classList.remove('show');
    }

    const logoutModal = document.getElementById('logoutModal');
    const profileModal = document.getElementById('profileModal');
    if (e.target === logoutModal) window.closeLogoutModal();
    if (e.target === profileModal) window.closeProfileModal();
});

document.addEventListener('DOMContentLoaded', fetchNotifications);
