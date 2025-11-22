@extends('layouts.admin')

@section('header', 'Kelola Proposal')

@section('content')
    @if(session('success'))
        <div style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">Proposal Masuk</h3>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>No. Proposal</th>
                        <th>Pengaju & Judul</th>
                        <th>Anggaran</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proposals as $p)
                    <tr>
                        <td>{{ $p->created_at->format('d/m/Y') }}</td>
                        <td><small>{{ $p->proposal_number }}</small></td>
                        <td>
                            <strong>{{ $p->judul }}</strong><br>
                            <small>Oleh: {{ $p->nama_pengaju }} ({{ $p->kontak }})</small>
                        </td>
                        <td>Rp {{ number_format($p->anggaran, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ asset($p->file_proposal) }}" target="_blank" class="btn-action" style="background:#ef4444; text-decoration:none;">PDF</a>
                        </td>
                        <td>
                            @php
                                $badge = 'pending';
                                if($p->status == 'Disetujui') $badge = 'done';
                                if($p->status == 'Ditolak') $badge = 'rejected';
                                if($p->status == 'Revisi') $badge = 'process';
                            @endphp
                            <span class="status-badge {{ $badge }}">{{ $p->status }}</span>
                        </td>
                        <td>
                            <div style="display:flex; gap:5px;">
                                <button class="btn-action btn-edit"
                                    onclick="openEditModal('{{ json_encode($p) }}')">
                                    Cek
                                </button>
                                    @csrf @method('DELETE')
                                    <button class="btn-action btn-delete" onclick="openGlobalDeleteModal('{{ url('/admin/proposal/'.$p->id) }}')">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center; padding:20px;">Belum ada proposal.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 20px;">{{ $proposals->links() }}</div>
    </div>

    <div class="modal-overlay" id="editModal">
        <div class="modal-box" style="max-width: 700px; max-height: 90vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="padding-bottom: 15px; border-bottom: 1px solid #eee; margin-bottom: 20px;">
                <h2 style="margin: 0;">Detail Proposal</h2>
            </div>

            <div class="modal-body" style="overflow-y: auto; flex: 1; padding-right: 10px;">
                <div class="form-group">
                    <label style="color:#666; font-size:13px;">Tanggal & No. Proposal</label>
                    <input type="text" id="detailTanggal" class="form-input" readonly style="background:#f9fafb;">
                </div>
                <div class="form-group">
                    <label style="color:#666; font-size:13px;">Penanggung Jawab</label>
                    <input type="text" id="detailPic" class="form-input" readonly style="background:#f9fafb;">
                </div>
                <div class="form-group">
                    <label style="color:#666; font-size:13px;">Judul Proposal</label>
                    <input type="text" id="detailJudul" class="form-input" readonly style="background:#f9fafb;">
                </div>
                <div class="form-group">
                    <label style="color:#666; font-size:13px;">Ringkasan</label>
                    <textarea id="detailRingkasan" class="form-input" rows="4" readonly style="background:#f9fafb;"></textarea>
                </div>
                <div class="form-group">
                    <label style="color:#666; font-size:13px;">Anggaran</label>
                    <input type="text" id="detailAnggaran" class="form-input" readonly style="background:#f9fafb;">
                </div>

                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">

                <form id="editForm" method="POST">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label style="font-weight:bold;">Status Approval</label>
                        <select name="status" id="modalStatus" class="form-input">
                            <option value="Menunggu">Menunggu</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Revisi">Perlu Revisi</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-weight:bold;">Catatan Revisi / Admin</label>
                        <textarea name="catatan_admin" id="modalCatatan" rows="3" class="form-input" placeholder="Berikan alasan atau catatan..."></textarea>
                    </div>

                    <div class="modal-footer" style="margin-top: 20px; text-align: right;">
                        <button type="button" class="btn-secondary" onclick="document.getElementById('editModal').style.display='none'">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Keputusan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(itemJson) {
            const item = JSON.parse(itemJson);

            // Format Rupiah
            const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' });

            document.getElementById('detailTanggal').value = new Date(item.created_at).toLocaleDateString('id-ID') + ' - ' + item.proposal_number;
            document.getElementById('detailPic').value = item.nama_pengaju + ' (PIC: ' + item.pic + ')';
            document.getElementById('detailJudul').value = item.judul;
            document.getElementById('detailRingkasan').value = item.ringkasan;
            document.getElementById('detailAnggaran').value = formatter.format(item.anggaran);

            document.getElementById('editForm').action = '/admin/proposal/' + item.id;
            document.getElementById('modalStatus').value = item.status;
            document.getElementById('modalCatatan').value = item.catatan_admin || '';

            document.getElementById('editModal').style.display = 'flex';
        }
    </script>
@endsection
