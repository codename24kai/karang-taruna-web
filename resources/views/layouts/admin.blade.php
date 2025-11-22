<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Karang Taruna</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo-karang-taruna.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/admin.css', 'resources/js/admin.js'])

    <style>
        /* === FIX CSS DARURAT UNTUK LAYOUT & NOTIFIKASI === */

        /* Pastikan Topbar Rapi */
        .topbar {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            position: relative;
            z-index: 100;
        }

        .topbar-right {
            display: flex !important;
            align-items: center !important;
            gap: 20px !important;
        }

        /* Fix Posisi Wrapper Notifikasi */
        .notification-wrapper {
            position: relative !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        /* Fix Dropdown Notifikasi */
        .notification-dropdown {
            display: none;
            position: absolute !important;
            top: 45px !important;
            right: -10px !important;
            width: 320px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            z-index: 9999 !important;
            overflow: hidden;
        }

        .notification-dropdown.show {
            display: block !important;
            animation: slideDown 0.2s ease-out;
        }

        /* Badge Merah */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
            border: 2px solid white;
        }

        /* KHUSUS MOBILE */
        @media (max-width: 768px) {
            .notification-dropdown {
                left: -10px !important;
                width: 280px;
            }
        }

        /* Style Item Notif */
        .notif-header { padding: 15px; border-bottom: 1px solid #f0f0f0; display:flex; justify-content:space-between; align-items:center; background:#fff; }
        .notif-list { max-height: 300px; overflow-y: auto; background:#fff; }
        .notif-item { display: flex; gap: 15px; padding: 15px; border-bottom: 1px solid #f5f5f5; cursor: pointer; transition: 0.2s; align-items: start; }
        .notif-item:hover { background-color: #f9fafb; }
        .notif-icon { width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 18px; }
        .notif-text p { margin: 0 0 5px 0; font-size: 13px; font-weight: 600; color: #333; }
        .notif-text span { font-size: 12px; color: #666; line-height: 1.4; display: block; }

        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <img src="{{ asset('assets/img/logo-karang-taruna.svg') }}" alt="Logo">
            </div>
            <div class="sidebar-logo-text">
                <h2>Karang Taruna</h2>
                <p>Admin Portal</p>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li class="sidebar-menu-item"><a href="{{ url('/dashboard') }}" class="sidebar-menu-link {{ request()->is('dashboard') ? 'active' : '' }}"><svg class="sidebar-menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>Dashboard</a></li>
            <li class="sidebar-menu-item"><a href="{{ url('/admin/pengaduan') }}" class="sidebar-menu-link {{ request()->is('admin/pengaduan*') ? 'active' : '' }}"><svg class="sidebar-menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>Pengaduan</a></li>
            <li class="sidebar-menu-item"><a href="{{ url('/admin/proposal') }}" class="sidebar-menu-link {{ request()->is('admin/proposal*') ? 'active' : '' }}"><svg class="sidebar-menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>Proposal</a></li>
            <li class="sidebar-menu-item"><a href="{{ url('/admin/galeri') }}" class="sidebar-menu-link {{ request()->is('admin/galeri*') ? 'active' : '' }}"><svg class="sidebar-menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>Galeri</a></li>
            <li class="sidebar-menu-item"><a href="{{ url('/admin/artikel') }}" class="sidebar-menu-link {{ request()->is('admin/artikel*') ? 'active' : '' }}"><svg class="sidebar-menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>Artikel</a></li>
            <li class="sidebar-menu-item"><a href="{{ url('/admin/pengguna') }}" class="sidebar-menu-link {{ request()->is('admin/pengguna*') ? 'active' : '' }}"><svg class="sidebar-menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Pengguna</a></li>
            <li class="sidebar-menu-item"><a href="{{ url('/admin/pengaturan') }}" class="sidebar-menu-link {{ request()->is('admin/pengaturan*') ? 'active' : '' }}"><svg class="sidebar-menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l-.06.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>Pengaturan</a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="#" onclick="openLogoutModal(event)" class="sidebar-menu-link">
                <svg class="sidebar-menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Logout
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <h1>@yield('header', 'Dashboard')</h1>
            </div>

            <div class="topbar-right">
                <button id="adminThemeToggle" onclick="toggleAdminTheme()" style="background:none; border:none; font-size:20px; cursor:pointer; margin-right:15px;">🌙</button>
                <div class="notification-wrapper">
                    <div class="topbar-notifications" onclick="toggleNotifications(event)">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                        <span id="notifBadge" class="notification-badge" style="display: none;">0</span>
                    </div>

                    <div id="notificationDropdown" class="notification-dropdown">
                        <div class="notif-header">
                            <h4>Notifikasi</h4>
                            <span style="font-size:12px; cursor:pointer; color:#A50104; font-weight:600;" onclick="markAllRead()">Tandai Terbaca</span>
                        </div>
                        <div class="notif-list">
                            <div style="padding: 20px; text-align: center; color: #888;">Memuat notifikasi...</div>
                        </div>
                    </div>
                </div>

                <div class="topbar-user" onclick="openProfileModal()">
                    <div class="user-avatar">{{ substr(Auth::user()->name, 0, 2) }}</div>
                    <div class="user-info">
                        <div class="user-name" style="font-weight:bold; font-size:14px;">{{ Auth::user()->name }}</div>
                        <div class="user-role" style="font-size:12px; color:#666;">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>
                </div>
            </div>
        </div>

        @yield('content')
    </main>

    <div class="modal-overlay" id="logoutModal">
        <div class="modal-box logout-box">
            <div class="logout-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            </div>
            <h3>Konfirmasi Logout</h3>
            <p>Yakin mau keluar dari halaman admin?</p>
            <div class="modal-footer logout-footer">
                <button class="btn-secondary" onclick="closeLogoutModal()">Batal</button>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-delete">Ya, Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="profileModal">
        <div class="modal-box" style="width: 400px; max-width: 90%; padding: 0; overflow: hidden;">
            <div style="background: linear-gradient(135deg, #A50104, #7A0103); padding: 30px; text-align: center; color: white;">
                <div style="width: 80px; height: 80px; background: white; color: #A50104; border-radius: 50%; font-size: 28px; font-weight: bold; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <h3 style="margin:0; font-size:18px;">{{ Auth::user()->name }}</h3>
                <p style="margin:5px 0 0; font-size:13px; opacity:0.9;">{{ ucfirst(Auth::user()->role) }}</p>
            </div>

            <div style="padding: 20px;">
                <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #eee;">
                    <span style="color:#666;">Username</span>
                    <span style="font-weight:600;">{{ Auth::user()->username }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #eee;">
                    <span style="color:#666;">Status</span>
                    <span class="status-badge process">Active</span>
                </div>
                <div style="display:flex; justify-content:space-between; padding:10px 0;">
                    <span style="color:#666;">Bergabung</span>
                    <span style="font-weight:600;">{{ Auth::user()->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <div style="padding: 15px 20px; background: #f8f9fa; border-top: 1px solid #eee; display: flex; justify-content: space-between;">
                <button class="btn-secondary" onclick="closeProfileModal()" style="padding: 8px 16px; font-size: 13px;">Tutup</button>
                <button class="btn-delete" onclick="openLogoutModal(event); closeProfileModal();" style="padding: 8px 16px; font-size: 13px;">Logout</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="globalDeleteModal" style="z-index: 99999;">
        <div class="modal-box" style="max-width: 400px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #fee2e2; color: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; font-size: 24px;">
                🗑️
            </div>
            <h3 style="margin-bottom: 10px;">Hapus Data Ini?</h3>
            <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Data yang dihapus tidak dapat dikembalikan. Yakin ingin melanjutkan?</p>

            <form id="globalDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button type="button" class="btn-secondary" onclick="closeGlobalDeleteModal()">Batal</button>
                    <button type="submit" class="btn-delete">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
    </button>
</body>
</html>
