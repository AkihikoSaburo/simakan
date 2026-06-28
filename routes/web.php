<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DapurController;
use App\Http\Controllers\BangsalController;

use App\Http\Controllers\Superadmin\AdministratorController;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\DatabaseController;
use App\Http\Controllers\Superadmin\ProfileController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminBangsalController;
use App\Http\Controllers\Admin\SettingsController;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // Super Admin Routes
    Route::prefix('superadmin')->middleware('role:superadmin')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'dashboard'])
            ->name('superadmin.dashboard');

        Route::post('/administrators', [AdministratorController::class, 'store'])
            ->name('superadmin.administrators.store');

        Route::put('/administrators/{administrator}', [AdministratorController::class, 'update'])
            ->name('superadmin.administrators.update');

        Route::delete('/administrators/{administrator}', [AdministratorController::class, 'destroy'])
            ->name('superadmin.administrators.destroy');

        Route::get('/database/backup', [DatabaseController::class, 'backup'])
            ->name('superadmin.database.backup');

        Route::post('/database/restore', [DatabaseController::class, 'restore'])
            ->name('superadmin.database.restore');

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('superadmin.profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('superadmin.profile.update');
    });

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');

        // Manage Dapur & Bangsal users
        Route::resource('/admin/users', AdminController::class)->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);

        Route::get('/admin/arsip/orders/{order}', [AdminBangsalController::class, 'arsipOrderDetail'])
            ->name('admin.arsip.orders.show');

        // Manage Bangsals
        Route::resource('/admin/bangsals', AdminBangsalController::class)->names([
            'index' => 'admin.bangsals.index',
            'create' => 'admin.bangsals.create',
            'store' => 'admin.bangsals.store',
            'edit' => 'admin.bangsals.edit',
            'update' => 'admin.bangsals.update',
            'destroy' => 'admin.bangsals.destroy',
        ]);

        // System Settings
        Route::get('/admin/settings', [SettingsController::class, 'edit'])
            ->name('admin.settings.edit');
        Route::put('/admin/settings', [SettingsController::class, 'update'])
            ->name('admin.settings.update');

        Route::get('/admin/arsip/bangsals', [AdminBangsalController::class, 'arsipIndex'])
            ->name('admin.bangsals.arsip');
        Route::get('/admin/arsip/bangsals/{id}', [AdminBangsalController::class, 'arsipShow'])
            ->name('admin.bangsals.arsip.show');
    });

    // Bangsal Routes (Admin & Superadmin Bisa Akses)
    Route::middleware('role:bangsal,admin,superadmin')->group(function () {
        Route::get('/bangsal/dashboard', [BangsalController::class, 'dashboard'])
            ->name('bangsal.dashboard');
        Route::get('/bangsal/orders/create', [BangsalController::class, 'create'])
            ->name('bangsal.orders.create');
        Route::get('/bangsal/cari-pasien', [BangsalController::class, 'cariPasien'])
            ->name('bangsal.pasien.cari');
        Route::post('/bangsal/orders', [BangsalController::class, 'store'])
            ->name('bangsal.orders.store');
        Route::get('/bangsal/orders/{order}', [BangsalController::class, 'show'])
            ->name('bangsal.orders.show');
        Route::get('/bangsal/orders/{order}/pdf', [BangsalController::class, 'exportSingleOrderPdf'])
            ->name('bangsal.orders.pdf');
        Route::get('/bangsal/orders/{order}/edit', [BangsalController::class, 'edit'])
            ->name('bangsal.orders.edit');
        Route::put('/bangsal/orders/{order}', [BangsalController::class, 'update'])
            ->name('bangsal.orders.update');
        Route::get('/bangsal/riwayat', [BangsalController::class, 'riwayat'])
            ->name('bangsal.riwayat');
    });

    // Dapur Routes (Admin & Superadmin Bisa Akses)
    Route::middleware('role:dapur,admin,superadmin')->group(function () {
        Route::get('/dapur/dashboard', [DapurController::class, 'dashboard'])
            ->name('dapur.dashboard');
        Route::get('/dapur/orders/{order}', [DapurController::class, 'show'])
            ->name('dapur.orders.show');
        Route::get('/dapur/orders/{order}/pdf', [DapurController::class, 'exportSingleOrderPdf'])
            ->name('dapur.orders.pdf');
        Route::get('/dapur/history', [DapurController::class, 'history'])
            ->name('dapur.history');
        Route::get('/dapur/history/pdf/{date}', [DapurController::class, 'exportDailyPdf'])
            ->name('dapur.history.pdf');
    });
});

Route::redirect('/', '/login');