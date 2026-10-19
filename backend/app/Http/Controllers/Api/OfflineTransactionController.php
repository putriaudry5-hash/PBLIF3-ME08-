<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use stdClass;

class OfflineTransactionController extends Controller
{
    public function index()
    {
        $orders = DB::table('orders')
            ->join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->where('orders.jenis', 'Offline')
            ->select('orders.*', 'tenants.nama as nama_tenant')
            ->orderByDesc('orders.id')
            ->get();

        $data = $orders->map(function (stdClass $order) {
            return [
                'id' => $order->id,
                'kode' => $order->kode,
                'tenant_id' => $order->tenant_id,
                'tenant' => $order->nama_tenant,
                'total' => (float) $order->total,
                'jenis' => $order->jenis,
                'status' => $order->status,
                'waktu' => date('H:i', strtotime($order->created_at)),
                'created_at' => date('Y-m-d', strtotime($order->created_at))
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.nama_item' => ['required', 'string', 'max:255'],
            'items.*.jumlah' => ['required', 'integer', 'min:1', 'max:1000'],
            'items.*.harga' => ['required', 'integer', 'min:1', 'max:100000000']
        ]);

        $tenant = Tenant::findOrFail($validated['tenant_id']);

        if (strtolower($tenant->status) !== 'aktif') {
            return response()->json([
                'message' => 'Tenant sedang tidak aktif.'
            ], 422);
        }

        $order = DB::transaction(function () use ($validated) {
            $total = 0;

            foreach ($validated['items'] as $item) {
                $total += $item['jumlah'] * $item['harga'];
            }

            if ($total > 9999999999) {
                throw ValidationException::withMessages([
                    'items' => ['Total transaksi terlalu besar.']
                ]);
            }

            $orderId = DB::table('orders')->insertGetId([
                'tenant_id' => $validated['tenant_id'],
                'kode' => 'TEMP-' . bin2hex(random_bytes(12)),
                'jenis' => 'Offline',
                'total' => $total,
                'status' => 'Menunggu Pembayaran',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $kode = $validated['tenant_id'] . '.' . $orderId;

            DB::table('orders')
                ->where('id', $orderId)
                ->update([
                    'kode' => $kode,
                    'updated_at' => now()
                ]);

            foreach ($validated['items'] as $item) {
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'nama_item' => $item['nama_item'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['jumlah'] * $item['harga'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return DB::table('orders')
                ->where('id', $orderId)
                ->first();
        });

        return response()->json([
            'message' => 'Transaksi berhasil dicatat.',
            'data' => [
                'id' => $order->id,
                'kode' => $order->kode,
                'tenant_id' => $order->tenant_id,
                'total' => (float) $order->total,
                'status' => $order->status
            ]
        ], 201);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'integer', 'exists:tenants,id'],
            'kode' => ['required', 'string', 'max:100']
        ]);

        $order = DB::table('orders')
            ->join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->where('orders.tenant_id', $validated['tenant_id'])
            ->where('orders.kode', trim($validated['kode']))
            ->where('orders.jenis', 'Offline')
            ->select('orders.*', 'tenants.nama as nama_tenant')
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Transaksi tidak ditemukan.'
            ], 404);
        }

        $items = DB::table('order_items')
            ->where('order_id', $order->id)
            ->select('id', 'nama_item', 'jumlah', 'harga', 'subtotal')
            ->orderBy('id')
            ->get()
            ->map(function (stdClass $item) {
                return [
                    'id' => $item->id,
                    'nama_item' => $item->nama_item,
                    'jumlah' => (int) $item->jumlah,
                    'harga' => (float) $item->harga,
                    'subtotal' => (float) $item->subtotal
                ];
            });

        return response()->json([
            'data' => [
                'id' => $order->id,
                'kode' => $order->kode,
                'tenant_id' => $order->tenant_id,
                'tenant' => $order->nama_tenant,
                'total' => (float) $order->total,
                'status' => $order->status,
                'items' => $items
            ]
        ]);
    }

    public function confirm(Request $request, int $id)
    {
        $validated = $request->validate([
            'uang_diterima' => ['required', 'integer', 'min:1']
        ]);

        $result = DB::transaction(function () use ($id, $validated) {
            $order = DB::table('orders')
                ->where('id', $id)
                ->lockForUpdate()
                ->first();

            if (!$order || $order->jenis !== 'Offline') {
                return [
                    'status_code' => 404,
                    'message' => 'Transaksi offline tidak ditemukan.'
                ];
            }

            if ($order->status !== 'Menunggu Pembayaran') {
                return [
                    'status_code' => 422,
                    'message' => 'Transaksi tidak dapat dibayar lagi.'
                ];
            }

            $total = (float) $order->total;
            $uangDiterima = (int) $validated['uang_diterima'];

            if ($uangDiterima < $total) {
                return [
                    'status_code' => 422,
                    'message' => 'Uang diterima kurang dari total tagihan.'
                ];
            }

            $kembalian = $uangDiterima - $total;

            DB::table('payments')->insert([
                'order_id' => $order->id,
                'metode' => 'Cash',
                'jumlah_bayar' => $total,
                'uang_diterima' => $uangDiterima,
                'kembalian' => $kembalian,
                'status' => 'Lunas',
                'paid_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('orders')
                ->where('id', $order->id)
                ->update([
                    'status' => 'Lunas',
                    'updated_at' => now()
                ]);

            return [
                'status_code' => 200,
                'message' => 'Pembayaran tunai berhasil dikonfirmasi.',
                'data' => [
                    'id' => $order->id,
                    'kode' => $order->kode,
                    'tenant_id' => $order->tenant_id,
                    'total' => $total,
                    'status' => 'Lunas',
                    'uang_diterima' => $uangDiterima,
                    'kembalian' => $kembalian
                ]
            ];
        });

        $statusCode = $result['status_code'];
        unset($result['status_code']);

        return response()->json($result, $statusCode);
    }
}
