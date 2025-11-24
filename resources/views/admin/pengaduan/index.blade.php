@extends('layouts.admin')

@section('header', 'Kelola Pengaduan')

@section('content')
    @if(session('success'))
        <div style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #badbcc;">
            {{ session('success') }}
        </div>
    @endif

    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Daftar Laporan Masuk</h3>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Tiket & Pelapor</th>
                        <th>Judul & Lokasi</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th style="text-align: center; width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengaduan as $item)
                    <tr>
                        <td style="vertical-align: middle;">
                            {{ $item->created_at->format('d/m/Y') }}
                        </td>
                        <td style="vertical-align: middle;">
                            <span style="font-family: monospace; background: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-size: 11px; color: #555;">
                                {{ $item->ticket_number }}
                            </span><br>
                            <strong style="font-size: 13px;">{{ $item->nama }}</strong>
                        </td>
                        <td style="vertical-align: middle;">
                            <strong style="color: #333; display: block; margin-bottom: 2px;">{{ Str::limit($item->judul, 30) }}</strong>
                            <small style="color: #666;">Lokasi: {{ Str::limit($item->lokasi, 20) }}</small>
                        </td>
                        <td style="vertical-align: middle;">
                            @if($item->lampiran)
                                <a href="{{ asset($item->lampiran) }}" target="_blank" class="btn-action" style="background:#3b82f6; color:white; text-decoration:none; padding: 4px 8px; font-size: 11px; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Lihat
                                </a>
                            @else
                                <span style="color: #ccc;">-</span>
                            @endif
                        </td>
                        <td style="vertical-align: middle;">
                            @php
                                $badgeClass = 'pending'; // Default kuning
                                if($item->status == 'Diproses') $badgeClass = 'process'; // Biru
                                else if($item->status == 'Selesai') $badgeClass = 'done'; // Hijau
                                else if($item->status == 'Ditolak') $badgeClass = 'rejected'; // Merah
                            @endphp
                            <span class="status-badge {{ $badgeClass }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td style="vertical-align: middle; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">

                                <button class="btn-action btn-edit respond-btn"
                                    data-json="{{ json_encode($item) }}"
                                    title="Respon & Update Status"
                                    style="display: flex; align-items: center; gap: 5px; padding: 6px 12px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    Respon
                                </button>

                                <button class="btn-action btn-delete"
                                    onclick="openGlobalDeleteModal('{{ url('/admin/pengaduan/'.$item->id) }}')"
                                    title="Hapus Laporan"
                                    style="display: flex; align-items: center; justify-content: center; padding: 6px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:20px; color: #666;">Belum ada pengaduan masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $pengaduan->links() }}
        </div>
    </div>

    <div class="modal-overlay" id="editModal">
        <div class="modal-box" style="max-width: 750px; max-height: 90vh; display: flex; flex-direction: column; padding: 0;">

            <div class="modal-header" style="padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0; font-size: 18px;">Detail & Respon Pengaduan</h2>
                <button onclick="closeEditModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
            </div>

            <div class="modal-body" style="padding: 25px; overflow-y: auto; flex: 1;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="color:#666; font-size:12px;">Nomor Tiket</label>
                        <input type="text" id="detailTiket" class="form-input" readonly style="background:#f9fafb; font-family: monospace; font-weight: bold; color: #A50104;">
                    </div>
                    <div class="form-group">
                        <label style="color:#666; font-size:12px;">Tanggal Lapor</label>
                        <input type="text" id="detailTanggal" class="form-input" readonly style="background:#f9fafb;">
                    </div>
                    <div class="form-group">
                        <label style="color:#666; font-size:12px;">Nama Pelapor</label>
                        <input type="text" id="detailNama" class="form-input" readonly style="background:#f9fafb;">
                    </div>
                    <div class="form-group">
                        <label style="color:#666; font-size:12px;">Kontak</label>
                        <input type="text" id="detailKontak" class="form-input" readonly style="background:#f9fafb;">
                    </div>
                </div>

                <div class="form-group">
                    <label style="color:#666; font-size:12px;">Judul & Lokasi</label>
                    <input type="text" id="detailJudulLokasi" class="form-input" readonly style="background:#f9fafb; font-weight: 600;">
                </div>

                <div class="form-group">
                    <label style="color:#666; font-size:12px;">Deskripsi Masalah</label>
                    <textarea id="detailDeskripsi" class="form-input" rows="4" readonly style="background:#f9fafb; line-height: 1.5;"></textarea>
                </div>

                <div id="buktiContainer" class="form-group" style="display: none; background: #eef2ff; padding: 15px; border-radius: 8px; border: 1px solid #c7d2fe; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 20px;">📷</span>
                        <div>
                            <div style="font-weight: 600; font-size: 13px; color: #02010f;">Bukti Lampiran</div>
                            <div style="font-size: 11px; color: #6b7280;">Klik tombol untuk melihat</div>
                        </div>
                    </div>
                    <a id="detailBuktiLink" href="#" target="_blank" class="btn-primary" style="text-decoration: none; font-size: 12px; padding: 6px 12px;">Lihat Bukti</a>
                </div>

                <hr style="margin: 25px 0; border: 0; border-top: 1px solid #eee;">

                <h4 style="margin-bottom: 15px; color: #333;">Tindak Lanjut</h4>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label style="font-weight:bold;">Update Status</label>
                        <select name="status" id="modalStatus" class="form-input" required>
                            <option value="Pending">Pending (Menunggu)</option>
                            <option value="Diproses">Diproses (Sedang Dikerjakan)</option>
                            <option value="Selesai">Selesai (Tuntas)</option>
                            <option value="Ditolak">Ditolak (Tidak Valid)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-weight:bold;">Tanggapan Admin</label>
                        <textarea name="tanggapan_admin" id="modalTanggapan" rows="3" class="form-input" placeholder="Tulis tanggapan atau laporan pengerjaan..."></textarea>
                    </div>

                    <div class="modal-footer" style="text-align: right; margin-top: 20px;">
                        <button type="button" class="btn-secondary" onclick="closeEditModal()">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Respon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');

            // Event Listener untuk tombol Respon
            document.querySelectorAll('.respond-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const data = JSON.parse(this.getAttribute('data-json'));
                    const baseUrl = "{{ asset('') }}";

                    // Isi Data Detail (Read Only)
                    document.getElementById('detailTiket').value = data.ticket_number;
                    document.getElementById('detailTanggal').value = new Date(data.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                    document.getElementById('detailNama').value = data.nama;
                    document.getElementById('detailKontak').value = data.kontak;
                    document.getElementById('detailJudulLokasi').value = data.judul + ' (' + data.lokasi + ')';
                    document.getElementById('detailDeskripsi').value = data.deskripsi;

                    // Handle Bukti
                    const buktiContainer = document.getElementById('buktiContainer');
                    const buktiLink = document.getElementById('detailBuktiLink');
                    if(data.lampiran) {
                        buktiContainer.style.display = 'flex';
                        buktiLink.href = baseUrl + data.lampiran;
                    } else {
                        buktiContainer.style.display = 'none';
                    }

                    // Isi Form Edit
                    form.action = '/admin/pengaduan/' + data.id;
                    document.getElementById('modalStatus').value = data.status;
                    document.getElementById('modalTanggapan').value = data.tanggapan_admin || '';

                    // Buka Modal
                    modal.style.display = 'flex';
                });
            });
        });

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Tutup modal kalo klik background luar
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }
    </script>
@endsection
