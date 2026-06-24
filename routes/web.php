<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DapurController;
use App\Http\Controllers\BangsalController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/superadmin/dashboard', function () {
        return view('superadmin.dashboard');
    })->name('superadmin.dashboard');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/bangsal/dashboard', [BangsalController::class, 'dashboard'])->name('bangsal.dashboard');

    Route::get('/bangsal/orders/create', [BangsalController::class, 'create'])->name('bangsal.orders.create');

    Route::get('/bangsal/cari-pasien', [BangsalController::class, 'cariPasien'])->name('bangsal.pasien.cari');

    Route::post('/bangsal/orders', [BangsalController::class, 'store'])->name('bangsal.orders.store');

    Route::get('/bangsal/orders/{order}', [BangsalController::class, 'show'])->name('bangsal.orders.show');

    Route::get('/bangsal/orders/{order}/pdf', [BangsalController::class, 'exportPdf'])->name('bangsal.orders.pdf');

    Route::get('/bangsal/orders/{order}/edit', [BangsalController::class, 'edit'])->name('bangsal.orders.edit');

    Route::put('/bangsal/orders/{order}', [BangsalController::class, 'update'])->name('bangsal.orders.update');

    Route::get('/dapur/dashboard', [DapurController::class, 'dashboard'])->name('dapur.dashboard');

    Route::get('/dapur/orders/{order}', [DapurController::class, 'show'])->name('dapur.orders.show');

    Route::get('/dapur/history', [DapurController::class, 'history'])->name('dapur.history');
});

Route::redirect('/', '/login');