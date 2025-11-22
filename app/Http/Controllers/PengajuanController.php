<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{
    public function index()
    {
        return view('pengajuan');
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nama' => 'required',
            'kontak' => 'required',
            'judul' => 'required',
            'ringkasan' => 'required',
            'anggaran' => 'required', // Nanti kita bersihin format Rp-nya
            'pic' => 'required',
            'dokumen' => 'required|file|mimes:pdf|max:10240', // Wajib PDF, max 10MB
        ]);

        // Upload PDF
        $filePath = null;
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/proposal'), $fileName);
            $filePath = 'uploads/proposal/' . $fileName;
        }

        // Bersihkan format Rupiah (Hapus semua kecuali angka)
        $cleanAnggaran = preg_replace('/[^0-9]/', '', $request->anggaran);

        // FIX: Kalau hasil bersihnya kosong (string ""), ubah jadi angka 0
        if (empty($cleanAnggaran)) {
            $cleanAnggaran = 0;
        }

        // Nomor Proposal
        $proposalNumber = 'PRO-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        Pengajuan::create([
            'proposal_number' => $proposalNumber,
            'nama_pengaju' => $request->nama,
            'kontak' => $request->kontak,
            'judul' => $request->judul,
            'ringkasan' => $request->ringkasan,
            'anggaran' => $cleanAnggaran,
            'pic' => $request->pic,
            'file_proposal' => $filePath,
            'status' => 'Menunggu'
        ]);

        return redirect()->back()->with([
            'success_modal' => true,
            'ticket_number' => $proposalNumber,
            'type' => 'Proposal'
        ]);
    }

    public function track(Request $request)
    {
        $number = $request->input('number');
        $data = Pengajuan::where('proposal_number', $number)->first();

        if ($data) {
            return response()->json(['found' => true, 'data' => $data]);
        }
        return response()->json(['found' => false]);
    }
}
