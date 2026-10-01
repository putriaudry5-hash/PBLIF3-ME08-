<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
        'kode_pesanan',
        'tenant_id',
        'pelanggan_id',
        'total',
        'status_pembayaran',
        'catatan',
        'dibayar_at',
    ];

    protected $casts = [
        'dibayar_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}