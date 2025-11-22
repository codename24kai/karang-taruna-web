<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaduan;

class PengaduanController extends Controller
{
    // 1. Tampilkan Daftar Pengaduan
    public function index()
    {
        $pengaduan = Pengaduan::latest()->paginate(10); // 10 data per halaman
        return view('admin.pengaduan.index', compact('pengaduan'));
    }

    // 2. Update Status & Tanggapan
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'tanggapan_admin' => 'nullable|string'
        ]);

        $item = Pengaduan::findOrFail($id);
        $item->update([
            'status' => $request->status,
            'tanggapan_admin' => $request->tanggapan_admin
        ]);

        return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui!');
    }

    // 3. Hapus Pengaduan
    public function destroy($id)
    {
        $item = Pengaduan::findOrFail($id);
        // Hapus file lampiran kalo ada (biar hemat storage)
        if ($item->lampiran && file_exists(public_path($item->lampiran))) {
            unlink(public_path($item->lampiran));
        }
        $item->delete();

        return redirect()->back()->with('success', 'Data pengaduan dihapus.');
    }
}
