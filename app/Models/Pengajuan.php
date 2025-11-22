<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_number',
        'nama_pengaju',
        'kontak',
        'judul',
        'ringkasan',
        'anggaran',
        'pic',
        'file_proposal',
        'status',
        'catatan_admin'
    ];
}
