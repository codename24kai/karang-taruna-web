<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Kita izinkan 'key' dan 'value' untuk diisi massal
    protected $fillable = ['key', 'value'];

    // PENTING: Jangan tambahkan primaryKey = 'key' di sini.
    // Biarkan default (id) saja biar aman dan gak bentrok.
}
