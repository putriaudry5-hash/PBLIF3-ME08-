<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::orderBy('id')->get();

        return view('admin.data-pelanggan', compact('pelanggans'));
    }

    public function toggleStatus(Pelanggan $pelanggan)
    {
        $pelanggan->status = $pelanggan->status === 'aktif'
            ? 'nonaktif'
            : 'aktif';

        $pelanggan->save();

        return redirect()
            ->route('admin.pelanggan')
            ->with('success', 'Status pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()
            ->route('admin.pelanggan')
            ->with('success', 'Data pelanggan berhasil dihapus.');
    }
}