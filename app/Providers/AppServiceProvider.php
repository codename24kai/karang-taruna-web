<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Pengaduan;
use App\Models\Pengajuan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void    {
    // Logic ini cuma jalan pas buka halaman Admin
     View::composer('layouts.admin', function ($view) {

         // Hitung yang Pending/Menunggu
         $countPengaduan = Pengaduan::where('status', 'Pending')->count();
         $countProposal = Pengajuan::where('status', 'Menunggu')->count();
         $totalNotif = $countPengaduan + $countProposal;

         $view->with('global_notif', [
             'total' => $totalNotif,
             'pengaduan' => $countPengaduan,
             'proposal' => $countProposal
         ]);
     });
    }
}
