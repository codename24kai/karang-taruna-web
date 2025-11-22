<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        // Kita pakai gambar dummy dari Unsplash biar cantik
        $data = [
            [
                'caption' => 'Kerja Bakti Lingkungan RT 006',
                // Pura-puranya ini array gambar
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1558008258-3256797b43f3?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1598335624129-27294cf63ea6?auto=format&fit=crop&w=800&q=80'
                ]),
                'date' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'caption' => 'Malam Puncak HUT RI ke-79',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1514525253440-b393452e3383?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1533174072545-e8d4aa97edf9?auto=format&fit=crop&w=800&q=80'
                ]),
                'date' => Carbon::now()->subMonth(1),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'caption' => 'Rapat Pembentukan Panitia',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?auto=format&fit=crop&w=800&q=80'
                ]),
                'date' => Carbon::now()->subMonth(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('galleries')->insert($data);
    }
}
