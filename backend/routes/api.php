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
use App\Http\Controllers\Api\GoogleCustomerAuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\MenuController;

Route::get('/tenants', [TenantController::class, 'index']);
Route::get('/tenants/{id}', [TenantController::class, 'show']);

Route::post('/auth/google', [GoogleCustomerAuthController::class, 'login']);

Route::get('/menus', [MenuController::class, 'index']);
Route::get('/menus/{id}', [MenuController::class, 'show']);

// Semua endpoint keranjang membutuhkan login.
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/items', [CartController::class, 'store']);
    Route::put('/cart/items/{id}', [CartController::class, 'update']);
    Route::delete('/cart/items/{id}', [CartController::class, 'destroy']);
});

Route::middleware(['web', EnsureAdmin::class])->group(function () {
    Route::post('/tenants', [TenantController::class, 'store']);
    Route::put('/tenants/{id}', [TenantController::class, 'update']);
    Route::patch('/tenants/{id}/toggle-status', [TenantController::class, 'toggleStatus']);

    Route::get('/admin/customers', [CustomerController::class, 'index']);
    Route::patch('/admin/customers/{id}/toggle-status', [CustomerController::class, 'toggleStatus']);

    Route::prefix('admin/offline-transactions')->group(function () {
        Route::get('/', [OfflineTransactionController::class, 'index']);
        Route::get('/search', [OfflineTransactionController::class, 'search']);
        Route::post('/{id}/confirm', [OfflineTransactionController::class, 'confirm']);
    });

    Route::get('/admin/online-transactions', [OnlineTransactionController::class, 'index']);
    Route::get('/admin/transaction-history', [TransactionHistoryController::class, 'index']);
    Route::get('/admin/reports', [ReportController::class, 'index']);

    Route::get('/admin/settings', [AdminSettingsController::class, 'index']);
    Route::put('/admin/settings', [AdminSettingsController::class, 'update']);
    Route::post('/admin/change-password', [AdminSettingsController::class, 'changePassword']);
});