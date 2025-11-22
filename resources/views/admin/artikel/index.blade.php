@extends('layouts.admin')

@section('header', 'Kelola Informasi & Berita')

@section('content')
    @if(session('success'))
        <div style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #f8d7da; color: #842029; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <ul>
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Daftar Artikel</h3>
            <button class="btn-primary" onclick="openModal()">+ Tulis Berita Baru</button>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Judul & Info</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Link (Opsional)</th>
                        <th style="width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $a)
                    <tr>
                        <td style="vertical-align: middle;">
                            <img src="{{ asset($a->image) }}" style="width:60px; height:60px; object-fit:cover; border-radius:6px; border: 1px solid #eee;">
                        </td>

                        <td style="vertical-align: middle;">
                            <strong style="font-size: 14px; color: #333;">{{ $a->title }}</strong><br>
                            <small style="color:#666">Penulis: {{ $a->author }}</small>
                        </td>

                        <td style="vertical-align: middle;">
                            <span class="status-badge process">{{ $a->category }}</span>
                        </td>

                        <td style="vertical-align: middle;">
                            {{ \Carbon\Carbon::parse($a->published_at)->format('d M Y') }}
                        </td>

                        <td style="vertical-align: middle;">
                            @if($a->link)
                                <a href="{{ $a->link }}" target="_blank" style="color: #A50104; font-weight: 600; font-size: 12px;">Link ↗</a>
                            @else
                                <span style="color: #ccc;">-</span>
                            @endif
                        </td>

                        <td style="text-align: center; vertical-align: middle;">
                            <div style="display: flex; gap: 5px; justify-content: center; align-items: center;">

                                <button class="btn-action btn-edit"
                                    onclick="openEditModal({{ $a->id }}, '{{ $a->title }}', '{{ $a->category }}', `{{ $a->content }}`, '{{ $a->link }}')"
                                    title="Edit"
                                    style="display: flex; align-items: center; justify-content: center; padding: 6px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>

                                    @csrf @method('DELETE')
                                   <button class="btn-action btn-delete" title="Hapus"
                                        style="display: flex; align-items: center; justify-content: center; padding: 6px;"
                                        onclick="openGlobalDeleteModal('{{ url('/admin/artikel/'.$a->id) }}')">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:40px; color: #666;">
                            Belum ada artikel. Yuk nulis berita baru!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px;">{{ $articles->links() }}</div>
    </div>

    <div class="modal-overlay" id="crudModal">
        <div class="modal-box" style="max-width: 700px;">
            <h2 id="modalTitle" style="margin-bottom: 20px;">Tulis Berita Baru</h2>

            <form id="crudForm" action="{{ url('/admin/artikel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div> <div class="form-group">
                    <label>Judul Artikel</label>
                    <input type="text" name="title" id="inpTitle" class="form-input" required placeholder="Contoh: Lomba Panjat Pinang">
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category" id="inpCategory" class="form-input" required>
                        <option value="Berita">Berita</option>
                        <option value="Kegiatan">Kegiatan</option>
                        <option value="Pengumuman">Pengumuman</option>
                        <option value="Artikel">Artikel</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Isi Berita</label>
                    <textarea name="content" id="inpContent" class="form-input" rows="6" required placeholder="Tulis isi berita di sini..."></textarea>
                </div>

                <div class="form-group">
                <label>Link Pendaftaran / Info (Opsional)</label>
                <input type="url" name="link" id="inpLink" class="form-input" placeholder="https://google.com">
                <small style="color:#888; font-size:11px;">Kosongkan jika tidak ada link eksternal.</small>
            </div>

            <div class="form-group">
                <label style="font-weight: 600; margin-bottom: 8px; display: block;">Upload Cover Artikel</label>

                <div class="upload-box-wrapper">
                    <div class="upload-box" style="height: 120px;">
                        <div class="upload-box-icon" style="font-size: 24px;">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                        </div>
                        <p>Klik untuk upload cover</p>

                        <input type="file" name="image" id="artikelInput" class="upload-input-hidden" accept="image/*" onchange="previewImages(this, 'artikelPreview')">
                    </div>

                    <div class="preview-container" id="artikelPreview"></div>
                </div>
            </div>



                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Artikel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('crudModal');
        const form = document.getElementById('crudForm');
        const titleEl = document.getElementById('modalTitle');
        const methodField = document.getElementById('methodField');

        // Mode Tambah
        function openModal() {
            form.action = "{{ url('/admin/artikel') }}"; // Reset URL ke store
            form.reset(); // Kosongkan form
            methodField.innerHTML = ''; // Hapus method PUT
            titleEl.innerText = 'Tulis Berita Baru';
            modal.style.display = 'flex';
        }

        // Mode Edit (Isi data lama ke form)
        function openEditModal(id, title, category, content, link) {
            form.action = "{{ url('/admin/artikel') }}/" + id; // Ubah URL ke update
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">'; // Tambah method PUT

            document.getElementById('inpTitle').value = title;
            document.getElementById('inpCategory').value = category;
            document.getElementById('inpContent').value = content;
            document.getElementById('inpLink').value = link || '';

            titleEl.innerText = 'Edit Artikel';
            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        // Tutup kalau klik background luar
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        // Fungsi Preview Gambar (Sama kayak galeri)
        function previewImages(input, containerId) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    container.innerHTML = `<div class="preview-item" style="width:100%; height:auto; max-height:150px;"><img src="${e.target.result}"></div>`;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
