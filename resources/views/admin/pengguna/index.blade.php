@extends('layouts.admin')

@section('header', 'Manajemen Pengguna')

@section('content')
    @if(session('success'))
        <div style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #badbcc;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div style="background: #f8d7da; color: #842029; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c2c7;">
            <ul>@foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Daftar Admin</h3>
            <button class="btn-primary" onclick="openModal()">+ Tambah Admin</button>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                        <th style="text-align:center; width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->username }}</td>
                        <td>
                            <span class="status-badge {{ $user->role == 'super_admin' ? 'process' : 'pending' }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td style="text-align:center;">
                            <div style="display: flex; gap: 5px; justify-content: center;">
                                <button class="btn-action btn-edit" onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->username }}', '{{ $user->role }}')" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>
                                <button class="btn-action btn-delete" onclick="openGlobalDeleteModal('{{ url('/admin/pengguna/'.$user->id) }}')" title="Hapus">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal-overlay" id="userModal">
        <div class="modal-box" style="max-width: 500px;">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                <h2 id="modalTitle" style="margin: 0; font-size: 18px;">Tambah Admin</h2>
                <button onclick="document.getElementById('userModal').style.display='none'" style="background:none; border:none; font-size:24px; cursor:pointer; color:#666;">&times;</button>
            </div>

            <form id="userForm" action="{{ url('/admin/pengguna') }}" method="POST">
                @csrf
                <div id="methodField"></div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" id="inpName" class="form-input" required placeholder="Contoh: Budi Santoso">
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="inpUsername" class="form-input" required placeholder="username_login">
                </div>

                <div class="form-group">
                    <label>Role (Hak Akses)</label>
                    <select name="role" id="inpRole" class="form-input">
                        <option value="admin">Admin Biasa</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>

                <div class="form-group" style="background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px dashed #ddd;">
                    <label>Password <small id="passHint" style="color: #d97706; display:none;">(Isi hanya jika ingin mengganti)</small></label>
                    <input type="password" name="password" id="inpPassword" class="form-input" placeholder="Minimal 6 karakter">
                </div>

                <div class="modal-footer" style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; text-align: right;">
                    <button type="button" class="btn-secondary" onclick="document.getElementById('userModal').style.display='none'">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('userForm').action = "{{ url('/admin/pengguna') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('userForm').reset();

            document.getElementById('modalTitle').innerText = 'Tambah Admin Baru';
            document.getElementById('passHint').style.display = 'none';
            document.getElementById('inpPassword').required = true;
            document.getElementById('inpPassword').placeholder = "Minimal 6 karakter";

            document.getElementById('userModal').style.display = 'flex';
        }

        function openEditModal(id, name, username, role) {
            document.getElementById('userForm').action = "{{ url('/admin/pengguna') }}/" + id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('inpName').value = name;
            document.getElementById('inpUsername').value = username;
            document.getElementById('inpRole').value = role;

            document.getElementById('modalTitle').innerText = 'Edit Data Admin';
            document.getElementById('passHint').style.display = 'inline';
            document.getElementById('inpPassword').required = false;
            document.getElementById('inpPassword').placeholder = "Kosongkan jika password tetap";

            document.getElementById('userModal').style.display = 'flex';
        }

        // Tutup kalau klik luar
        window.onclick = function(event) {
            const modal = document.getElementById('userModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <style>
        /* Paksa Style Gelap untuk Elemen Spesifik di Halaman Ini */
        [data-theme="dark"] .form-group[style*="background: #f9fafb"] {
            background-color: #1f2937 !important; /* Gelap */
            border-color: #374151 !important;
        }

        [data-theme="dark"] .modal-header,
        [data-theme="dark"] .modal-footer {
            border-color: #374151 !important;
        }
    </style>
@endsection
