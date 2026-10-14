<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;

class RiwayatPelangganController extends Controller
{
    public function index()
    {
        $riwayat = Pesanan::with(['tenant', 'details'])
            ->latest()
            ->paginate(10);

        return view('pelanggan.riwayat-transaksi', compact('riwayat'));
    }
}
