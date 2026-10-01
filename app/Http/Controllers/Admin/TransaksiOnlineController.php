<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Transaksi;

class TransaksiOnlineController extends Controller
{
    public function index()
    {
        // Semua tenant
        $semuaTenants = Tenant::orderBy('nama_tenant')
            ->get();

        // Semua transaksi online hari ini dikelompokkan berdasarkan tenant
        $transaksiPerTenant = Transaksi::with([
            'tenant',
            'pelanggan',
            'details'
        ])
            ->where('jenis', 'online')
            ->whereDate('created_at', today())
            ->latest()
            ->get()
            ->groupBy('tenant_id');

        // Jumlah transaksi online hari ini
        $jumlahHariIni = Transaksi::where('jenis', 'online')
            ->whereDate('created_at', today())
            ->count();

        // Pendapatan online hari ini hanya dari transaksi lunas
        $pendapatanHariIni = Transaksi::where('jenis', 'online')
            ->where('status', 'lunas')
            ->whereDate('created_at', today())
            ->sum('total');

        return view('admin.transaksi-online', compact(
            'semuaTenants',
            'transaksiPerTenant',
            'jumlahHariIni',
            'pendapatanHariIni'
        ));
    }
}