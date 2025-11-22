<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Pengaduan;
use App\Models\Pengajuan;
use App\Models\HeroSlide; // <--- Jangan lupa import Model ini!

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil Statistik Real
        $stats = [
            'pengaduan' => Pengaduan::where('status', 'Selesai')->count(),
            'kegiatan'  => Gallery::count(),
            'proposal'  => Pengajuan::where('status', 'Disetujui')->count(),
        ];

        // 2. Ambil Berita Terbaru (3 Teratas)
        $latest_articles = Article::latest('published_at')->take(3)->get();

        // 3. Ambil Data Galeri Kegiatan (6 Teratas buat Grid di bawah)
        $home_galleries = Gallery::latest('date')->take(6)->get();

        // 4. Ambil Data Hero Carousel (Dari Pengaturan Admin)
        // Ini variabel yang bikin error "Undefined" tadi
        $hero_galleries = HeroSlide::latest()->get();

        // Kirim semua variabel ke View 'home'
        return view('home', compact('stats', 'latest_articles', 'home_galleries', 'hero_galleries'));
    }
}
