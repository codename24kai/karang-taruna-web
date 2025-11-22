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
                <label>Nomor Telepon / WA</label>
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
            <h3 class="content-card-title">Pengaturan Hero Carousel (Halaman Depan)</h3>
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
                            <p>Klik atau drag foto-foto banner ke sini</p>

                            <input type="file" name="images[]" id="heroInput" class="upload-input-hidden" accept="image/*" multiple required onchange="previewHero(this)">
                        </div>

                        <div class="preview-container" id="heroPreview" style="margin-top: 10px;"></div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; justify-content: center;">
                    <div class="form-group">
                        <label>Caption Slide (Opsional)</label>
                        <input type="text" name="caption" class="form-input" placeholder="Contoh: Dokumentasi Kegiatan">
                        <small style="color: #888; display: block; margin-top: 5px;">Caption ini akan diterapkan ke semua foto yang diupload bersamaan.</small>
                    </div>
                    <button type="submit" class="btn-primary" style="width: 100%; margin-top: 10px;">+ Upload Semua Slide</button>
                </div>
            </div>
        </form>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

        <h4 style="margin-bottom: 15px; font-size: 14px; color: #333;">Daftar Slide Aktif</h4>

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
                        <button class="btn-delete"
                             onclick="openGlobalDeleteModal('{{ url('/admin/pengaturan/hero/'.$slide->id) }}')"
                             style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 5px; padding: 8px; font-size: 12px; border: none; cursor: pointer; background-color: #ef4444; color: white; border-radius: 6px;">
                             <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                             Hapus Slide
                        </button>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 30px; background: #f9fafb; border-radius: 8px; border: 1px dashed #ccc;">
                    <p style="color: #666; font-style: italic;">Belum ada slide yang diupload.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function previewHero(input) {
            const container = document.getElementById('heroPreview');
            container.innerHTML = ''; // Reset preview

            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Buat elemen preview kecil
                        const div = document.createElement('div');
                        div.className = 'preview-item';
                        div.style.width = '100px'; // Ukuran thumbnail
                        div.style.height = '60px';
                        div.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        function resetHeroUpload() {
            document.getElementById('heroInput').value = '';
            document.getElementById('heroPreview').innerHTML = '';
        }
    </script>
@endsection
