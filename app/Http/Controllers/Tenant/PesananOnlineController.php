<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class PesananOnlineController extends Controller
{
    public function index()
    {
        $user = Auth::guard('internal')->user();
        $tenant = Tenant::findOrFail($user->tenant_id);

        $pesanans = Pesanan::with('pelanggan')
            ->where('tenant_id', $tenant->id)
            ->latest()
            ->get();

        $jumlahMenunggu = $pesanans->where('status_pesanan', 'menunggu')->count();
        $jumlahDiproses = $pesanans->where('status_pesanan', 'diproses')->count();
        $jumlahSiap = $pesanans->where('status_pesanan', 'siap_diambil')->count();
        $jumlahSelesai = $pesanans->where('status_pesanan', 'selesai')->count();

        return view('tenant.pesanan-online', compact(
            'tenant',
            'pesanans',
            'jumlahMenunggu',
            'jumlahDiproses',
            'jumlahSiap',
            'jumlahSelesai'
        ));
    }

    public function lanjutStatus($id)
    {
        $user = Auth::guard('internal')->user();

        $pesanan = Pesanan::where('tenant_id', $user->tenant_id)
            ->where('id', $id)
            ->firstOrFail();

        if ($pesanan->status_pembayaran !== 'lunas') {
            return back()->with(
                'error',
                'Pesanan belum dapat diproses karena pembayaran belum lunas.'
            );
        }

        $statusBerikutnya = [
            'menunggu' => 'diproses',
            'diproses' => 'siap_diambil',
            'siap_diambil' => 'selesai',
        ];

        if (!isset($statusBerikutnya[$pesanan->status_pesanan])) {
            return back()->with(
                'error',
                'Pesanan ini sudah selesai.'
            );
        }

        $pesanan->update([
            'status_pesanan' => $statusBerikutnya[$pesanan->status_pesanan],
        ]);

        return back()->with(
            'success',
            'Status pesanan berhasil diperbarui.'
        );
    }
}