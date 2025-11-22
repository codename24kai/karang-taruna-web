<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // 1. Bikin Akun Admin
    \App\Models\User::create([
        'name' => 'Super Admin',
        'username' => 'admin',
        'password' => bcrypt('admin123'),
        'role' => 'super_admin'
    ]);

    // 2. Panggil Seeder Lain
    $this->call([
        ArticleSeeder::class,
        GallerySeeder::class,
        // Tambahin seeder lain kalau ada
    ]);
    }
}
