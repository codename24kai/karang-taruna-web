@extends('layouts.admin')

@section('header', 'Manajemen Pengguna')

@section('content')
    @if(session('success'))
        <div style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 8px; margin-bottom: 20px;">{{ session('success') }}</div>
    @endif

    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Daftar Admin</h3>
            <button class="btn-primary" onclick="openModal()">+ Tambah Admin</button>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td><span class="status-badge process">{{ ucfirst($user->role) }}</span></td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td style="text-align:center;">
                        <button class="btn-action btn-edit" onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->username }}', '{{ $user->role }}')">Edit</button>
                        <form action="{{ url('/admin/pengguna/'.$user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus user ini?')">
                            @csrf @method('DELETE')
                            <button class="btn-action btn-delete" onclick="openGlobalDeleteModal('{{ url('/admin/pengguna/'.$user->id) }}')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal-overlay" id="userModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div class="modal-box" style="background: white; padding: 25px; border-radius: 12px; width: 500px;">
            <h2 id="modalTitle" style="margin-bottom: 20px;">Tambah Admin</h2>
            <form id="userForm" action="{{ url('/admin/pengguna') }}" method="POST">
                @csrf
                <div id="methodField"></div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" id="inpName" class="form-input" required>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="inpUsername" class="form-input" required>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" id="inpRole" class="form-input">
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password <small id="passHint" style="color:red; display:none;">(Kosongkan jika tidak ingin ganti)</small></label>
                    <input type="password" name="password" id="inpPassword" class="form-input" placeholder="Min. 6 karakter">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="document.getElementById('userModal').style.display='none'">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('userForm').action = "{{ url('/admin/pengguna') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('userForm').reset();
            document.getElementById('modalTitle').innerText = 'Tambah Admin';
            document.getElementById('passHint').style.display = 'none';
            document.getElementById('inpPassword').required = true;
            document.getElementById('userModal').style.display = 'flex';
        }

        function openEditModal(id, name, username, role) {
            document.getElementById('userForm').action = "{{ url('/admin/pengguna') }}/" + id;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('inpName').value = name;
            document.getElementById('inpUsername').value = username;
            document.getElementById('inpRole').value = role;

            document.getElementById('modalTitle').innerText = 'Edit Admin';
            document.getElementById('passHint').style.display = 'inline';
            document.getElementById('inpPassword').required = false;
            document.getElementById('userModal').style.display = 'flex';
        }
    </script>
@endsection
