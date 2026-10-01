<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\InternalAdminMiddleware;
use App\Http\Controllers\Auth\InternalLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\TransaksiOfflineController;
use App\Http\Controllers\Admin\TransaksiOnlineController;
use App\Http\Controllers\Admin\RiwayatTransaksiController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Middleware\InternalTenantMiddleware;
use App\Http\Controllers\Tenant\DashboardTenantController;
use App\Http\Controllers\Tenant\TransaksiOfflineTenantController;

/*
|--------------------------------------------------------------------------
| HALAMAN AWAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login.pelanggan');
});

/*
|--------------------------------------------------------------------------
| LOGIN PELANGGAN
|--------------------------------------------------------------------------
*/

Route::get('/login/pelanggan', function () {
    return view('auth.login-pelanggan');
})->name('login.pelanggan');

/*
|--------------------------------------------------------------------------
| LOGIN INTERNAL
|--------------------------------------------------------------------------
*/

Route::get('/login/internal', function () {
    return view('auth.login-internal');
})->name('login.internal');

Route::post(
    '/login/internal',
    [InternalLoginController::class, 'store']
)->name('login.internal.submit');

Route::post(
    '/logout/internal',
    [InternalLoginController::class, 'destroy']
)->name('login.internal.logout');

/*
|--------------------------------------------------------------------------
| PELANGGAN
|--------------------------------------------------------------------------
*/

Route::get('/pelanggan/dashboard', function () {
    return view('pelanggan.dashboard');
})->name('pelanggan.dashboard');

/*
|--------------------------------------------------------------------------
| AREA ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(InternalAdminMiddleware::class)
    ->prefix('admin')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('admin.dashboard');

        /*
        |--------------------------------------------------------------------------
        | DATA TENANT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/tenant',
            [TenantController::class, 'index']
        )->name('admin.tenant');

        Route::post(
            '/tenant',
            [TenantController::class, 'store']
        )->name('admin.tenant.store');

        Route::put(
            '/tenant/{tenant}',
            [TenantController::class, 'update']
        )->name('admin.tenant.update');

        Route::patch(
            '/tenant/{tenant}/status',
            [TenantController::class, 'toggleStatus']
        )->name('admin.tenant.status');

        /*
        |--------------------------------------------------------------------------
        | DATA PELANGGAN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pelanggan',
            [PelangganController::class, 'index']
        )->name('admin.pelanggan');

        Route::patch(
            '/pelanggan/{pelanggan}/status',
            [PelangganController::class, 'toggleStatus']
        )->name('admin.pelanggan.status');

        Route::delete(
            '/pelanggan/{pelanggan}',
            [PelangganController::class, 'destroy']
        )->name('admin.pelanggan.destroy');

        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI OFFLINE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/transaksi-offline',
            [TransaksiOfflineController::class, 'index']
        )->name('admin.transaksi.offline');

        Route::patch(
            '/transaksi-offline/{transaksi}/bayar',
            [TransaksiOfflineController::class, 'bayar']
        )->name('admin.transaksi.offline.bayar');

        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI ONLINE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/transaksi-online',
            [TransaksiOnlineController::class, 'index']
        )->name('admin.transaksi.online');

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT TRANSAKSI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/riwayat-transaksi',
            [RiwayatTransaksiController::class, 'index']
        )->name('admin.riwayat');

        Route::get(
            '/riwayat-transaksi/online',
            [RiwayatTransaksiController::class, 'online']
        )->name('admin.riwayat.online');

        Route::get(
            '/riwayat-transaksi/offline',
            [RiwayatTransaksiController::class, 'offline']
        )->name('admin.riwayat.offline');

        /*
        |--------------------------------------------------------------------------
        | LAPORAN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/laporan',
            [LaporanController::class, 'index']
        )->name('admin.laporan');

        /*
        |--------------------------------------------------------------------------
        | PENGATURAN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pengaturan',
            [PengaturanController::class, 'index']
        )->name('admin.pengaturan');

        Route::put(
            '/pengaturan',
            [PengaturanController::class, 'update']
        )->name('admin.pengaturan.update');

        Route::put(
            '/pengaturan/password',
            [PengaturanController::class, 'updatePassword']
        )->name('admin.pengaturan.password');
    });

    /*
|--------------------------------------------------------------------------
| AREA TENANT
|--------------------------------------------------------------------------
*/

Route::middleware(InternalTenantMiddleware::class)
    ->prefix('tenant')
    ->group(function () {

        Route::get(
            '/dashboard',
            [DashboardTenantController::class, 'index']
        )->name('tenant.dashboard');

        Route::get(
            '/transaksi-offline',
            [
                TransaksiOfflineTenantController::class,
                'index'
            ]
        )->name('tenant.transaksi.offline');

        Route::post(
            '/transaksi-offline',
            [
                TransaksiOfflineTenantController::class,
                'store'
            ]
        )->name('tenant.transaksi.offline.store');
    });