<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class TransactionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'jenis' => ['required', 'in:online,offline']
        ]);

        $jenis = ucfirst($validated['jenis']);

        $orders = DB::table('orders')
            ->join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->where('orders.jenis', $jenis)
            ->select(
                'orders.id',
                'orders.kode',
                'orders.tenant_id',
                'orders.total',
                'orders.status',
                'orders.created_at',
                'tenants.nama as nama_tenant'
            )
            ->orderByDesc('orders.created_at')
            ->get();

        $data = $orders->map(function (stdClass $order) {
            $payment = DB::table('payments')
                ->where('order_id', $order->id)
                ->orderByDesc('id')
                ->first();

            return [
                'id' => $order->id,
                'kode' => $order->kode,
                'tenant_id' => $order->tenant_id,
                'tenant' => $order->nama_tenant,
                'pelanggan' => '-',
                'tanggal' => date('d/m/Y H:i', strtotime($order->created_at)),
                'total' => (float) $order->total,
                'pembayaran' => $payment?->status ?? 'Menunggu',
                'status' => $order->status
            ];
        });

        return response()->json(['data' => $data]);
    }
}
