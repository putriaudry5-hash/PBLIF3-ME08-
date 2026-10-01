<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'kode_transaksi',
        'nomor_urut',
        'tanggal_transaksi',
        'tenant_id',
        'pelanggan_id',
        'jenis',
        'sumber',
        'metode_pembayaran',
        'total',
        'jumlah_bayar',
        'kembalian',
        'status',
        'status_pesanan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'total' => 'integer',
        'jumlah_bayar' => 'integer',
        'kembalian' => 'integer',
        'nomor_urut' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($transaksi) {
            if (
                $transaksi->jenis !== 'offline' ||
                $transaksi->sumber !== 'tenant'
            ) {
                return;
            }

            if (!$transaksi->tenant_id) {
                return;
            }

            if (!$transaksi->tanggal_transaksi) {
                $transaksi->tanggal_transaksi = today()->toDateString();
            }

            $nomorTerakhir = self::where('jenis', 'offline')
                ->where('sumber', 'tenant')
                ->where('tenant_id', $transaksi->tenant_id)
                ->whereDate(
                    'tanggal_transaksi',
                    $transaksi->tanggal_transaksi
                )
                ->max('nomor_urut');

            $nomorBaru = ((int) $nomorTerakhir) + 1;

            $transaksi->nomor_urut = $nomorBaru;

            $transaksi->kode_transaksi =
                $transaksi->tenant_id . '.' . $nomorBaru;
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function details()
    {
        return $this->hasMany(
            TransactionDetail::class,
            'transaction_id'
        );
    }
}