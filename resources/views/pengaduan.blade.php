@extends('layouts.app')

@section('title', 'Pengaduan - Portal Karang Taruna')

@section('content')
    <section class="section section--gray" id="pengaduan">
        <div class="container">
            <h2 class="section__title">Pengaduan Masyarakat</h2>

            <div class="pengaduan-header">
                <p class="pengaduan-header__text">Sampaikan keluhan, aspirasi, atau laporan Anda. Setiap pengaduan akan mendapat nomor tracking untuk monitoring status.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" style="background: #f8d7da; color: #842029; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c2c7;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/pengaduan') }}" method="POST" enctype="multipart/form-data" class="form form--large">
                @csrf <div class="form__row">
                    <div class="form__group">
                        <label for="nama" class="form__label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama" id="nama" class="form__input" placeholder="Masukkan nama sesuai KTP" value="{{ old('nama') }}" required>
                    </div>

                    <div class="form__group">
                        <label for="kontak" class="form__label">No. HP / Email <span class="required">*</span></label>
                        <input type="text" name="kontak" id="kontak" class="form__input" placeholder="Contoh: 0812xxx" value="{{ old('kontak') }}" required>
                    </div>
                </div>

                <div class="form__row">
                    <div class="form__group form__group--full">
                        <label for="judul" class="form__label">Judul Pengaduan <span class="required">*</span></label>
                        <input type="text" name="judul" id="judul" class="form__input" value="{{ old('judul') }}" required>
                    </div>
                </div>

                <div class="form__row">
                    <div class="form__group">
                        <label for="kategori" class="form__label">Kategori <span class="required">*</span></label>
                        <select name="kategori" id="kategori" class="form__select" required>
                            <option value="">Pilih kategori</option>
                            <option value="Infrastruktur" {{ old('kategori') == 'Infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                            <option value="Lingkungan" {{ old('kategori') == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                            <option value="Sosial" {{ old('kategori') == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                            <option value="Keamanan" {{ old('kategori') == 'Keamanan' ? 'selected' : '' }}>Keamanan</option>
                            <option value="Layanan Publik" {{ old('kategori') == 'Layanan Publik' ? 'selected' : '' }}>Layanan Publik</option>
                        </select>
                    </div>

                    <div class="form__group">
                        <label for="lokasi" class="form__label">Lokasi <span class="required">*</span></label>
                        <input type="text" name="lokasi" id="lokasi" class="form__input" placeholder="Contoh: Jl. Merdeka No. 10" value="{{ old('lokasi') }}" required>
                    </div>
                </div>

                <div class="form__group">
                    <label for="deskripsi" class="form__label">Deskripsi Pengaduan <span class="required">*</span></label>
                    <textarea name="deskripsi" id="deskripsi" class="form__textarea" rows="6" placeholder="Jelaskan pengaduan Anda secara detail..." required>{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form__group">
                    <label class="form__label">Upload Bukti (Foto/PDF, maks 5MB)</label>

                    <div class="upload-box-wrapper">
                        <div class="upload-box" id="uploadBox">
                            <div class="upload-box-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            </div>
                            <p>Klik atau drag file ke sini</p>
                            <input type="file" name="file" id="fileInput" class="upload-input-hidden" accept="image/*,.pdf" onchange="handleFileSelect(this)">
                        </div>

                        <div id="filePreview" class="file-preview-card" style="display: none;">
                            <div class="file-preview-icon" id="previewIcon">
                                </div>
                            <div class="file-preview-info">
                                <div class="file-name" id="fileName">nama_file.jpg</div>
                                <div class="file-size" id="fileSize">2.5 MB</div>
                            </div>
                            <button type="button" class="btn-remove-file" onclick="removeFile()" title="Hapus File">&times;</button>
                        </div>
                    </div>
                    <small class="form__help">Format: JPG, PNG, PDF. Maksimal 5MB</small>
                </div>

                <script>
                    function handleFileSelect(input) {
                        const file = input.files[0];
                        const previewBox = document.getElementById('filePreview');
                        const uploadBox = document.getElementById('uploadBox');
                        const nameEl = document.getElementById('fileName');
                        const sizeEl = document.getElementById('fileSize');
                        const iconEl = document.getElementById('previewIcon');

                        if (file) {
                            // 1. Tampilkan Info File
                            nameEl.textContent = file.name;
                            sizeEl.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';

                            // 2. Cek Tipe (Gambar atau PDF)
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    iconEl.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                                }
                                reader.readAsDataURL(file);
                            } else {
                                iconEl.innerHTML = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>`;
                            }

                            // 3. Tampilkan Preview, Sembunyikan Kotak Upload (Opsional, atau biarkan dua-duanya)
                            // Disini saya biarkan kotak upload tetap ada biar bisa ganti file langsung,
                            // tapi kalau mau hidden, uncomment baris bawah:
                            // uploadBox.style.display = 'none';

                            previewBox.style.display = 'flex';
                        }
                    }

                    function removeFile() {
                        const input = document.getElementById('fileInput');
                        const previewBox = document.getElementById('filePreview');
                        const uploadBox = document.getElementById('uploadBox');

                        // Reset Input
                        input.value = '';

                        // Sembunyikan Preview
                        previewBox.style.display = 'none';

                        // Tampilkan lagi kotak upload (kalau tadi di-hide)
                        uploadBox.style.display = 'flex';
                    }
                </script>

                <div class="form__actions">
                    <button type="submit" class="btn btn--primary">Kirim Pengaduan</button>
                    <button type="reset" class="btn btn--tertiary">Reset</button>
                </div>
            </form>

            <div class="tracking-section" style="margin-top: 60px; border-top: 1px solid #eee; padding-top: 40px;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <h3 class="section__title" style="margin-bottom: 10px;">Lacak Status Pengaduan</h3>
                    <p style="color: #666;">Masukkan nomor tiket laporan Anda (Contoh: CTR-20251122-XXXX) untuk memantau progres penanganan.</p>
                </div>

                <div class="tracking-form" style="max-width: 600px; margin: 0 auto; display: flex; gap: 10px;">
                    <input type="text" id="trackingInput" class="form__input" placeholder="Masukkan Nomor Tiket..." style="flex: 1;">
                    <button type="button" id="trackingBtn" class="btn btn--primary" onclick="cekStatusPengaduan()">Lacak</button>
                </div>

                <div id="trackingResult" class="tracking-result" style="display: none; max-width: 600px; margin: 30px auto 0;"></div>
            </div>
        </div>
    </section>

    <script>
        async function cekStatusPengaduan() {
            const ticket = document.getElementById('trackingInput').value;
            const resultDiv = document.getElementById('trackingResult');
            const btn = document.getElementById('trackingBtn');

            if(!ticket) return alert('Masukkan nomor tiket dulu!');

            // Efek Loading
            btn.innerText = 'Mencari...';
            btn.disabled = true;

            try {
                const response = await fetch(`/api/track-pengaduan?ticket=${ticket}`);
                const data = await response.json();

                if(data.found) {
                    // Logic Warna Badge Status
                    let badgeBg = '#f3f4f6';
                    let badgeColor = '#374151';

                    if(data.data.status == 'Selesai') { badgeBg = '#d1fae5'; badgeColor = '#047857'; } // Hijau
                    else if(data.data.status == 'Diproses') { badgeBg = '#dbeafe'; badgeColor = '#1e40af'; } // Biru
                    else if(data.data.status == 'Ditolak') { badgeBg = '#fee2e2'; badgeColor = '#b91c1c'; } // Merah
                    else { badgeBg = '#ffedd5'; badgeColor = '#c2410c'; } // Kuning (Pending)

                    resultDiv.innerHTML = `
                        <div style="padding: 25px; background: white; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">

                            <div style="border-bottom: 1px solid #f3f4f6; padding-bottom: 15px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: bold; color: #333; font-size: 16px;">${data.data.ticket_number}</span>
                                <span style="background: ${badgeBg}; color: ${badgeColor}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                    ${data.data.status}
                                </span>
                            </div>

                            <div style="display: grid; gap: 15px;">
                                <div>
                                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">Judul Laporan</div>
                                    <div style="font-weight: 600; color: #111;">${data.data.judul}</div>
                                </div>
                                <div>
                                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">Lokasi</div>
                                    <div style="font-weight: 600; color: #111;">${data.data.lokasi}</div>
                                </div>

                                <div style="background: #f9fafb; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">Tanggapan Admin:</div>
                                    <div style="font-style: italic; color: #333;">"${data.data.tanggapan_admin || 'Belum ada tanggapan.'}"</div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    // Tampilan Tidak Ditemukan
                    resultDiv.innerHTML = `
                        <div style="padding: 15px; background: #fee2e2; color: #b91c1c; border-radius: 8px; text-align: center;">
                            Nomor tiket <strong>${ticket}</strong> tidak ditemukan. Coba periksa kembali.
                        </div>
                    `;
                }
                resultDiv.style.display = 'block';
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan koneksi.');
            } finally {
                // Reset Tombol
                btn.innerText = 'Lacak';
                btn.disabled = false;
            }
        }
    </script>

    @if(session('success_modal'))
    <div class="modal-overlay" id="successModal" style="display: flex; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 99999; justify-content: center; align-items: center;">
        <div class="modal-box" style="background: white; padding: 40px; border-radius: 12px; width: 90%; max-width: 500px; text-align: center; animation: slideUp 0.3s;">

            <div style="width: 80px; height: 80px; background: #ecfdf5; color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; font-size: 40px;">
                ✓
            </div>

            <h2 style="margin-bottom: 10px; color: #111;">Laporan Berhasil Dikirim!</h2>
            <p style="color: #666; margin-bottom: 25px;">Terima kasih telah melapor. Laporan Anda telah kami terima dan akan segera diproses.</p>

            <div style="background: #f3f4f6; padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px dashed #ccc;">
                <p style="font-size: 12px; text-transform: uppercase; color: #666; margin-bottom: 5px; font-weight: 600;">Nomor Tiket Anda:</p>
                <h3 style="font-size: 28px; font-family: monospace; color: #A50104; margin: 0; letter-spacing: 2px;" id="ticketDisplay">{{ session('ticket_number') }}</h3>
            </div>

            <p style="font-size: 13px; color: #ef4444; margin-bottom: 25px;">
                *Harap SIMPAN atau Screenshot nomor tiket ini untuk mengecek status laporan Anda.
            </p>

            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="copyTicket()" class="btn btn--secondary" style="display: flex; align-items: center; gap: 5px;">
                    📋 Salin ID
                </button>
                <button onclick="document.getElementById('successModal').style.display='none'" class="btn btn--primary">
                    Oke, Mengerti
                </button>
            </div>
        </div>
    </div>

    <script>
        function copyTicket() {
            const ticket = document.getElementById('ticketDisplay').innerText;
            navigator.clipboard.writeText(ticket).then(() => {
                alert('Nomor tiket berhasil disalin: ' + ticket);
            });
        }
    </script>
    @endif
@endsection
