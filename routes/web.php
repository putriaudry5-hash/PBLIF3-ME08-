
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
| CONTROLLER PELANGGAN
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Pelanggan\MenuTenantController;
use App\Http\Controllers\Pelanggan\KeranjangController;

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
| PROSES PEMBAYARAN DAN DETAIL PESANAN
|--------------------------------------------------------------------------
*/

Route::post('/pelanggan/konfirmasi-pembayaran', [
    KeranjangController::class,
    'konfirmasiPembayaran'
])->name('pelanggan.pembayaran.konfirmasi');

Route::get('/pelanggan/pembayaran-berhasil', [
    KeranjangController::class,
    'pembayaranBerhasil'
])->name('pelanggan.pembayaran.berhasil');

Route::get('/pelanggan/detail-pesanan/{nomor}', [
    KeranjangController::class,
    'detailPesanan'
])->name('pelanggan.detail-pesanan');

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
| AREA PELANGGAN
|--------------------------------------------------------------------------
*/

Route::get('/pelanggan/dashboard', function () {
    return view('pelanggan.dashboard');
})->name('pelanggan.dashboard');

// Halaman Pesanan Saya
Route::get('/pelanggan/pesanan-saya', [
    \App\Http\Controllers\Pelanggan\PesananSayaController::class,
    'index'
])->name('pelanggan.pesanan-saya');

Route::get('/pelanggan/riwayat-transaksi', [
    \App\Http\Controllers\Pelanggan\RiwayatPelangganController::class,
    'index'
])->name('pelanggan.riwayat-transaksi');


// Halaman Profil Pelanggan
Route::get('/pelanggan/profil', function () {
    return view('pelanggan.profil');
})->name('pelanggan.profil');

Route::get('/pelanggan/data-diri', function () {
    return view('pelanggan.data-diri');
})->name('pelanggan.data-diri');


/*
|--------------------------------------------------------------------------
| HALAMAN MENU TENANT UNTUK PELANGGAN
|--------------------------------------------------------------------------
*/

Route::get(
    '/pelanggan/tenant/{tenant}/menu',
    [MenuTenantController::class, 'index']
)->name('pelanggan.tenant.menu');


Route::post('/pelanggan/keranjang/tambah', [
    KeranjangController::class, 'tambah'
])->name('pelanggan.keranjang.tambah');

Route::get('/pelanggan/keranjang', [
    KeranjangController::class, 'index'
])->name('pelanggan.keranjang');

Route::post('/pelanggan/keranjang/{item}/jumlah', [
    KeranjangController::class, 'jumlah'
])->name('pelanggan.keranjang.jumlah');

Route::delete('/pelanggan/keranjang/{item}', [
    KeranjangController::class, 'hapus'
])->name('pelanggan.keranjang.hapus');

Route::post('/pelanggan/keranjang/kosongkan', [
    KeranjangController::class, 'kosongkan'
])->name('pelanggan.keranjang.kosongkan');

Route::get('/pelanggan/checkout', [
    KeranjangController::class, 'checkout'
])->name('pelanggan.checkout');

Route::post('/pelanggan/pembayaran', [
    KeranjangController::class, 'pembayaran'
])->name('pelanggan.pembayaran');

/*
|--------------------------------------------------------------------------
| AREA ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(InternalAdminMiddleware::class)
    ->prefix('admin')
    ->group(function () {

        // Dashboard
        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('admin.dashboard');

        // Data Tenant
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

        // Data Pelanggan
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

        // Transaksi Offline
        Route::get(
            '/transaksi-offline',
            [TransaksiOfflineController::class, 'index']
        )->name('admin.transaksi.offline');

        Route::patch(
            '/transaksi-offline/{transaksi}/bayar',
            [TransaksiOfflineController::class, 'bayar']
        )->name('admin.transaksi.offline.bayar');

        // Transaksi Online
        Route::get(
            '/transaksi-online',
            [TransaksiOnlineController::class, 'index']
        )->name('admin.transaksi.online');

        // Riwayat Transaksi
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

        // Laporan
        Route::get(
            '/laporan',
            [LaporanController::class, 'index']
        )->name('admin.laporan');

        // Pengaturan
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

        // Dashboard
        Route::get(
            '/dashboard',
            [DashboardTenantController::class, 'index']
        )->name('tenant.dashboard');

        // Kelola Menu
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

        // Transaksi Offline
        Route::get(
            '/transaksi-offline',
            [TransaksiOfflineTenantController::class, 'index']
        )->name('tenant.transaksi.offline');

        Route::post(
            '/transaksi-offline',
            [TransaksiOfflineTenantController::class, 'store']
        )->name('tenant.transaksi.offline.store');

        // Pesanan Online
        Route::get(
            '/pesanan-online',
            [PesananOnlineController::class, 'index']
        )->name('tenant.pesanan.online');

        Route::patch(
            '/pesanan-online/{id}/lanjut',
            [PesananOnlineController::class, 'lanjutStatus']
        )->name('tenant.pesanan.online.lanjut');

        // Riwayat Transaksi
        Route::get(
            '/riwayat-transaksi',
            [TenantRiwayatTransaksiController::class, 'index']
        )->name('tenant.riwayat');

        // Laporan
        Route::get(
            '/laporan',
            [LaporanTenantController::class, 'index']
        )->name('tenant.laporan');

        // Pengaturan
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