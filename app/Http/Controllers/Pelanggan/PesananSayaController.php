<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;

class PesananSayaController extends Controller
{
    public function index()
    {
        // Ambil ID pelanggan dari session jika tersedia
        $pelangganId = session('pelanggan_id');

        // Jika ID tersedia, ambil pesanan milik pelanggan
        if ($pelangganId) {
            $pesanans = Pesanan::with(['tenant', 'details'])
                ->where('pelanggan_id', $pelangganId)
                ->latest()
                ->get();
        } else {
            // Tampilkan halaman kosong jika belum ada ID pelanggan
            $pesanans = collect();
        }

        return view('pelanggan.pesanan-saya', compact('pesanans'));
    }
}