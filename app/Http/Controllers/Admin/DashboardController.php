<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTenantAktif = Tenant::whereRaw(
            'LOWER(status) = ?',
            ['aktif']
        )->count();

        $queryHariIni = Transaksi::query()
            ->where(function ($query) {
                $query->where(function ($offline) {
                    $offline->where('jenis', 'offline')
                        ->whereDate('tanggal_transaksi', today());
                })->orWhere(function ($online) {
                    $online->where('jenis', 'online')
                        ->whereDate('created_at', today());
                });
            });

        $transaksiHariIni = (clone $queryHariIni)->count();

        $pendapatanHariIni = (clone $queryHariIni)
            ->where('status', 'lunas')
            ->sum('total');

        $queryMenunggu = Transaksi::with('tenant')
            ->where('jenis', 'offline')
            ->where('sumber', 'tenant')
            ->where('status', 'menunggu_pembayaran')
            ->whereDate('tanggal_transaksi', today());

        $menungguPembayaran = (clone $queryMenunggu)->count();

        $offlineMenunggu = (clone $queryMenunggu)
            ->latest()
            ->limit(3)
            ->get();

        $transaksiTerbaru = Transaksi::with('tenant')
            ->latest()
            ->limit(4)
            ->get();

        return view('admin.dashboard', compact(
            'totalTenantAktif',
            'transaksiHariIni',
            'menungguPembayaran',
            'pendapatanHariIni',
            'transaksiTerbaru',
            'offlineMenunggu'
        ));
    }
}