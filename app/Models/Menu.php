<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'tenant_id',
        'nama_menu',
        'jenis_menu',
        'tipe_harga',
        'harga',
        'opsi_harga',
        'stok',
        'foto',
        'status',
        'aktif_online',
    ];

    protected $casts = [
        'harga' => 'integer',
        'opsi_harga' => 'array',
        'stok' => 'integer',
        'aktif_online' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}