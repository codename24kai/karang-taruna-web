@extends('layouts.admin')

@section('header', 'Pengaturan Website')

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

    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Informasi Umum</h3>
        </div>

        <form action="{{ url('/admin/pengaturan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Website</label>
                <input type="text" name="site_name" class="form-input" value="{{ $settings['site_name'] ?? '' }}">
            </div>
            <div class="form-group">
                <label>Alamat Sekretariat</label>
                <textarea name="site_address" class="form-input" rows="2">{{ $settings['site_address'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                <label>Email Resmi</label>
                <input type="email" name="site_email" class="form-input" value="{{ $settings['site_email'] ?? '' }}">
            </div>
            <div class="form-group">
                <label>Username TikTok</label>
                <input type="text" name="site_phone" class="form-input" value="{{ $settings['site_phone'] ?? '' }}">
            </div>
            <div class="form-group">
                <label>Username Instagram</label>
                <input type="text" name="instagram" class="form-input" value="{{ $settings['instagram'] ?? '' }}">
            </div>

            <div style="text-align: right; margin-top: 20px;">
                <button type="submit" class="btn-primary">Simpan Informasi</button>
            </div>
        </form>
    </div>

    <div class="content-card" style="margin-top: 30px;">
        <div class="content-card-header">
            <h3 class="content-card-title">Pengaturan Hero Carousel</h3>
        </div>

        <form action="{{ url('/admin/pengaturan/hero') }}" method="POST" enctype="multipart/form-data" style="background: #f9fafb; padding: 20px; border-radius: 8px; border: 1px dashed #ccc; margin-bottom: 20px;">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="font-weight: 600; margin-bottom: 8px; display: block;">Upload Banner (Bisa Banyak)</label>

                    <div class="upload-box-wrapper">
                        <div class="upload-box" style="height: 150px;">
                            <div class="upload-box-icon">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            </div>
                            <p>Klik atau drag foto ke sini</p>

                            <input type="file" name="images[]" id="heroInput" class="upload-input-hidden" accept="image/*" multiple required onchange="previewImages(this, 'heroPreview')">
                        </div>

                        <div class="preview-container" id="heroPreview" style="margin-top: 10px;"></div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; justify-content: center;">
                    <div class="form-group">
                        <label>Caption Slide (Opsional)</label>
                        <input type="text" name="caption" class="form-input" placeholder="Contoh: Dokumentasi Kegiatan">
                        <small style="color: #888; display: block; margin-top: 5px;">Caption ini berlaku untuk semua foto yang diupload bareng.</small>
                    </div>
                    <button type="submit" class="btn-primary" style="width: 100%; margin-top: 10px;">+ Upload Semua Slide</button>
                </div>
            </div>
        </form>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h4 style="margin: 0; font-size: 14px; color: #333;">Daftar Slide Aktif</h4>

           @if($hero_slides->count() > 0)
            <button type="button" class="btn-delete-all"
                onclick="openGlobalDeleteModal('{{ url('/admin/pengaturan/hero-actions/delete-all') }}')"> <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                Hapus Semua
            </button>
            @endif
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
            @forelse($hero_slides as $slide)
                <div style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <div style="height: 140px; overflow: hidden; position: relative;">
                        <img src="{{ asset($slide->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>

                    <div style="padding: 12px;">
                        <p style="font-size: 12px; color: #333; font-weight: 600; margin-bottom: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $slide->caption ?? 'Tanpa Caption' }}
                        </p>

                        <div style="display: flex; gap: 5px;">
                            <button class="btn-edit" onclick="openHeroEditModal({{ $slide->id }}, '{{ $slide->caption }}')" style="flex: 1; padding: 6px; font-size: 12px; display: flex; align-items: center; justify-content: center; gap: 4px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                Edit
                            </button>

                            <button class="btn-delete" onclick="openGlobalDeleteModal('{{ url('/admin/pengaturan/hero/'.$slide->id) }}')" style="flex: 1; padding: 6px; font-size: 12px; display: flex; align-items: center; justify-content: center; gap: 4px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 30px; background: #f9fafb; border-radius: 8px; border: 1px dashed #ccc;">
                    <p style="color: #666; font-style: italic;">Belum ada slide yang diupload.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="modal-overlay" id="heroEditModal">
        <div class="modal-box" style="max-width: 500px;">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                <h2 style="margin: 0; font-size: 18px;">Edit Slide</h2>
                <button onclick="document.getElementById('heroEditModal').style.display='none'" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
            </div>

            <form id="heroEditForm" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Caption Slide</label>
                    <input type="text" name="caption" id="heroEditCaption" class="form-input" placeholder="Masukkan caption baru">
                </div>

                <div class="modal-footer" style="text-align: right; margin-top: 20px;">
                    <button type="button" class="btn-secondary" onclick="document.getElementById('heroEditModal').style.display='none'">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Logic Modal Edit
        function openHeroEditModal(id, caption) {
            const modal = document.getElementById('heroEditModal');
            const form = document.getElementById('heroEditForm');
            const input = document.getElementById('heroEditCaption');

            form.action = '/admin/pengaturan/hero/' + id;
            input.value = caption !== 'null' ? caption : '';

            modal.style.display = 'flex';
        }

        // Tutup modal kalau klik luar
        window.onclick = function(e) {
            const modal = document.getElementById('heroEditModal');
            if (e.target === modal) modal.style.display = 'none';
        }
    </script>
@endsection
