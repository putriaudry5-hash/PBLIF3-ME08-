<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'nama_kantin',
        'jam_buka',
        'jam_tutup',
    ];
}