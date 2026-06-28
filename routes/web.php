<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DapurController;
use App\Http\Controllers\BangsalController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // Superadmin Routes
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/superadmin/dashboard', [\App\Http\Controllers\SuperadminController::class, 'dashboard'])->name('superadmin.dashboard');
        Route::resource('/superadmin/admins', \App\Http\Controllers\SuperadminController::class)->names([
            'index' => 'superadmin.admins.index',
            'create' => 'superadmin.admins.create',
            'store' => 'superadmin.admins.store',
            'edit' => 'superadmin.admins.edit',
            'update' => 'superadmin.admins.update',
            'destroy' => 'superadmin.admins.destroy',
        ]);
    });

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Manage Dapur & Bangsal users
        Route::resource('/admin/users', \App\Http\Controllers\AdminController::class)->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);

        // Manage Bangsals
        Route::resource('/admin/bangsals', \App\Http\Controllers\AdminBangsalController::class)->names([
            'index' => 'admin.bangsals.index',
            'create' => 'admin.bangsals.create',
            'store' => 'admin.bangsals.store',
            'edit' => 'admin.bangsals.edit',
            'update' => 'admin.bangsals.update',
            'destroy' => 'admin.bangsals.destroy',
        ]);

        // System Settings
        Route::get('/admin/settings', [\App\Http\Controllers\AdminSettingsController::class, 'edit'])->name('admin.settings.edit');
        Route::put('/admin/settings', [\App\Http\Controllers\AdminSettingsController::class, 'update'])->name('admin.settings.update');
    });

    // Bangsal Routes
    Route::middleware('role:bangsal')->group(function () {
        Route::get('/bangsal/dashboard', [BangsalController::class, 'dashboard'])->name('bangsal.dashboard');
        Route::get('/bangsal/orders/create', [BangsalController::class, 'create'])->name('bangsal.orders.create');
        Route::get('/bangsal/cari-pasien', [BangsalController::class, 'cariPasien'])->name('bangsal.pasien.cari');
        Route::post('/bangsal/orders', [BangsalController::class, 'store'])->name('bangsal.orders.store');
        Route::get('/bangsal/orders/{order}', [BangsalController::class, 'show'])->name('bangsal.orders.show');
        Route::get('/bangsal/orders/{order}/pdf', [BangsalController::class, 'exportPdf'])->name('bangsal.orders.pdf');
        Route::get('/bangsal/orders/{order}/edit', [BangsalController::class, 'edit'])->name('bangsal.orders.edit');
        Route::put('/bangsal/orders/{order}', [BangsalController::class, 'update'])->name('bangsal.orders.update');
    });

    // Dapur Routes
    Route::middleware('role:dapur')->group(function () {
        Route::get('/dapur/dashboard', [DapurController::class, 'dashboard'])->name('dapur.dashboard');
        Route::get('/dapur/orders/{order}', [DapurController::class, 'show'])->name('dapur.orders.show');
        Route::get('/dapur/history', [DapurController::class, 'history'])->name('dapur.history');
        Route::get('/dapur/history/pdf/{date}', [DapurController::class, 'exportDailyPdf'])->name('dapur.history.pdf');
    });
});

Route::redirect('/', '/login');

Route::get('/run-migrations', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return '<div style="font-family: sans-serif; padding: 2rem; max-width: 600px; margin: 40px auto; border: 1px solid #DEF2FF; background: #FEFDFF; rounded-corners: 12px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">'
             . '<h1 style="color: #008DE1; margin-top: 0;">Migrasi Berhasil! 🎉</h1>'
             . '<p style="color: #475569; font-size: 14px;">Tabel konfigurasi settings dan skema database lainnya telah berhasil dibuat.</p>'
             . '<pre style="background: #f1f5f9; padding: 12px; border-radius: 8px; font-size: 12px; color: #31363F; overflow-x: auto;">' . e(\Illuminate\Support\Facades\Artisan::output()) . '</pre>'
             . '<a href="/login" style="display: inline-block; padding: 10px 20px; background: #008DE1; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px;">Kembali ke Login</a>'
             . '</div>';
    } catch (\Exception $e) {
        return '<div style="font-family: sans-serif; padding: 2rem; max-width: 600px; margin: 40px auto; border: 1px solid #fecaca; background: #fff5f5; border-radius: 12px;">'
             . '<h1 style="color: #dc2626; margin-top: 0;">Migrasi Gagal! ❌</h1>'
             . '<p style="color: #7f1d1d;">Error: ' . e($e->getMessage()) . '</p>'
             . '</div>';
    }
});