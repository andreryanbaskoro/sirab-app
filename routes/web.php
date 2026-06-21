<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Tukang;
use App\Http\Controllers\Konsumen;

// Auth Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Middleware Auth
Route::middleware(['auth'])->group(function () {
    
    // API Endpoints
    Route::get('/api/pekerjaan/{id}/materials', [\App\Http\Controllers\Api\PekerjaanApiController::class, 'getMaterials'])->name('api.pekerjaan.materials');

    // Redirect role
    Route::get('/home', HomeController::class)->name('home');

    // Global Notifications
    Route::get('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/{id}/read', [App\Http\Controllers\NotifikasiController::class, 'read'])->name('notifikasi.read');
    Route::post('/notifikasi/mark-all-read', [App\Http\Controllers\NotifikasiController::class, 'markAllRead'])->name('notifikasi.markAllRead');

    // ADMIN PU ROUTES
    Route::middleware(['role:admin_pu'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Profil Admin
        Route::get('/profil', [Admin\ProfileController::class, 'index'])->name('profil');
        Route::get('/profil/edit', [Admin\ProfileController::class, 'edit'])->name('profil.edit');
        Route::post('/profil', [Admin\ProfileController::class, 'update'])->name('profil.update');
        
        // Data Master
        Route::resource('konsumen', Admin\KonsumenController::class);
        Route::resource('tukang', Admin\TukangController::class);
        Route::resource('tipe-rumah', Admin\TipeRumahController::class);
        Route::resource('material', Admin\MaterialController::class);
        Route::resource('kategori-pekerjaan', Admin\KategoriPekerjaanController::class)->except(['create', 'show', 'edit']);
        Route::resource('pekerjaan', Admin\PekerjaanController::class);
        Route::resource('harga-jasa-tukang', Admin\HargaJasaTukangController::class)->only(['index']);
        
        // Transaksi & Laporan
        Route::get('permintaan', [Admin\PermintaanController::class, 'index'])->name('permintaan.index');
        Route::get('permintaan/{permintaan}', [Admin\PermintaanController::class, 'show'])->name('permintaan.show');
        
        Route::get('rab', [Admin\RabController::class, 'index'])->name('rab.index');
        Route::get('rab/{rab}', [Admin\RabController::class, 'show'])->name('rab.show');
        
        Route::get('kontrak', [Admin\KontrakController::class, 'index'])->name('kontrak.index');
        Route::get('kontrak/{kontrak}', [Admin\KontrakController::class, 'show'])->name('kontrak.show');
        
        Route::get('laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export-excel', [Admin\LaporanController::class, 'exportExcel'])->name('laporan.export-excel');
        Route::get('laporan/export-pdf', [Admin\LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
    });

    // KEPALA TUKANG ROUTES
    Route::middleware(['role:kepala_tukang'])->prefix('tukang')->name('tukang.')->group(function () {
        Route::get('/dashboard', [Tukang\DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/profil', [Tukang\ProfileController::class, 'index'])->name('profil');
        Route::get('/profil/edit', [Tukang\ProfileController::class, 'edit'])->name('profil.edit');
        Route::post('/profil', [Tukang\ProfileController::class, 'update'])->name('profil.update');
        
        Route::get('/permintaan', [Tukang\PermintaanController::class, 'index'])->name('permintaan.index');
        Route::get('/permintaan/{permintaan}', [Tukang\PermintaanController::class, 'show'])->name('permintaan.show');
        Route::post('/permintaan/{permintaan}/terima', [Tukang\PermintaanController::class, 'terima'])->name('permintaan.terima');
        Route::post('/permintaan/{permintaan}/tolak', [Tukang\PermintaanController::class, 'tolak'])->name('permintaan.tolak');
        
        Route::get('/rab', [Tukang\RabController::class, 'index'])->name('rab.index');
        Route::get('/rab/create/{permintaan}', [Tukang\RabController::class, 'create'])->name('rab.create');
        Route::post('/rab', [Tukang\RabController::class, 'store'])->name('rab.store');
        Route::get('/rab/{rab}', [Tukang\RabController::class, 'show'])->name('rab.show');
        Route::post('/rab/{rab}/submit', [Tukang\RabController::class, 'submit'])->name('rab.submit');
        Route::get('/rab/{rab}/pdf', [Tukang\RabController::class, 'cetakPdf'])->name('rab.pdf');

        // Data Anggaran
        Route::get('/anggaran', [Tukang\AnggaranController::class, 'index'])->name('anggaran.index');

        // CRUD Harga Material (oleh kepala tukang) - Diubah menjadi Read-Only (Hanya Admin PU yang bisa CRUD)
        // Route::post('/anggaran/material', [Tukang\AnggaranController::class, 'storeMaterial'])->name('anggaran.material.store');
        // Route::put('/anggaran/material/{hargaMaterial}', [Tukang\AnggaranController::class, 'updateMaterial'])->name('anggaran.material.update');
        // Route::delete('/anggaran/material/{hargaMaterial}', [Tukang\AnggaranController::class, 'destroyMaterial'])->name('anggaran.material.destroy');

        // CRUD Harga Pekerjaan (oleh kepala tukang)
        Route::post('anggaran/pekerjaan', [Tukang\AnggaranController::class, 'storePekerjaan'])->name('anggaran.pekerjaan.store');
        Route::put('anggaran/pekerjaan/{pekerjaan}', [Tukang\AnggaranController::class, 'updatePekerjaan'])->name('anggaran.pekerjaan.update');
        Route::delete('anggaran/pekerjaan/{pekerjaan}', [Tukang\AnggaranController::class, 'destroyPekerjaan'])->name('anggaran.pekerjaan.destroy');

        // CRUD Harga Jasa Tukang (milik sendiri)
        Route::post('/anggaran/jasa', [Tukang\AnggaranController::class, 'storeJasa'])->name('anggaran.jasa.store');
        Route::put('/anggaran/jasa/{hargaJasaTukang}', [Tukang\AnggaranController::class, 'updateJasa'])->name('anggaran.jasa.update');
        Route::delete('/anggaran/jasa/{hargaJasaTukang}', [Tukang\AnggaranController::class, 'destroyJasa'])->name('anggaran.jasa.destroy');

        // Proyek Aktif / Penyelesaian
        Route::get('/proyek', [Tukang\ProyekController::class, 'index'])->name('proyek.index');
        Route::get('/proyek/{proyek}', [Tukang\ProyekController::class, 'show'])->name('proyek.show');
        Route::post('/proyek/{proyek}/ajukan-selesai', [Tukang\ProyekController::class, 'ajukanSelesai'])->name('proyek.ajukan-selesai');
        Route::post('/pembayaran/{pembayaran}/verifikasi', [Tukang\ProyekController::class, 'verifikasiPembayaran'])->name('pembayaran.verifikasi');

        // Riwayat
        Route::get('/riwayat', [Tukang\RiwayatController::class, 'index'])->name('riwayat.index');
    });

    // KONSUMEN ROUTES
    Route::middleware(['role:konsumen'])->prefix('konsumen')->name('konsumen.')->group(function () {
        Route::get('/dashboard', [Konsumen\DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/profil', [Konsumen\ProfileController::class, 'index'])->name('profil');
        Route::get('/profil/edit', [Konsumen\ProfileController::class, 'edit'])->name('profil.edit');
        Route::post('/profil', [Konsumen\ProfileController::class, 'update'])->name('profil.update');
        
        Route::get('/cari-tukang', [Konsumen\PermintaanController::class, 'cariTukang'])->name('cari-tukang');
        
        Route::get('/permintaan', [Konsumen\PermintaanController::class, 'index'])->name('permintaan.index');
        Route::get('/permintaan/create', [Konsumen\PermintaanController::class, 'create'])->name('permintaan.create');
        Route::post('/permintaan', [Konsumen\PermintaanController::class, 'store'])->name('permintaan.store');
        Route::get('/permintaan/{permintaan}', [Konsumen\PermintaanController::class, 'show'])->name('permintaan.show');
        
        // Modul Pembiayaan / Hasil RAB
        Route::get('/pembiayaan', [Konsumen\PembiayaanController::class, 'index'])->name('pembiayaan.index');
        Route::get('/pembiayaan/{rab}', [Konsumen\PembiayaanController::class, 'show'])->name('pembiayaan.show');
        Route::post('/pembiayaan/{rab}/setujui', [Konsumen\PembiayaanController::class, 'setujui'])->name('pembiayaan.setujui');
        Route::post('/pembiayaan/{rab}/tolak', [Konsumen\PembiayaanController::class, 'tolak'])->name('pembiayaan.tolak');
        Route::get('/pembiayaan/{rab}/download-rab', [Konsumen\PembiayaanController::class, 'downloadRab'])->name('pembiayaan.download-rab');
        Route::get('/pembiayaan/{rab}/download-kontrak', [Konsumen\PembiayaanController::class, 'downloadKontrak'])->name('pembiayaan.download-kontrak');

        // Proyek Aktif / Pembayaran
        Route::get('/proyek', [Konsumen\ProyekController::class, 'index'])->name('proyek.index');
        Route::get('/proyek/{proyek}', [Konsumen\ProyekController::class, 'show'])->name('proyek.show');
        Route::post('/proyek/{proyek}/bayar', [Konsumen\ProyekController::class, 'uploadPembayaran'])->name('proyek.bayar');
        Route::post('/proyek/{proyek}/selesai', [Konsumen\ProyekController::class, 'setujuiSelesai'])->name('proyek.selesai');

        // Riwayat
        Route::get('/riwayat', [Konsumen\RiwayatController::class, 'index'])->name('riwayat.index');
    });

});
