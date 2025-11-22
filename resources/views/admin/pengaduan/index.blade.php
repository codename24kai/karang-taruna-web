@extends('layouts.admin')

@section('header', 'Kelola Pengaduan')

@section('content')
    @if(session('success'))
        <div style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
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
                        <th>Pelapor</th>
                        <th>Judul & Lokasi</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengaduan as $item)
                    <tr>
                        <td>
                            {{ $item->created_at->format('d/m/Y') }}<br>
                            <small style="color:#666">{{ $item->ticket_number }}</small>
                        </td>
                        <td>
                            <strong>{{ $item->nama }}</strong><br>
                            <small>{{ $item->kontak }}</small>
                        </td>
                        <td>
                            <strong>{{ $item->judul }}</strong><br>
                            <small>{{ $item->lokasi }}</small>
                        </td>
                        <td>
                            @if($item->lampiran)
                                <a href="{{ asset($item->lampiran) }}" target="_blank" class="btn-action" style="background:#3b82f6; color:white; text-decoration:none;">Lihat</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="status-badge {{ strtolower($item->status) == 'diproses' ? 'process' : (strtolower($item->status) == 'selesai' ? 'done' : (strtolower($item->status) == 'ditolak' ? 'rejected' : 'pending')) }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; gap:5px;">
                                <button class="btn-action btn-edit"
                                    onclick="openEditModal('{{ json_encode($item) }}')"> Respon
                                </button>

                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-action btn-delete" onclick="openGlobalDeleteModal('{{ url('/admin/pengaduan/'.$item->id) }}')">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:20px;">Belum ada pengaduan masuk.</td>
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
        <div class="modal-box" style="max-width: 700px; max-height: 90vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="padding-bottom: 15px; border-bottom: 1px solid #eee; margin-bottom: 20px;">
                <h2 style="margin: 0;">Detail Pengaduan</h2>
            </div>

            <div class="modal-body" style="overflow-y: auto; flex: 1; padding-right: 10px;">
                <div class="form-group">
                    <label style="color:#666; font-size:13px;">Tanggal & Tiket</label>
                    <input type="text" id="detailTanggal" class="form-input" readonly style="background:#f9fafb;">
                </div>
                <div class="form-group">
                    <label style="color:#666; font-size:13px;">Pelapor</label>
                    <input type="text" id="detailPelapor" class="form-input" readonly style="background:#f9fafb;">
                </div>
                <div class="form-group">
                    <label style="color:#666; font-size:13px;">Judul Laporan</label>
                    <input type="text" id="detailJudul" class="form-input" readonly style="background:#f9fafb;">
                </div>
                <div class="form-group">
                    <label style="color:#666; font-size:13px;">Isi Laporan</label>
                    <textarea id="detailDeskripsi" class="form-input" rows="4" readonly style="background:#f9fafb;"></textarea>
                </div>
                <div class="form-group">
                    <label style="color:#666; font-size:13px;">Lokasi</label>
                    <input type="text" id="detailLokasi" class="form-input" readonly style="background:#f9fafb;">
                </div>

                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

                <form id="editForm" method="POST">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label style="font-weight:bold;">Update Status</label>
                        <select name="status" id="modalStatus" class="form-input">
                            <option value="Pending">Pending</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-weight:bold;">Tanggapan Admin</label>
                        <textarea name="tanggapan_admin" id="modalTanggapan" rows="3" class="form-input" placeholder="Tulis balasan untuk pelapor..."></textarea>
                    </div>

                    <div class="modal-footer" style="margin-top: 20px; text-align: right;">
                        <button type="button" class="btn-secondary" onclick="closeEditModal()">Tutup</button>
                        <button type="submit" class="btn-primary">Simpan Respon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(itemJson) {
            const item = JSON.parse(itemJson); // Parsing data object dari PHP

            // Isi Data Read Only
            document.getElementById('detailTanggal').value = new Date(item.created_at).toLocaleDateString('id-ID') + ' - ' + item.ticket_number;
            document.getElementById('detailPelapor').value = item.nama + ' (' + item.kontak + ')';
            document.getElementById('detailJudul').value = item.judul;
            document.getElementById('detailDeskripsi').value = item.deskripsi;
            document.getElementById('detailLokasi').value = item.lokasi;

            // Isi Form Update
            document.getElementById('editForm').action = '/admin/pengaduan/' + item.id;
            document.getElementById('modalStatus').value = item.status;
            document.getElementById('modalTanggapan').value = item.tanggapan_admin || '';

            // Tampilkan
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) closeEditModal();
        }
    </script>
@endsection
