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
       Schema::create('pengaduans', function (Blueprint $table) {
        $table->id();
        $table->string('ticket_number')->unique(); // Contoh: CTR-20251020-1234
        $table->string('nama');
        $table->string('kontak');      // HP atau Email
        $table->string('judul');
        $table->string('kategori');    // Infrastruktur, Lingkungan, dll
        $table->string('lokasi');
        $table->text('deskripsi');
        $table->string('lampiran')->nullable(); // Path file foto/pdf
        $table->enum('status', ['Pending', 'Diproses', 'Selesai', 'Ditolak'])->default('Pending');
        $table->text('tanggapan_admin')->nullable(); // Buat admin bales nanti
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
