@extends('layouts.admin')

@section('header', 'Kelola Proposal')

@section('content')
    @if(session('success'))
        <div style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #badbcc;">
            {{ session('success') }}
        </div>
    @endif

    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Daftar Proposal Masuk</h3>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>No. Proposal</th>
                        <th>Pengaju & Judul</th>
                        <th>Status</th>
                        <th style="text-align: center; width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals as $p)
                    <tr>
                        <td style="vertical-align: middle;">
                            {{ $p->created_at->format('d M Y') }}
                        </td>
                        <td style="vertical-align: middle;">
                            <span style="font-family: monospace; background: #f3f4f682; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                {{ $p->proposal_number }}
                            </span>
                        </td>
                        <td style="vertical-align: middle;">
                            <strong style="color: #333; display: block; margin-bottom: 2px;">{{ Str::limit($p->judul, 40) }}</strong>
                            <small style="color: #666;">Oleh: {{ $p->nama_pengaju }}</small>
                        </td>
                        <td style="vertical-align: middle;">
                            @php
                                $badgeClass = 'pending';
                                if($p->status == 'Disetujui') $badgeClass = 'done';
                                else if($p->status == 'Ditolak') $badgeClass = 'rejected';
                                else if($p->status == 'Revisi') $badgeClass = 'process';
                            @endphp
                            <span class="status-badge {{ $badgeClass }}">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td style="vertical-align: middle; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <button class="btn-action btn-edit check-btn"
                                    data-json="{{ json_encode($p) }}"
                                    title="Cek Detail & Review">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    Cek
                                </button>

                                <button class="btn-action btn-delete"
                                    onclick="openGlobalDeleteModal('{{ url('/admin/proposal/'.$p->id) }}')"
                                    title="Hapus">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 40px; color: #666;">
                            Belum ada proposal masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $proposals->links() }}
        </div>
    </div>

    <div class="modal-overlay" id="detailModal">
        <div class="modal-box" style="max-width: 750px; max-height: 90vh; display: flex; flex-direction: column; padding: 0;">

            <div class="modal-header" style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; font-size: 18px;">Detail Proposal</h2>
                <button onclick="closeDetailModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
            </div>

            <div class="modal-body" style="padding: 25px; overflow-y: auto; flex: 1;">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="color:#666; font-size:12px;">Nomor Proposal</label>
                        <input type="text" id="detNo" class="form-input" readonly style="background:#f9fafb; font-family: monospace;">
                    </div>
                    <div class="form-group">
                        <label style="color:#666; font-size:12px;">Tanggal Pengajuan</label>
                        <input type="text" id="detTgl" class="form-input" readonly style="background:#f9fafb;">
                    </div>
                    <div class="form-group">
                        <label style="color:#666; font-size:12px;">Nama Pengaju</label>
                        <input type="text" id="detNama" class="form-input" readonly style="background:#f9fafb;">
                    </div>
                    <div class="form-group">
                        <label style="color:#666; font-size:12px;">Kontak / HP</label>
                        <input type="text" id="detKontak" class="form-input" readonly style="background:#f9fafb;">
                    </div>
                    <div class="form-group">
                        <label style="color:#666; font-size:12px;">Penanggung Jawab (PIC)</label>
                        <input type="text" id="detPic" class="form-input" readonly style="background:#f9fafb;">
                    </div>
                    <div class="form-group">
                        <label style="color:#666; font-size:12px;">Anggaran Diajukan</label>
                        <input type="text" id="detAnggaran" class="form-input" readonly style="background:#f9fafb; font-weight: bold; color: #A50104;">
                    </div>
                </div>

                <div class="form-group">
                    <label style="color:#666; font-size:12px;">Judul Proposal</label>
                    <input type="text" id="detJudul" class="form-input" readonly style="background:#f9fafb; font-weight: 600;">
                </div>

                <div class="form-group">
                    <label style="color:#666; font-size:12px;">Ringkasan Kegiatan</label>
                    <textarea id="detRingkasan" class="form-input" rows="4" readonly style="background:#f9fafb;"></textarea>
                </div>

                <div class="form-group" style="background: #eef2ff; padding: 15px; border-radius: 8px; border: 1px solid #c7d2fe; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 24px;">📄</span>
                        <div>
                            <div style="font-weight: 600; font-size: 14px; color: #3730a3;">Dokumen Proposal</div>
                            <div style="font-size: 11px; color: #6b7280;">Format PDF</div>
                        </div>
                    </div>
                    <a id="detLink" href="#" target="_blank" class="btn-primary" style="text-decoration: none; font-size: 12px; padding: 8px 16px;">Download File</a>
                </div>

                <hr style="margin: 25px 0; border: 0; border-top: 1px solid #eee;">

                <h4 style="margin-bottom: 15px; color: #333;">Keputusan Admin</h4>

                <form id="reviewForm" method="POST">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label style="font-weight:bold;">Status Approval</label>
                        <select name="status" id="inpStatus" class="form-input" required>
                            <option value="Menunggu">Menunggu</option>
                            <option value="Disetujui">Disetujui (ACC)</option>
                            <option value="Revisi">Perlu Revisi</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-weight:bold;">Catatan / Alasan</label>
                        <textarea name="catatan_admin" id="inpCatatan" rows="3" class="form-input" placeholder="Contoh: Anggaran terlalu besar, mohon direvisi..."></textarea>
                    </div>

                    <div style="text-align: right; margin-top: 20px;">
                        <button type="button" class="btn-secondary" onclick="closeDetailModal()">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Keputusan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('detailModal');

            // Event Listener untuk tombol Cek
            document.querySelectorAll('.check-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const data = JSON.parse(this.getAttribute('data-json'));

                    // Format Rupiah
                    const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' });

                    // Isi Data Read Only
                    document.getElementById('detNo').value = data.proposal_number;
                    document.getElementById('detTgl').value = new Date(data.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                    document.getElementById('detNama').value = data.nama_pengaju;
                    document.getElementById('detKontak').value = data.kontak;
                    document.getElementById('detPic').value = data.pic;
                    document.getElementById('detJudul').value = data.judul;
                    document.getElementById('detRingkasan').value = data.ringkasan;
                    document.getElementById('detAnggaran').value = formatter.format(data.anggaran);

                    // Setup Link Download
                    const linkBtn = document.getElementById('detLink');
                    if(data.file_proposal) {
                        linkBtn.href = "{{ asset('') }}" + data.file_proposal;
                        linkBtn.style.display = 'inline-block';
                    } else {
                        linkBtn.style.display = 'none';
                    }

                    // Isi Form Review
                    document.getElementById('reviewForm').action = '/admin/proposal/' + data.id;
                    document.getElementById('inpStatus').value = data.status;
                    document.getElementById('inpCatatan').value = data.catatan_admin || '';

                    // Buka Modal
                    modal.style.display = 'flex';
                });
            });
        });

        function closeDetailModal() {
            document.getElementById('detailModal').style.display = 'none';
        }

        // Tutup modal kalau klik background luar
        window.onclick = function(event) {
            const modal = document.getElementById('detailModal');
            if (event.target == modal) {
                closeDetailModal();
            }
        }
    </script>
@endsection
