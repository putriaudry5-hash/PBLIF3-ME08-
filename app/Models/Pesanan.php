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
        'status_pesanan',
        'catatan',
        'dibayar_at',
    ];

    protected $casts = [
        'dibayar_at' => 'datetime',
        'total' => 'integer',
    ];

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
    return $this->hasMany(PesananDetail::class);
}

}