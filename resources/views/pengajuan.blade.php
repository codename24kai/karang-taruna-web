@extends('layouts.app')

@section('title', 'Pengajuan Proposal - Portal Karang Taruna')

@section('content')
    <section class="section" id="pengajuan">
        <div class="container">
            <h2 class="section__title">Pengajuan Proposal</h2>

            <div class="pengajuan-intro">
                <p>Ajukan proposal kegiatan atau program Anda. Pastikan dokumen proposal sudah lengkap sebelum diunggah.</p>

                <button type="button" onclick="openTemplateModal()" class="btn btn--secondary btn--sm">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                        <path d="M14 10v2.67A1.33 1.33 0 0112.67 14H3.33A1.33 1.33 0 012 12.67V10M11.33 5.33L8 2M8 2L4.67 5.33M8 2v8"></path>
                    </svg>
                    Download Template Dokumen
                </button>
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

            <form action="{{ url('/pengajuan') }}" method="POST" enctype="multipart/form-data" class="form form--large">
                @csrf

                <div class="form__row">
                    <div class="form__group">
                        <label for="nama" class="form__label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama" id="nama" class="form__input" placeholder="Ketua Pelaksana" value="{{ old('nama') }}" required>
                    </div>

                    <div class="form__group">
                        <label for="kontak" class="form__label">No. HP / Email <span class="required">*</span></label>
                        <input type="text" name="kontak" id="kontak" class="form__input" value="{{ old('kontak') }}" required>
                    </div>
                </div>

                <div class="form__row">
                    <div class="form__group form__group--full">
                        <label for="judul" class="form__label">Judul Proposal <span class="required">*</span></label>
                        <input type="text" name="judul" id="judul" class="form__input" value="{{ old('judul') }}" required>
                    </div>
                </div>

                <div class="form__group">
                    <label for="ringkasan" class="form__label">Ringkasan Proposal <span class="required">*</span></label>
                    <textarea name="ringkasan" id="ringkasan" class="form__textarea" rows="4" required>{{ old('ringkasan') }}</textarea>
                </div>

                <div class="form__row">
                    <div class="form__group">
                        <label for="anggaran" class="form__label">Anggaran (Rp) <span class="required">*</span></label>
                        <input type="text" name="anggaran" id="anggaran" class="form__input" placeholder="Contoh: 5000000" value="{{ old('anggaran') }}" required>
                    </div>

                    <div class="form__group">
                        <label for="pic" class="form__label">Penanggung Jawab (PIC) <span class="required">*</span></label>
                        <input type="text" name="pic" id="pic" class="form__input" value="{{ old('pic') }}" required>
                    </div>
                </div>

                <<div class="form__group">
                    <label class="form__label">Upload Proposal (PDF, maks 10MB) <span class="required">*</span></label>

                    <div class="upload-box-wrapper">
                        <div class="upload-box" id="uploadBoxProp">
                            <div class="upload-box-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="12" y2="12"></line><line x1="15" y1="15" x2="12" y2="12"></line></svg>
                            </div>
                            <p>Upload PDF Proposal di sini</p>
                            <input type="file" name="dokumen" id="dokInput" class="upload-input-hidden" accept=".pdf" required onchange="handlePropSelect(this)">
                        </div>

                        <div id="propPreview" class="file-preview-card" style="display: none;">
                            <div class="file-preview-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            </div>
                            <div class="file-preview-info">
                                <div class="file-name" id="propName">file.pdf</div>
                                <div class="file-size" id="propSize">0 MB</div>
                            </div>
                            <button type="button" class="btn-remove-file" onclick="removeProp()" title="Hapus File">&times;</button>
                        </div>
                    </div>
                    <small class="form__help">Wajib format PDF.</small>
                </div>

                <script>
                    function handlePropSelect(input) {
                        const file = input.files[0];
                        if (file) {
                            document.getElementById('propName').textContent = file.name;
                            document.getElementById('propSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                            document.getElementById('propPreview').style.display = 'flex';
                        }
                    }

                    function removeProp() {
                        document.getElementById('dokInput').value = ''; // Reset input
                        document.getElementById('propPreview').style.display = 'none'; // Hide preview
                    }
                </script>

                <div class="form__actions">
                    <button type="submit" class="btn btn--primary">Ajukan Proposal</button>
                    <button type="reset" class="btn btn--secondary">Reset</button>
                </div>
            </form>
        </div>

        <div class="tracking-section" style="margin-top: 60px; border-top: 1px solid #eee; padding-top: 40px;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <h3 class="section__title" style="margin-bottom: 10px;">Cek Status Proposal</h3>
                    <p style="color: #666;">Masukkan nomor registrasi proposal Anda (Contoh: PRO-20251122-XXXX) untuk melihat status terkini.</p>
                </div>

                <div class="tracking-form" style="max-width: 600px; margin: 0 auto; display: flex; gap: 10px;">
                    <input type="text" id="trackingInput" class="form__input" placeholder="Masukkan Nomor Registrasi..." style="flex: 1;">
                    <button type="button" id="trackingBtn" class="btn btn--primary" onclick="cekStatusProposal()">Cek Status</button>
                </div>

                <div id="trackingResult" class="tracking-result" style="display: none; max-width: 600px; margin: 30px auto 0;"></div>
            </div>
            </div>
    </section>

    <div class="modal-overlay" id="templateModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 10000; justify-content: center; align-items: center;">
        <div class="modal-box" style="background: white; padding: 30px; border-radius: 12px; width: 90%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); animation: slideUp 0.3s;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 18px; color: #333;">Pilih Template Dokumen</h3>
                <button onclick="closeTemplateModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
            </div>

            <div class="template-list" style="display: grid; gap: 15px;">

                <a href="{{ asset('assets/docs/TEMPLATE PROPOSAL SEKULIR.docx') }}" download class="template-item" style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; text-decoration: none; color: #333; transition: all 0.2s;">
                    <div class="template-icon" style="width: 40px; height: 40px; background: #e0f2fe; color: #0284c7; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px;">📝</div>
                    <div class="template-info">
                        <h4 style="margin: 0 0 4px 0; font-size: 15px; font-weight: 600;">Proposal Kegiatan</h4>
                        <p style="margin: 0; font-size: 12px; color: #6b7280;">Format lengkap acara HUT RI & kegiatan warga.</p>
                    </div>
                </a>

                <a href="{{ asset('assets/docs/Surat Peminjaman Barang.docx') }}" download class="template-item" style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; text-decoration: none; color: #333; transition: all 0.2s;">
                    <div class="template-icon" style="width: 40px; height: 40px; background: #fff7ed; color: #ea580c; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px;">📦</div>
                    <div class="template-info">
                        <h4 style="margin: 0 0 4px 0; font-size: 15px; font-weight: 600;">Surat Peminjaman Barang</h4>
                        <p style="margin: 0; font-size: 12px; color: #6b7280;">Format surat untuk meminjam inventaris RT.</p>
                    </div>
                </a>

                <a href="{{ asset('assets/docs/Surat Permohonan Dana.docx') }}" download class="template-item" style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; text-decoration: none; color: #333; transition: all 0.2s;">
                    <div class="template-icon" style="width: 40px; height: 40px; background: #ecfdf5; color: #059669; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px;">💰</div>
                    <div class="template-info">
                        <h4 style="margin: 0 0 4px 0; font-size: 15px; font-weight: 600;">Surat Permohonan Dana</h4>
                        <p style="margin: 0; font-size: 12px; color: #6b7280;">Format pengajuan dana bantuan donatur.</p>
                    </div>
                </a>

            </div>

            <div style="margin-top: 20px; font-size: 12px; color: #6b7280; text-align: center;">
                *Klik salah satu untuk mengunduh file .docx
            </div>
        </div>
    </div>

    <script>
        function openTemplateModal() {
            document.getElementById('templateModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeTemplateModal() {
            document.getElementById('templateModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Tutup jika klik area luar box
        document.getElementById('templateModal').addEventListener('click', function(e) {
            if (e.target === this) closeTemplateModal();
        });

        async function cekStatusProposal() {
            const number = document.getElementById('trackingInput').value;
            const resultDiv = document.getElementById('trackingResult');

            if(!number) return alert('Masukkan nomor proposal dulu!');

            // Efek Loading
            document.getElementById('trackingBtn').innerText = 'Mencari...';
            document.getElementById('trackingBtn').disabled = true;

            try {
                const response = await fetch(`/api/track-pengajuan?number=${number}`);
                const data = await response.json();

                if(data.found) {
                    // Tentukan Warna Badge Status
                    let badgeBg = '#f3f4f6';
                    let badgeColor = '#374151';

                    if(data.data.status == 'Disetujui') { badgeBg = '#d1fae5'; badgeColor = '#047857'; }
                    else if(data.data.status == 'Menunggu') { badgeBg = '#ffedd5'; badgeColor = '#c2410c'; }
                    else if(data.data.status == 'Revisi') { badgeBg = '#dbeafe'; badgeColor = '#1e40af'; }
                    else if(data.data.status == 'Ditolak') { badgeBg = '#fee2e2'; badgeColor = '#b91c1c'; }

                    const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' });

                    resultDiv.innerHTML = `
                        <div style="padding: 25px; background: white; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">

                            <div style="border-bottom: 1px solid #f3f4f6; padding-bottom: 15px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: bold; color: #333; font-size: 16px;">${data.data.proposal_number}</span>
                                <span style="background: ${badgeBg}; color: ${badgeColor}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                    ${data.data.status}
                                </span>
                            </div>

                            <div style="display: grid; gap: 15px;">
                                <div>
                                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">Judul Proposal</div>
                                    <div style="font-weight: 600; color: #111;">${data.data.judul}</div>
                                </div>
                                <div>
                                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px;">Anggaran Diajukan</div>
                                    <div style="font-weight: 600; color: #111;">${formatter.format(data.data.anggaran)}</div>
                                </div>
                                <div style="background: #f9fafb; padding: 15px; border-radius: 8px;">
                                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 5px;">Catatan Admin:</div>
                                    <div style="font-style: italic; color: #333;">"${data.data.catatan_admin || 'Belum ada catatan.'}"</div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div style="padding: 15px; background: #fee2e2; color: #b91c1c; border-radius: 8px; text-align: center;">
                            Nomor proposal <strong>${number}</strong> tidak ditemukan. Coba periksa kembali.
                        </div>
                    `;
                }
                resultDiv.style.display = 'block';
            } catch (error) {
                console.error(error);
                alert('Terjadi kesalahan koneksi.');
            } finally {
                document.getElementById('trackingBtn').innerText = 'Cek Status';
                document.getElementById('trackingBtn').disabled = false;
            }
        }
    </script>

    <style>
        .template-item:hover {
            background-color: #fff !important;
            border-color: #A50104 !important;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    @if(session('success_modal'))
    <div class="modal-overlay" id="successModal" style="display: flex; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 99999; justify-content: center; align-items: center;">
        <div class="modal-box" style="background: white; padding: 40px; border-radius: 12px; width: 90%; max-width: 500px; text-align: center; animation: slideUp 0.3s;">

            <div style="width: 80px; height: 80px; background: #ecfdf5; color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; font-size: 40px;">
                ✓
            </div>

            <h2 style="margin-bottom: 10px; color: #111;">Proposal Berhasil Diajukan!</h2>
            <p style="color: #666; margin-bottom: 25px;">Proposal Anda telah masuk ke sistem kami dan sedang menunggu proses review.</p>

            <div style="background: #f3f4f6; padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px dashed #ccc;">
                <p style="font-size: 12px; text-transform: uppercase; color: #666; margin-bottom: 5px; font-weight: 600;">Nomor Registrasi Proposal:</p>
                <h3 style="font-size: 24px; font-family: monospace; color: #A50104; margin: 0; letter-spacing: 1px;" id="ticketDisplay">{{ session('ticket_number') }}</h3>
            </div>

            <p style="font-size: 13px; color: #ef4444; margin-bottom: 25px;">
                *Mohon SIMPAN nomor ini untuk mengecek status persetujuan proposal Anda secara berkala.
            </p>

            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="copyTicket()" class="btn btn--secondary" style="display: flex; align-items: center; gap: 5px;">
                    📋 Salin ID
                </button>
                <button onclick="document.getElementById('successModal').style.display='none'" class="btn btn--primary">
                    Siap, Mengerti
                </button>
            </div>
        </div>
    </div>

    <script>
        function copyTicket() {
            const ticket = document.getElementById('ticketDisplay').innerText;
            navigator.clipboard.writeText(ticket).then(() => {
                alert('ID Proposal berhasil disalin: ' + ticket);
            });
        }
    </script>
    @endif
@endsection
