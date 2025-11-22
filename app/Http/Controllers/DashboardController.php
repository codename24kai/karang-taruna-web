<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Pengajuan;
use App\Models\Gallery;
use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Counter (Tetap sama)
        $stats = [
            'pengaduan' => Pengaduan::count(),
            'proposal' => Pengajuan::count(),
            'galeri' => Gallery::count(),
            'artikel' => Article::count(),
        ];

        // 2. Data Buat Slider (Carousel)
        $slide_pengaduan = Pengaduan::latest()->take(5)->get();
        $slide_proposal = Pengajuan::latest()->take(5)->get();
        $slide_galeri = Gallery::latest()->take(4)->get();
        $slide_artikel = Article::latest()->take(3)->get();

        // 3. Logic "Aktivitas Terkini" (Gabungin semua data jadi satu timeline)
        $activities = collect();

        // Masukin Pengaduan Terbaru
        foreach($slide_pengaduan as $p) {
            $activities->push([
                'type' => 'pengaduan',
                'icon' => '⚠️',
                'color' => '#fee2e2', // Merah muda
                'text_color' => '#dc2626',
                'title' => 'Pengaduan Baru',
                'desc' => Str::limit($p->judul, 30),
                'time' => $p->created_at,
                'url' => url('/admin/pengaduan')
            ]);
        }

        // Masukin Proposal Terbaru
        foreach($slide_proposal as $p) {
            $activities->push([
                'type' => 'proposal',
                'icon' => '📄',
                'color' => '#ecfdf5', // Hijau muda
                'text_color' => '#059669',
                'title' => 'Proposal Masuk',
                'desc' => Str::limit($p->judul, 30),
                'time' => $p->created_at,
                'url' => url('/admin/proposal')
            ]);
        }

        // Masukin Galeri Terbaru
        foreach($slide_galeri as $g) {
            $activities->push([
                'type' => 'galeri',
                'icon' => '📷',
                'color' => '#fef3c7', // Kuning muda
                'text_color' => '#d97706',
                'title' => 'Galeri Diupdate',
                'desc' => Str::limit($g->caption, 30),
                'time' => $g->created_at,
                'url' => url('/admin/galeri')
            ]);
        }

        // Urutkan dari yang paling baru & ambil 5 teratas
        $recent_activities = $activities->sortByDesc('time')->take(5);

        return view('admin.dashboard', compact(
            'stats',
            'slide_pengaduan',
            'slide_proposal',
            'slide_galeri',
            'slide_artikel',
            'recent_activities'
        ));
    }
}
