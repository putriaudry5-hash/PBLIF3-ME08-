<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Tenant;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LaporanTenantController extends Controller
{
    public function index()
    {
        $user = Auth::guard('internal')->user();
        $tenant = Tenant::findOrFail($user->tenant_id);

        $offline = Transaksi::where('tenant_id', $tenant->id)
            ->where('jenis', 'offline')
            ->where('sumber', 'tenant')
            ->where('status', 'lunas')
            ->get();

        $online = Pesanan::where('tenant_id', $tenant->id)
            ->where('status_pembayaran', 'lunas')
            ->where('status_pesanan', 'selesai')
            ->get();

        $totalOffline = $offline->sum('total');
        $totalOnline = $online->sum('total');

        $totalPendapatan = $totalOffline + $totalOnline;
        $totalTransaksi = $offline->count() + $online->count();

        $jumlahOffline = $offline->count();
        $jumlahOnline = $online->count();

        /*
        |--------------------------------------------------------------------------
        | GRAFIK HARIAN - 7 HARI TERAKHIR
        |--------------------------------------------------------------------------
        */

        $harianLabels = [];
        $harianOffline = [];
        $harianOnline = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = today()->subDays($i);

            $harianLabels[] = $tanggal->format('d M');

            $harianOffline[] = Transaksi::where('tenant_id', $tenant->id)
                ->where('jenis', 'offline')
                ->where('sumber', 'tenant')
                ->where('status', 'lunas')
                ->whereDate('tanggal_transaksi', $tanggal)
                ->sum('total');

            $harianOnline[] = Pesanan::where('tenant_id', $tenant->id)
                ->where('status_pembayaran', 'lunas')
                ->where('status_pesanan', 'selesai')
                ->whereDate('created_at', $tanggal)
                ->sum('total');
        }

        /*
        |--------------------------------------------------------------------------
        | GRAFIK MINGGUAN - 4 MINGGU TERAKHIR
        |--------------------------------------------------------------------------
        */

        $mingguanLabels = [];
        $mingguanOffline = [];
        $mingguanOnline = [];

        for ($i = 3; $i >= 0; $i--) {
            $awal = now()->startOfWeek()->subWeeks($i);
            $akhir = $awal->copy()->endOfWeek();

            $mingguanLabels[] = 'Minggu ' . (4 - $i);

            $mingguanOffline[] = Transaksi::where('tenant_id', $tenant->id)
                ->where('jenis', 'offline')
                ->where('sumber', 'tenant')
                ->where('status', 'lunas')
                ->whereBetween('tanggal_transaksi', [
                    $awal->toDateString(),
                    $akhir->toDateString()
                ])
                ->sum('total');

            $mingguanOnline[] = Pesanan::where('tenant_id', $tenant->id)
                ->where('status_pembayaran', 'lunas')
                ->where('status_pesanan', 'selesai')
                ->whereBetween('created_at', [
                    $awal->startOfDay(),
                    $akhir->endOfDay()
                ])
                ->sum('total');
        }

        /*
        |--------------------------------------------------------------------------
        | GRAFIK BULANAN - 6 BULAN TERAKHIR
        |--------------------------------------------------------------------------
        */

        $bulananLabels = [];
        $bulananOffline = [];
        $bulananOnline = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->startOfMonth()->subMonths($i);

            $bulananLabels[] = $bulan->translatedFormat('M Y');

            $bulananOffline[] = Transaksi::where('tenant_id', $tenant->id)
                ->where('jenis', 'offline')
                ->where('sumber', 'tenant')
                ->where('status', 'lunas')
                ->whereYear('tanggal_transaksi', $bulan->year)
                ->whereMonth('tanggal_transaksi', $bulan->month)
                ->sum('total');

            $bulananOnline[] = Pesanan::where('tenant_id', $tenant->id)
                ->where('status_pembayaran', 'lunas')
                ->where('status_pesanan', 'selesai')
                ->whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->sum('total');
        }

        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI TERBARU
        |--------------------------------------------------------------------------
        */

        $riwayatOffline = Transaksi::where('tenant_id', $tenant->id)
            ->where('jenis', 'offline')
            ->where('sumber', 'tenant')
            ->where('status', 'lunas')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'kode' => $item->kode_transaksi,
                    'jenis' => 'Offline',
                    'tanggal' => $item->created_at,
                    'total' => $item->total,
                ];
            });

        $riwayatOnline = Pesanan::where('tenant_id', $tenant->id)
            ->where('status_pembayaran', 'lunas')
            ->where('status_pesanan', 'selesai')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'kode' => $item->kode_pesanan,
                    'jenis' => 'Online',
                    'tanggal' => $item->created_at,
                    'total' => $item->total,
                ];
            });

        $transaksiTerbaru = $riwayatOffline
            ->concat($riwayatOnline)
            ->sortByDesc('tanggal')
            ->take(5)
            ->values();

        return view('tenant.laporan', compact(
            'tenant',
            'totalPendapatan',
            'totalTransaksi',
            'totalOffline',
            'totalOnline',
            'jumlahOffline',
            'jumlahOnline',
            'harianLabels',
            'harianOffline',
            'harianOnline',
            'mingguanLabels',
            'mingguanOffline',
            'mingguanOnline',
            'bulananLabels',
            'bulananOffline',
            'bulananOnline',
            'transaksiTerbaru'
        ));
    }
}