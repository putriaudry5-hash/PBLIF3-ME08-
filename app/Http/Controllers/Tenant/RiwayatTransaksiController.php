<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Tenant;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatTransaksiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('internal')->user();
        $tenant = Tenant::findOrFail($user->tenant_id);

        $filter = $request->query('jenis', 'semua');

        $offline = collect();
        $online = collect();

        if ($filter === 'semua' || $filter === 'offline') {
            $offline = Transaksi::with('details')
                ->where('tenant_id', $tenant->id)
                ->where('jenis', 'offline')
                ->where('sumber', 'tenant')
                ->where('status', 'lunas')
                ->latest()
                ->get()
                ->map(function ($transaksi) {
                    return [
                        'id' => $transaksi->id,
                        'kode' => $transaksi->kode_transaksi,
                        'jenis' => 'offline',
                        'tanggal' => $transaksi->tanggal_transaksi ?? $transaksi->created_at,
                        'tanggal_sort' => $transaksi->created_at,
                        'jumlah_item' => $transaksi->details->sum('jumlah'),
                        'total' => $transaksi->total,
                        'status' => 'Lunas',
                    ];
                });
        }

        if ($filter === 'semua' || $filter === 'online') {
            $online = Pesanan::with('details')
                ->where('tenant_id', $tenant->id)
                ->where('status_pembayaran', 'lunas')
                ->where('status_pesanan', 'selesai')
                ->latest()
                ->get()
                ->map(function ($pesanan) {
                    return [
                        'id' => $pesanan->id,
                        'kode' => $pesanan->kode_pesanan,
                        'jenis' => 'online',
                        'tanggal' => $pesanan->created_at,
                        'tanggal_sort' => $pesanan->created_at,
                        'jumlah_item' => $pesanan->details->sum('jumlah'),
                        'total' => $pesanan->total,
                        'status' => 'Selesai',
                    ];
                });
        }

        $riwayat = $offline
            ->concat($online)
            ->sortByDesc('tanggal_sort')
            ->values();

        $totalTransaksi = $riwayat->count();
        $totalPendapatan = $riwayat->sum('total');

        return view('tenant.riwayat-transaksi', compact(
            'tenant',
            'riwayat',
            'filter',
            'totalTransaksi',
            'totalPendapatan'
        ));
    }
}