<?php

use Illuminate\Support\Facades\Route;

// Users
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\HomeController;

// Admin
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PengaduanController as AdminPengaduanController;
use App\Http\Controllers\Admin\PengajuanController as AdminPengajuanController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\NotificationController;

//USER

Route::get('/', [HomeController::class, 'index']);

// Route sementara biar gak 404 kalau diklik
Route::get('/informasi', [ArticleController::class, 'index']);
Route::get('/galeri', [GalleryController::class, 'index']);

// --- ROUTE PENGADUAN ---
Route::get('/pengaduan', [PengaduanController::class, 'index']); // Tampilkan Form
Route::post('/pengaduan', [PengaduanController::class, 'store']); // Kirim Data

// --- ROUTE PENGAJUAN ---
Route::get('/pengajuan', [PengajuanController::class, 'index']);
Route::post('/pengajuan', [PengajuanController::class, 'store']);

// --- API ROUTE BUAT TRACKING (Biar simple taruh sini dulu) ---
Route::get('/api/track-pengaduan', [PengaduanController::class, 'track']);
Route::get('/api/track-pengajuan', [PengajuanController::class, 'track']);




//ADMIN

// --- AUTH ROUTES ---
// ROUTE LOGIN WAJIB DI LUAR MIDDLEWARE AUTH
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {


// --- ADMIN DASHBOARD (Diproteksi Middleware Auth) ---
Route::middleware(['auth'])->group(function () {
    // Arahkan ke Controller Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- KELOLA PENGADUAN ---
    Route::get('/admin/pengaduan', [AdminPengaduanController::class, 'index']);
    Route::put('/admin/pengaduan/{id}', [AdminPengaduanController::class, 'update']);
    Route::delete('/admin/pengaduan/{id}', [AdminPengaduanController::class, 'destroy']);

});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PENGADUAN
    Route::get('/admin/pengaduan', [AdminPengaduanController::class, 'index']);
    Route::put('/admin/pengaduan/{id}', [AdminPengaduanController::class, 'update']);
    Route::delete('/admin/pengaduan/{id}', [AdminPengaduanController::class, 'destroy']);

    // PROPOSAL (Baru)
    Route::get('/admin/proposal', [AdminPengajuanController::class, 'index']);
    Route::put('/admin/proposal/{id}', [AdminPengajuanController::class, 'update']);
    Route::delete('/admin/proposal/{id}', [AdminPengajuanController::class, 'destroy']);

    // GALERI (Baru)
    Route::get('/admin/galeri', [AdminGalleryController::class, 'index']);
    Route::post('/admin/galeri', [AdminGalleryController::class, 'store']);
    Route::put('/admin/galeri/{id}', [AdminGalleryController::class, 'update']);
    Route::delete('/admin/galeri/{id}', [AdminGalleryController::class, 'destroy']);
});

// --- KELOLA ARTIKEL ---
    Route::get('/admin/artikel', [AdminArticleController::class, 'index']);
    Route::post('/admin/artikel', [AdminArticleController::class, 'store']);
    Route::put('/admin/artikel/{id}', [AdminArticleController::class, 'update']);
    Route::delete('/admin/artikel/{id}', [AdminArticleController::class, 'destroy']);

    // PENGGUNA
    Route::get('/admin/pengguna', [App\Http\Controllers\Admin\UserController::class, 'index']);
    Route::post('/admin/pengguna', [App\Http\Controllers\Admin\UserController::class, 'store']);
    Route::put('/admin/pengguna/{id}', [App\Http\Controllers\Admin\UserController::class, 'update']);
    Route::delete('/admin/pengguna/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy']);

    // PENGATURAN
    Route::get('/admin/pengaturan', [App\Http\Controllers\Admin\SettingController::class, 'index']);
    Route::post('/admin/pengaturan', [App\Http\Controllers\Admin\SettingController::class, 'update']);

    // API KHUSUS NOTIFIKASI REALTIME
    Route::get('/admin/api/notifications', [NotificationController::class, 'getNotifications']);
});
