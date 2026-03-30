<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockStatusController;

Route::get('/', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.perform')
    ->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('inventories', InventoryController::class);
    Route::resource('stock-in', StockInController::class)->only(['index', 'create', 'store']);
    Route::resource('stock-out', StockOutController::class)->only(['index', 'create', 'store']);

    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
    Route::get('/stock-status', [StockStatusController::class, 'index'])->name('stock-status.index');
    Route::put('/stock-status/{inventory}', [StockStatusController::class, 'update'])->name('stock-status.update');
});
