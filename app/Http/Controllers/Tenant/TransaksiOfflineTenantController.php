<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiOfflineTenantController extends Controller
{
    public function index()
    {
        $user = Auth::guard('internal')->user();

        $tenant = Tenant::findOrFail(
            $user->tenant_id
        );

        $queryHariIni = Transaksi::with('details')
            ->where('tenant_id', $tenant->id)
            ->where('jenis', 'offline')
            ->where('sumber', 'tenant')
            ->whereDate(
                'tanggal_transaksi',
                today()
            );

        $transaksiHariIni =
            (clone $queryHariIni)
                ->latest()
                ->get();

        $jumlahHariIni =
            $transaksiHariIni->count();

        $jumlahMenunggu =
            $transaksiHariIni
                ->where(
                    'status',
                    'menunggu_pembayaran'
                )
                ->count();

        $jumlahLunas =
            $transaksiHariIni
                ->where(
                    'status',
                    'lunas'
                )
                ->count();

        return view(
            'tenant.transaksi-offline',
            compact(
                'tenant',
                'transaksiHariIni',
                'jumlahHariIni',
                'jumlahMenunggu',
                'jumlahLunas'
            )
        );
    }

    public function store(Request $request)
    {
        $user = Auth::guard('internal')->user();

        $tenant = Tenant::findOrFail(
            $user->tenant_id
        );

        $request->validate([
            'nama_item' => [
                'required',
                'array',
                'min:1'
            ],
            'nama_item.*' => [
                'required',
                'string',
                'max:150'
            ],
            'harga' => [
                'required',
                'array',
                'min:1'
            ],
            'harga.*' => [
                'required',
                'integer',
                'min:1'
            ],
            'jumlah' => [
                'required',
                'array',
                'min:1'
            ],
            'jumlah.*' => [
                'required',
                'integer',
                'min:1',
                'max:100'
            ],
            'catatan' => [
                'nullable',
                'string',
                'max:500'
            ],
        ]);

        $namaItems = $request->input(
            'nama_item',
            []
        );

        $hargaItems = $request->input(
            'harga',
            []
        );

        $jumlahItems = $request->input(
            'jumlah',
            []
        );

        if (
            count($namaItems) !== count($hargaItems) ||
            count($namaItems) !== count($jumlahItems)
        ) {
            return back()
                ->withErrors([
                    'item' =>
                        'Data item transaksi tidak lengkap.'
                ])
                ->withInput();
        }

        $items = [];
        $total = 0;

        foreach ($namaItems as $index => $namaItem) {
            $harga = (int) $hargaItems[$index];
            $jumlah = (int) $jumlahItems[$index];
            $subtotal = $harga * $jumlah;

            $total += $subtotal;

            $items[] = [
                'nama_item' => $namaItem,
                'harga' => $harga,
                'jumlah' => $jumlah,
                'subtotal' => $subtotal,
            ];
        }

        $transaksi = DB::transaction(
            function () use (
                $tenant,
                $request,
                $items,
                $total
            ) {
                $transaksi = Transaksi::create([
                    'tenant_id' => $tenant->id,
                    'pelanggan_id' => null,
                    'tanggal_transaksi' =>
                        today()->toDateString(),
                    'jenis' => 'offline',
                    'sumber' => 'tenant',
                    'metode_pembayaran' => 'cash',
                    'total' => $total,
                    'jumlah_bayar' => null,
                    'kembalian' => 0,
                    'status' => 'menunggu_pembayaran',
                    'status_pesanan' => null,
                    'catatan' =>
                        $request->input('catatan'),
                ]);

                foreach ($items as $item) {
                    $transaksi->details()->create(
                        $item
                    );
                }

                return $transaksi;
            }
        );

        return redirect()
            ->route('tenant.transaksi.offline')
            ->with(
                'success',
                'Transaksi berhasil dibuat.'
            )
            ->with(
                'kode_transaksi',
                $transaksi->kode_transaksi
            );
    }
}