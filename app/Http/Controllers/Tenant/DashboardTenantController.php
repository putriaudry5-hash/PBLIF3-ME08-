<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class DashboardTenantController extends Controller
{
    public function index()
    {
        $user = Auth::guard('internal')->user();

        $tenant = Tenant::findOrFail(
            $user->tenant_id
        );

        $queryHariIni = Transaksi::where(
            'tenant_id',
            $tenant->id
        )
            ->where('jenis', 'offline')
            ->where('sumber', 'tenant')
            ->whereDate(
                'tanggal_transaksi',
                today()
            );

        $transaksiHariIni =
            (clone $queryHariIni)->count();

        $menungguPembayaran =
            (clone $queryHariIni)
                ->where(
                    'status',
                    'menunggu_pembayaran'
                )
                ->count();

        $transaksiLunas =
            (clone $queryHariIni)
                ->where('status', 'lunas')
                ->count();

        $pendapatanHariIni =
            (clone $queryHariIni)
                ->where('status', 'lunas')
                ->sum('total');

        $transaksiTerbaru = Transaksi::with('details')
            ->where('tenant_id', $tenant->id)
            ->where('jenis', 'offline')
            ->where('sumber', 'tenant')
            ->latest()
            ->limit(5)
            ->get();

        return view('tenant.dashboard', compact(
            'tenant',
            'transaksiHariIni',
            'menungguPembayaran',
            'transaksiLunas',
            'pendapatanHariIni',
            'transaksiTerbaru'
        ));
    }
}