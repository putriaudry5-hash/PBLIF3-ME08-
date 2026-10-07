<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    protected $fillable = [
        'pesanan_id',
        'menu_id',
        'nama_item',
        'jenis_menu',
        'harga',
        'jumlah',
        'subtotal',
    ];

    protected $casts = [
        'harga' => 'integer',
        'jumlah' => 'integer',
        'subtotal' => 'integer',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}