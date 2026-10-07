<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\InternalAdminMiddleware;
use App\Http\Middleware\InternalTenantMiddleware;

use App\Http\Controllers\Auth\InternalLoginController;

/*
|--------------------------------------------------------------------------
| CONTROLLER ADMIN
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\TransaksiOfflineController;
use App\Http\Controllers\Admin\TransaksiOnlineController;
use App\Http\Controllers\Admin\RiwayatTransaksiController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaturanController;

/*
|--------------------------------------------------------------------------
| CONTROLLER TENANT
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Tenant\DashboardTenantController;
use App\Http\Controllers\Tenant\MenuController;
use App\Http\Controllers\Tenant\TransaksiOfflineTenantController;
use App\Http\Controllers\Tenant\PesananOnlineController;
use App\Http\Controllers\Tenant\RiwayatTransaksiController as TenantRiwayatTransaksiController;
use App\Http\Controllers\Tenant\LaporanTenantController;
use App\Http\Controllers\Tenant\PengaturanTenantController;

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

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [DashboardTenantController::class, 'index']
        )->name('tenant.dashboard');

        /*
        |--------------------------------------------------------------------------
        | KELOLA MENU
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/menu',
            [MenuController::class, 'index']
        )->name('tenant.menu.index');

        Route::post(
            '/menu',
            [MenuController::class, 'store']
        )->name('tenant.menu.store');

        Route::put(
            '/menu/{id}',
            [MenuController::class, 'update']
        )->name('tenant.menu.update');

        Route::patch(
            '/menu/{id}/status',
            [MenuController::class, 'toggleStatus']
        )->name('tenant.menu.status');

        Route::patch(
            '/menu/{id}/online',
            [MenuController::class, 'toggleOnline']
        )->name('tenant.menu.online');

        Route::delete(
            '/menu/{id}',
            [MenuController::class, 'destroy']
        )->name('tenant.menu.destroy');

        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI OFFLINE
        |--------------------------------------------------------------------------
        */

        Route::get('/transaksi-offline',
            [TransaksiOfflineTenantController::class, 'index']
        )->name('tenant.transaksi.offline');

        Route::post('/transaksi-offline',
            [TransaksiOfflineTenantController::class, 'store']
        )->name('tenant.transaksi.offline.store');

        /*
        |--------------------------------------------------------------------------
        | PESANAN ONLINE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pesanan-online',
            [PesananOnlineController::class, 'index']
        )->name('tenant.pesanan.online');

        Route::patch(
            '/pesanan-online/{id}/lanjut',
            [PesananOnlineController::class, 'lanjutStatus']
        )->name('tenant.pesanan.online.lanjut');

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT TRANSAKSI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/riwayat-transaksi',
            [TenantRiwayatTransaksiController::class, 'index']
        )->name('tenant.riwayat');

        Route::get(
            '/laporan',
            [LaporanTenantController::class, 'index']
        )->name('tenant.laporan');

        Route::get(
            '/pengaturan',
            [PengaturanTenantController::class, 'index']
        )->name('tenant.pengaturan');

        Route::put(
            '/pengaturan/profil',
            [PengaturanTenantController::class, 'updateProfil']
        )->name('tenant.pengaturan.profil');

        Route::put(
            '/pengaturan/akun',
            [PengaturanTenantController::class, 'updateAkun']
        )->name('tenant.pengaturan.akun');

        Route::patch(
            '/pengaturan/online',
            [PengaturanTenantController::class, 'updateOnline']
        )->name('tenant.pengaturan.online');

        Route::put(
            '/pengaturan/password',
            [PengaturanTenantController::class, 'updatePassword']
        )->name('tenant.pengaturan.password');
    });