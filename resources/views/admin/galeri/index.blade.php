@extends('layouts.admin')

@section('header', 'Kelola Galeri')

@section('content')
    @if(session('success'))
        <div style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #badbcc;">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background: #f8d7da; color: #842029; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c2c7;">
            <ul>@foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="content-card" style="margin-bottom: 30px;">
        <div class="content-card-header" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center;" onclick="toggleUploadForm()">
            <h3 class="content-card-title">+ Tambah Kegiatan Baru</h3>
            <span id="toggleIcon">▼</span>
        </div>
        <div id="uploadFormContainer" style="display: none; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
            <form action="{{ url('/admin/galeri') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Nama Kegiatan / Caption</label>
                    <input type="text" name="caption" class="form-input" required placeholder="Contoh: Kerja Bakti RT 01">
                </div>
                <div class="form-group">
                    <label>Tanggal Kegiatan</label>
                    <input type="date" name="date" class="form-input" required>
                </div>
                <div class="form-group">
                    <label style="font-weight: 600; margin-bottom: 8px; display: block;">Upload Foto (Bisa Banyak)</label>

                    <div class="upload-box-wrapper">
                        <div class="upload-box">
                            <div class="upload-box-icon">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            </div>
                            <p>Klik untuk upload banyak foto sekaligus</p>

                            <input type="file" name="images[]" id="galeriInput" class="upload-input-hidden" multiple accept="image/*" onchange="previewImages(this, 'galeriPreview')">
                        </div>

                        <div class="preview-container" id="galeriPreview"></div>
                    </div>
                </div>
                <div style="text-align: right;">
                    <button type="submit" class="btn-primary">Upload Kegiatan</button>
                </div>
            </form>
        </div>
    </div>

    <h3 style="margin-bottom: 20px;">Album Terpublish</h3>
    <div class="gallery-preview-grid">
        @forelse($galleries as $item)
            @php
                $imgs = json_decode($item->images);
                $cover = $imgs[0] ?? 'https://via.placeholder.com/300';
                $count = count($imgs);
            @endphp

            <div class="gallery-admin-card">
                <div class="gallery-card-image-wrap">
                    <img src="{{ asset($cover) }}" alt="{{ $item->caption }}">
                    <div class="photo-count-badge">📷 {{ $count }}</div>

                    <div class="gallery-hover-details">
                        <h4>{{ Str::limit($item->caption, 30) }}</h4>
                        <p>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</p>
                        <p style="font-size: 11px; margin-top: 5px;">{{ $count }} Foto</p>
                    </div>
                </div>

                <div class="gallery-card-actions">
                    <div style="font-weight:600; font-size:14px; margin-bottom:10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $item->caption }}
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button class="btn-edit edit-btn"
                            data-id="{{ $item->id }}"
                            data-json="{{ json_encode($item) }}"
                            style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 5px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            Edit
                        </button>

                        <button class="btn-delete delete-btn"
                            data-id="{{ $item->id }}"
                            data-caption="{{ $item->caption }}"
                            style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 5px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 40px; background: #fff; border-radius: 12px; border: 1px dashed #ccc;">
                <p style="color: #666; margin-bottom: 10px;">Belum ada album galeri.</p>
                <button class="btn-secondary" onclick="toggleUploadForm()">+ Buat Album Pertama</button>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 20px;">{{ $galleries->links() }}</div>

    <div class="modal-overlay" id="editModal">
        <div class="modal-box" style="max-width: 600px;">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">Edit Album</h2>
                <button onclick="closeEditModal()" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="caption" id="modalCaption" class="form-input" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Kegiatan</label>
                    <input type="date" name="date" id="modalDate" class="form-input" required>
                </div>

                <div class="form-group">
                    <label>Foto Saat Ini (<span id="modalCount">0</span> file)</label>
                    <div id="modalPreviewGrid" style="display: flex; gap: 8px; overflow-x: auto; padding: 10px; background: #f9fafb; border-radius: 8px; border: 1px solid #eee;">
                        </div>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label style="color: #A50104; font-weight: 600;">+ Tambah Foto Baru</label>
                    <input type="file" name="new_images[]" class="form-input" multiple accept="image/*">
                    <small style="color: #666;">Foto baru akan ditambahkan ke album ini.</small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box" style="max-width: 400px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #fee2e2; color: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; font-size: 24px;">🗑️</div>
            <h3 style="margin-bottom: 10px;">Hapus Album Ini?</h3>
            <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Album "<strong id="deleteCaption"></strong>" akan dihapus permanen.</p>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button type="button" class="btn-secondary" onclick="closeDeleteModal()">Batal</button>
                    <button type="submit" class="btn-delete">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .gallery-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 24px;
        }

        .gallery-admin-card {
            background: var(--white); /* GANTI 'white' JADI VARIABLE */
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid var(--border); /* GANTI '#eee' JADI VARIABLE */
            transition: transform 0.2s;
        }

        .gallery-admin-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .gallery-card-image-wrap {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        .gallery-card-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .photo-count-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.6);
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            backdrop-filter: blur(4px);
        }

        .gallery-hover-details {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(165, 1, 4, 0.9);
            color: white; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;
            opacity: 0; transition: opacity 0.3s; padding: 20px;
        }

        .gallery-card-image-wrap:hover .gallery-hover-details { opacity: 1; }
        .gallery-card-image-wrap:hover img { transform: scale(1.1); }

        .gallery-card-actions {
            padding: 15px;
            background: var(--white); /* GANTI '#fff' JADI VARIABLE */
            border-top: 1px solid var(--border); /* GANTI '#f0f0f0' JADI VARIABLE */
        }

        /* Modal Styles juga disesuaikan */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; }
        .modal-box {
            background: var(--white); /* FIX DARK MODE */
            color: var(--text-dark); /* FIX TEXT COLOR */
            padding: 25px;
            border-radius: 12px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            border: 1px solid var(--border);
        }
    </style>

    <script>
        function toggleUploadForm() {
            const form = document.getElementById('uploadFormContainer');
            const icon = document.getElementById('toggleIcon');
            if (form.style.display === 'none') {
                form.style.display = 'block';
                icon.innerText = '▲';
            } else {
                form.style.display = 'none';
                icon.innerText = '▼';
            }
        }

        // Event Listener biar JS nya gak bingung
        document.addEventListener('DOMContentLoaded', function() {

            // TOMBOL EDIT
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const item = JSON.parse(this.getAttribute('data-json'));
                    const baseUrl = "{{ asset('') }}";
                    const images = JSON.parse(item.images);

                    document.getElementById('editForm').action = '/admin/galeri/' + item.id;
                    document.getElementById('modalCaption').value = item.caption;
                    document.getElementById('modalDate').value = item.date;
                    document.getElementById('modalCount').innerText = images.length;

                    const previewGrid = document.getElementById('modalPreviewGrid');
                    previewGrid.innerHTML = '';
                    images.forEach(img => {
                        previewGrid.innerHTML += `<a href="${baseUrl + img}" target="_blank" style="flex-shrink:0;"><img src="${baseUrl + img}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;"></a>`;
                    });

                    document.getElementById('editModal').style.display = 'flex';
                });
            });

            // TOMBOL DELETE
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const caption = this.getAttribute('data-caption');
                    document.getElementById('deleteForm').action = '/admin/galeri/' + id;
                    document.getElementById('deleteCaption').innerText = caption;
                    document.getElementById('deleteModal').style.display = 'flex';
                });
            });
        });

        function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }
        function closeDeleteModal() { document.getElementById('deleteModal').style.display = 'none'; }

        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) closeEditModal();
            if (event.target == document.getElementById('deleteModal')) closeDeleteModal();
        }

        function previewImages(input, containerId) {
            const container = document.getElementById(containerId);
            container.innerHTML = ''; // Bersihkan preview lama

            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'preview-item';
                        div.innerHTML = `<img src="${e.target.result}">`;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
@endsection
