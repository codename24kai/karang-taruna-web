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
    Schema::create('pengajuans', function (Blueprint $table) {
        $table->id();
        $table->string('proposal_number')->unique(); // Contoh: PRO-20251020-5678
        $table->string('nama_pengaju');
        $table->string('kontak');
        $table->string('judul');
        $table->text('ringkasan');
        $table->decimal('anggaran', 15, 2); // Angka duit gede (sampai triliun aman)
        $table->string('pic'); // Penanggung Jawab
        $table->string('file_proposal'); // File PDF
        $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak', 'Revisi'])->default('Menunggu');
        $table->text('catatan_admin')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
