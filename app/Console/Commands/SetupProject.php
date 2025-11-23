<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupProject extends Command
{
    /**
     * Nama command buat dipanggil di terminal.
     * Cara pakainya: php artisan app:setup-fresh
     */
    protected $signature = 'app:setup-fresh {--force : Paksa jalan tanpa konfirmasi}';

    /**
     * Deskripsi command.
     */
    protected $description = 'Reset database (fresh migration), seed data, link storage, dan clear cache.';

    /**
     * Eksekusi command.
     */
    public function handle()
    {
        $this->warn('⚠️  PERINGATAN: Command ini bakal MENGHAPUS SEMUA DATA di database!');

        // Minta konfirmasi dulu biar gak kepencet
        if (!$this->option('force') && !$this->confirm('Yakin mau lanjut? Data bakal ilang loh!')) {
            $this->info('Operasi dibatalkan. Aman bestie.');
            return;
        }

        $this->info('🚀 Mulai setup project...');

        // 1. Storage Link
        $this->info('🔗 Linking storage...');
        Artisan::call('storage:link');
        $this->info('✅ Storage linked.');

        // 2. Clear Cache (Nuke Cache)
        $this->info('💥 Nuke cache...');
        Artisan::call('optimize:clear');
        $this->info('✅ Cache cleared.');

        // 3. Migrate Fresh & Seed
        $this->info('🌱 Migrating database & seeding...');
        // Pakai --force biar jalan di production kalau emang niat
        Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        $this->info('✅ Database berhasil di-reset dan di-seed!');
        $this->info('🎉 SETUP SELESAI! Web udah siap digunakan.');
    }
}
