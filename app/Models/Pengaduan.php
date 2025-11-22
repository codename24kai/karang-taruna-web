<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory;

    // Tambahin ini biar Laravel ngebolehin kita isi kolom-kolom ini
    protected $fillable = [
        'ticket_number',
        'nama',
        'kontak',
        'judul',
        'kategori',
        'lokasi',
        'deskripsi',
        'lampiran',
        'status',
        'tanggapan_admin'
    ];
}
