
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\OfflineTransactionController;
use App\Http\Controllers\Api\OnlineTransactionController;
use App\Http\Controllers\Api\TransactionHistoryController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Controllers\Api\AdminSettingsController;

// DATA TENANT
Route::get('/tenants', [TenantController::class, 'index']);
Route::get('/tenants/{id}', [TenantController::class, 'show']);

// AKSES KHUSUS ADMIN / KASIR UTAMA
Route::middleware(['web', EnsureAdmin::class])->group(function () {

    // MANAJEMEN DATA TENANT
    Route::post('/tenants', [TenantController::class, 'store']);
    Route::put('/tenants/{id}', [TenantController::class, 'update']);
    Route::patch('/tenants/{id}/toggle-status', [
        TenantController::class, 'toggleStatus'
    ]);

    // DATA PELANGGAN
    Route::get('/admin/customers', [
        CustomerController::class, 'index'
    ]);

    Route::patch('/admin/customers/{id}/toggle-status', [
        CustomerController::class, 'toggleStatus'
    ]);

    // TRANSAKSI OFFLINE
    Route::prefix('admin/offline-transactions')->group(function () {
        Route::get('/', [OfflineTransactionController::class, 'index']);
        Route::get('/search', [OfflineTransactionController::class, 'search']);
        Route::post('/{id}/confirm', [OfflineTransactionController::class, 'confirm']);
    });

    // MONITORING TRANSAKSI ONLINE
    Route::get('/admin/online-transactions', [
        OnlineTransactionController::class, 'index'
    ]);

    // RIWAYAT TRANSAKSI
    Route::get('/admin/transaction-history', [
        TransactionHistoryController::class, 'index'
    ]);

    // LAPORAN PENJUALAN
    Route::get('/admin/reports', [
        ReportController::class, 'index'
    ]);

    // PENGATURAN ADMIN
    Route::get('/admin/settings', [
        AdminSettingsController::class, 'index'
    ]);

    Route::put('/admin/settings', [
        AdminSettingsController::class, 'update'
    ]);

    Route::post('/admin/change-password', [
        AdminSettingsController::class, 'changePassword'
    ]);
});
