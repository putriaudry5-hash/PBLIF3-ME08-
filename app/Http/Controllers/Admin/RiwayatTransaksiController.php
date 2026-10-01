<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;

class RiwayatTransaksiController extends Controller
{
    public function index()
    {
        return view('admin.riwayat-transaksi');
    }

    public function online()
    {
        $daftarTransaksi = Transaksi::with([
            'tenant',
            'pelanggan',
            'details'
        ])
            ->where('jenis', 'online')
            ->where('status', 'lunas')
            ->where('status_pesanan', 'selesai')
            ->latest()
            ->get();

        $jumlahTransaksi = $daftarTransaksi->count();
        $totalPendapatan = $daftarTransaksi->sum('total');

        return view('admin.riwayat-online', compact(
            'daftarTransaksi',
            'jumlahTransaksi',
            'totalPendapatan'
        ));
    }

    public function offline()
    {
        $daftarTransaksi = Transaksi::with([
            'tenant',
            'pelanggan',
            'details'
        ])
            ->where('jenis', 'offline')
            ->where('sumber', 'tenant')
            ->where('status', 'lunas')
            ->latest()
            ->get();

        $jumlahTransaksi = $daftarTransaksi->count();
        $totalPendapatan = $daftarTransaksi->sum('total');

        return view('admin.riwayat-offline', compact(
            'daftarTransaksi',
            'jumlahTransaksi',
            'totalPendapatan'
        ));
    }
}