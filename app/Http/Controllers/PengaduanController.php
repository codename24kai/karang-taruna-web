<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use Illuminate\Support\Str; // Buat bikin random string

class PengaduanController extends Controller
{
    // Tampilkan Halaman Form
    public function index()
    {
        return view('pengaduan');
    }

    // Proses Simpan Data
    public function store(Request $request)
    {
        // 1. Validasi Input (Biar gak asal isi)
        $validated = $request->validate([
            'nama' => 'required|min:3',
            'kontak' => 'required',
            'judul' => 'required|min:5',
            'kategori' => 'required',
            'lokasi' => 'required',
            'deskripsi' => 'required|min:10',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB
        ]);

        // 2. Handle Upload File (Kalau ada)
        $filePath = null;
        if ($request->hasFile('file')) {
            // Simpan ke folder: public/uploads/pengaduan
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pengaduan'), $fileName);
            $filePath = 'uploads/pengaduan/' . $fileName;
        }

        // 3. Bikin Nomor Tiket Unik (CTR-TahunBulanTanggal-Random)
        $ticketNumber = 'CTR-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // 4. Simpan ke Database
        Pengaduan::create([
            'ticket_number' => $ticketNumber,
            'nama' => $request->nama,
            'kontak' => $request->kontak,
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'lampiran' => $filePath,
            'status' => 'Pending'
        ]);

        // 5. Balikin ke halaman dengan pesan sukses
        return redirect()->back()->with([
            'success_modal' => true, // Trigger buat buka modal
            'ticket_number' => $ticketNumber, // Kirim nomor tiket
            'type' => 'Pengaduan'
        ]);
    }

    // Fitur Tracking (Cek Status)
    public function track(Request $request)
    {
        $ticket = $request->input('ticket');
        $data = Pengaduan::where('ticket_number', $ticket)->first();

        if ($data) {
            return response()->json([
                'found' => true,
                'data' => $data
            ]);
        } else {
            return response()->json(['found' => false]);
        }
    }
}
