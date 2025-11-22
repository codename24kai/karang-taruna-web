<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('title');           // Judul Artikel
        $table->string('slug')->unique();  // Link ramah URL (misal: lomba-17-agustus)
        $table->string('category');        // Kategori (Berita, Kegiatan, Pengumuman)
        $table->text('content');           // Isi lengkap
        $table->text('excerpt')->nullable(); // Ringkasan pendek buat di kartu depan
        $table->string('image')->nullable(); // Path gambar/foto
        $table->string('author')->default('Admin'); // Penulis
        $table->date('published_at');      // Tanggal terbit
        $table->timestamps();              // Otomatis bikin created_at & updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
