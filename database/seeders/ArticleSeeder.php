<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        // Kita isi 3 data contoh
        $data = [
            [
                'title' => 'Rapat Koordinasi Bulanan Karang Taruna',
                'slug' => 'rapat-koordinasi-bulanan',
                'category' => 'Kegiatan',
                'content' => 'Ini adalah isi lengkap berita tentang rapat koordinasi bulanan...',
                'excerpt' => 'Rapat rutin untuk membahas program kerja bulan depan.',
                'image' => null, // Nanti kita urus upload gambar
                'author' => 'Admin',
                'published_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Lomba 17 Agustus Tingkat RW 013',
                'slug' => 'lomba-17-agustus-rw-013',
                'category' => 'Pengumuman',
                'content' => 'Daftar lomba: Panjat pinang, makan kerupuk, balap karung...',
                'excerpt' => 'Ayo ramaikan kemerdekaan dengan mengikuti berbagai lomba seru!',
                'image' => null,
                'author' => 'Panitia',
                'published_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Kerja Bakti Membersihkan Saluran Air',
                'slug' => 'kerja-bakti-saluran-air',
                'category' => 'Berita',
                'content' => 'Warga RW 013 bergotong royong membersihkan got...',
                'excerpt' => 'Kegiatan gotong royong untuk mencegah banjir di musim hujan.',
                'image' => null,
                'author' => 'Humas',
                'published_at' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('articles')->insert($data);
    }
}
