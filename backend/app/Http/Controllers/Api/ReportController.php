<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use stdClass;

class ReportController extends Controller
{
    public function index()
    {
        $latestPayments = DB::table('payments')
            ->select('order_id')
            ->selectRaw('MAX(id) as payment_id')
            ->groupBy('order_id');

        $orders = DB::table('orders')
            ->join('tenants', 'orders.tenant_id', '=', 'tenants.id')
            ->leftJoinSub($latestPayments, 'latest_payments', function ($join) {
                $join->on('latest_payments.order_id', '=', 'orders.id');
            })
            ->leftJoin('payments', 'payments.id', '=', 'latest_payments.payment_id')
            ->select(
                'orders.id',
                'orders.kode',
                'orders.tenant_id',
                'orders.jenis',
                'orders.total',
                'orders.status',
                'orders.created_at',
                'tenants.nama as nama_tenant',
                'payments.status as status_pembayaran',
                'payments.paid_at'
            )
            ->orderByDesc('orders.id')
            ->get();

        $data = $orders->map(function (stdClass $order) {
            $lunas = $order->status === 'Lunas'
                && $order->status_pembayaran === 'Lunas';

            $tanggalAcuan = $lunas && $order->paid_at
                ? $order->paid_at
                : $order->created_at;

            return [
                'id' => $order->id,
                'kode' => $order->kode,
                'tenant_id' => $order->tenant_id,
                'tenant' => $order->nama_tenant,
                'jenis' => $order->jenis,
                'total' => (float) $order->total,
                'status' => $order->status,
                'pembayaran' => $lunas ? 'Lunas' : 'Menunggu',
                'tanggal' => date('Y-m-d', strtotime($tanggalAcuan)),
                'waktu' => date('H:i', strtotime($tanggalAcuan))
            ];
        });

        return response()->json(['data' => $data]);
    }
}
