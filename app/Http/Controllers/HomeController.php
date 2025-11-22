<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Pengaduan;
use App\Models\Pengajuan;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil Statistik Real (Tetap)
        $stats = [
            'pengaduan' => Pengaduan::where('status', 'Selesai')->count(),
            'kegiatan'  => Gallery::count(),
            'proposal'  => Pengajuan::where('status', 'Disetujui')->count(),
        ];

        // 2. Ambil Berita Terbaru (Tetap)
        $latest_articles = Article::latest('published_at')->take(3)->get();

        // 3. Ambil Data Galeri Terbaru (Buat Section Galeri di Home)
        // Ambil 6 kegiatan terakhir
        $home_galleries = Gallery::latest('date')->take(6)->get();

        return view('home', compact('stats', 'latest_articles', 'home_galleries'));
    }
}
