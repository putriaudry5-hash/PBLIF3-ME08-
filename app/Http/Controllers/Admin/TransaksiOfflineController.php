<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiOfflineController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN TRANSAKSI OFFLINE
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        // Semua tenant untuk pilihan dan filter transaksi
        $semuaTenants = Tenant::orderBy('nama_tenant')
            ->get();

        // Daftar transaksi offline hari ini
        $queryTransaksi = Transaksi::with('tenant')
            ->where('jenis', 'offline')
            ->where('sumber', 'tenant')
            ->whereDate('tanggal_transaksi', today());

        // Filter daftar transaksi berdasarkan tenant
        if ($request->filled('tenant_id')) {
            $queryTransaksi->where(
                'tenant_id',
                $request->tenant_id
            );
        }

        $daftarTransaksi = $queryTransaksi
            ->latest()
            ->get();

        // Jumlah transaksi offline hari ini
        $jumlahHariIni = Transaksi::where('jenis', 'offline')
            ->where('sumber', 'tenant')
            ->whereDate('tanggal_transaksi', today())
            ->count();

        // Pendapatan hanya dari transaksi yang sudah lunas
        $pendapatanHariIni = Transaksi::where('jenis', 'offline')
            ->where('sumber', 'tenant')
            ->where('status', 'lunas')
            ->whereDate('tanggal_transaksi', today())
            ->sum('total');

        // Data pencarian transaksi
        $tenantDicari = $request->query('tenant_id');

        $kodeDicari = trim(
            (string) $request->query('kode_transaksi')
        );

        $transaksiDicari = null;

        /*
        |--------------------------------------------------------------------------
        | CARI TRANSAKSI
        |--------------------------------------------------------------------------
        |
        | Kasir memilih tenant terlebih dahulu,
        | kemudian memasukkan kode seperti 4.1.
        |
        */

        if ($tenantDicari && $kodeDicari !== '') {
            $transaksiDicari = Transaksi::with([
                'tenant',
                'details'
            ])
                ->where('jenis', 'offline')
                ->where('sumber', 'tenant')
                ->where('tenant_id', $tenantDicari)
                ->where('kode_transaksi', $kodeDicari)
                ->whereDate('tanggal_transaksi', today())
                ->first();
        }

        return view('admin.transaksi-offline', compact(
            'semuaTenants',
            'daftarTransaksi',
            'jumlahHariIni',
            'pendapatanHariIni',
            'kodeDicari',
            'transaksiDicari'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    public function bayar(Request $request, Transaksi $transaksi)
    {
        // Pastikan transaksi merupakan transaksi offline dari tenant
        if (
            $transaksi->jenis !== 'offline' ||
            $transaksi->sumber !== 'tenant'
        ) {
            abort(404);
        }

        // Pastikan transaksi memiliki tanggal
        if (!$transaksi->tanggal_transaksi) {
            abort(404);
        }

        // Pastikan transaksi berasal dari hari ini
        $tanggalTransaksi = Carbon::parse(
            $transaksi->tanggal_transaksi
        )->toDateString();

        if ($tanggalTransaksi !== today()->toDateString()) {
            abort(404);
        }

        // Transaksi yang sudah lunas tidak boleh dibayar lagi
        if ($transaksi->status === 'lunas') {
            return redirect()
                ->route('admin.transaksi.offline', [
                    'tenant_id' => $transaksi->tenant_id,
                    'kode_transaksi' => $transaksi->kode_transaksi
                ])
                ->with(
                    'error',
                    'Transaksi ini sudah lunas.'
                );
        }

        // Hanya transaksi menunggu pembayaran yang dapat dibayar
        if ($transaksi->status !== 'menunggu_pembayaran') {
            return redirect()
                ->route('admin.transaksi.offline', [
                    'tenant_id' => $transaksi->tenant_id,
                    'kode_transaksi' => $transaksi->kode_transaksi
                ])
                ->with(
                    'error',
                    'Transaksi ini tidak dapat dibayar.'
                );
        }

        // Validasi uang yang diterima
        $request->validate([
            'jumlah_bayar' => [
                'required',
                'integer',
                'min:1'
            ]
        ]);

        // Pastikan uang pelanggan cukup
        if ($request->jumlah_bayar < $transaksi->total) {
            return back()
                ->withErrors([
                    'jumlah_bayar' =>
                        'Uang yang diterima masih kurang.'
                ])
                ->withInput();
        }

        // Hitung kembalian
        $kembalian =
            $request->jumlah_bayar -
            $transaksi->total;

        // Ubah transaksi menjadi lunas
        $transaksi->update([
            'jumlah_bayar' => $request->jumlah_bayar,
            'kembalian' => $kembalian,
            'status' => 'lunas'
        ]);

        return redirect()
            ->route('admin.transaksi.offline', [
                'tenant_id' => $transaksi->tenant_id,
                'kode_transaksi' => $transaksi->kode_transaksi
            ])
            ->with(
                'success',
                'Pembayaran kode ' .
                $transaksi->kode_transaksi .
                ' berhasil. Status transaksi sudah lunas.'
            );
    }
}