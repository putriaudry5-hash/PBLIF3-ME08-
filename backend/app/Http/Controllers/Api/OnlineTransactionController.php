<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use stdClass;

class OnlineTransactionController extends Controller
{
    public function index()
    {
        $orders = DB::table('orders')
            ->join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->leftJoin('payments', function ($join) {
                $join->on('payments.order_id', '=', 'orders.id')
                    ->where('payments.metode', '=', 'Online');
            })
            ->where('orders.jenis', 'Online')
            ->select(
                'orders.id',
                'orders.kode',
                'orders.tenant_id',
                'orders.total',
                'orders.status',
                'orders.created_at',
                'tenants.nama as nama_tenant',
                'payments.metode',
                'payments.status as status_pembayaran',
                'payments.paid_at'
            )
            ->orderByDesc('orders.id')
            ->get();

        $data = $orders->map(function (stdClass $order) {
            return [
                'id' => $order->id,
                'kode' => $order->kode,
                'tenant_id' => $order->tenant_id,
                'tenant' => $order->nama_tenant,
                'pelanggan' => '-',
                'total' => (float) $order->total,
                'metode' => $order->metode ?? 'Online',
                'pembayaran' => $order->status_pembayaran ?? 'Menunggu',
                'status' => $order->status,
                'waktu' => date('H:i', strtotime($order->created_at)),
                'created_at' => date('Y-m-d', strtotime($order->created_at))
            ];
        });

        return response()->json([
            'data' => $data
        ]);
    }
}
