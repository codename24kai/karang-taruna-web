<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        // 1. Cek Pengaduan Pending
        $pengaduan = Pengaduan::where('status', 'Pending')->latest()->get();

        // 2. Cek Proposal Menunggu
        $proposal = Pengajuan::where('status', 'Menunggu')->latest()->get();

        // 3. Gabungin Data
        $notifications = [];

        foreach($pengaduan as $p) {
            $notifications[] = [
                'id' => 'pengaduan-' . $p->id, //
                'type' => 'pengaduan',
                'title' => 'Pengaduan Baru',
                'desc' => 'Dari: ' . $p->nama,
                'url' => url('/admin/pengaduan'),
                'icon' => '⚠️',
                'bg' => '#fee2e2',
                'color' => 'red'
            ];
        }

        foreach($proposal as $p) {
            $notifications[] = [
                'id' => 'proposal-' . $p->id, //
                'type' => 'proposal',
                'title' => 'Proposal Masuk',
                'desc' => 'Judul: ' . $p->judul,
                'url' => url('/admin/proposal'),
                'icon' => '📄',
                'bg' => '#ecfdf5',
                'color' => 'green'
            ];
        }

        // Return data JSON biar bisa dibaca JS
        return response()->json([
            'total' => count($notifications),
            'data' => $notifications
        ]);
    }
}
