<?php

use Illuminate\Support\Facades\Route;

// Users Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PengajuanController;

// Admin Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PengaduanController as AdminPengaduanController;
use App\Http\Controllers\Admin\PengajuanController as AdminPengajuanController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\UserController; // Ditambahin use biar konsisten
use App\Http\Controllers\Admin\SettingController; // Ditambahin use biar konsisten

use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Guest)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index']);
Route::get('/informasi', [ArticleController::class, 'index']);
Route::get('/galeri', [GalleryController::class, 'index']);

// --- PENGADUAN ---
Route::get('/pengaduan', [PengaduanController::class, 'index']);
Route::post('/pengaduan', [PengaduanController::class, 'store']);

// --- PENGAJUAN ---
Route::get('/pengajuan', [PengajuanController::class, 'index']);
Route::post('/pengajuan', [PengajuanController::class, 'store']);

// --- TRACKING API ---
Route::get('/api/track-pengaduan', [PengaduanController::class, 'track']);
Route::get('/api/track-pengajuan', [PengajuanController::class, 'track']);

/*
|--------------------------------------------------------------------------
| ADMIN AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ADMIN ROUTES (Middleware Auth)
|--------------------------------------------------------------------------
| Semua route di bawah ini butuh login dulu baru bisa diakses.
*/

Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 1. KELOLA PENGADUAN
    Route::get('/admin/pengaduan', [AdminPengaduanController::class, 'index']);
    Route::put('/admin/pengaduan/{id}', [AdminPengaduanController::class, 'update']);
    Route::delete('/admin/pengaduan/{id}', [AdminPengaduanController::class, 'destroy']);

    // 2. KELOLA PROPOSAL
    Route::get('/admin/proposal', [AdminPengajuanController::class, 'index']);
    Route::put('/admin/proposal/{id}', [AdminPengajuanController::class, 'update']);
    Route::delete('/admin/proposal/{id}', [AdminPengajuanController::class, 'destroy']);

    // 3. KELOLA GALERI
    Route::get('/admin/galeri', [AdminGalleryController::class, 'index']);
    Route::post('/admin/galeri', [AdminGalleryController::class, 'store']);
    Route::put('/admin/galeri/{id}', [AdminGalleryController::class, 'update']);
    Route::delete('/admin/galeri/{id}', [AdminGalleryController::class, 'destroy']);

    // 4. KELOLA ARTIKEL
    Route::get('/admin/artikel', [AdminArticleController::class, 'index']);
    Route::post('/admin/artikel', [AdminArticleController::class, 'store']);
    Route::put('/admin/artikel/{id}', [AdminArticleController::class, 'update']);
    Route::delete('/admin/artikel/{id}', [AdminArticleController::class, 'destroy']);

    // 5. KELOLA PENGGUNA
    Route::get('/admin/pengguna', [UserController::class, 'index']);
    Route::post('/admin/pengguna', [UserController::class, 'store']);
    Route::put('/admin/pengguna/{id}', [UserController::class, 'update']);
    Route::delete('/admin/pengguna/{id}', [UserController::class, 'destroy']);

    // 6. PENGATURAN
    Route::get('/admin/pengaturan', [SettingController::class, 'index']);
    Route::post('/admin/pengaturan', [SettingController::class, 'update']);

    // PENGATURAN & HERO
    Route::get('/admin/pengaturan', [App\Http\Controllers\Admin\SettingController::class, 'index']);
    Route::post('/admin/pengaturan', [App\Http\Controllers\Admin\SettingController::class, 'update']);

    // Route Baru Hero Slide
    Route::post('/admin/pengaturan/hero', [App\Http\Controllers\Admin\SettingController::class, 'uploadHero']);
    Route::delete('/admin/pengaturan/hero/{id}', [App\Http\Controllers\Admin\SettingController::class, 'deleteHero']);

    // 7. NOTIFIKASI REALTIME
    Route::get('/admin/api/notifications', [NotificationController::class, 'getNotifications']);

    // Cuma admin yang login yang bisa clear cache
    Route::get('/admin/nuke-cache', function() {
        Artisan::call('optimize:clear');
        return redirect()->back()->with('success', 'Cache berhasil diledakkan! 💥');
    });
});
