<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'nama',
        'pj',
        'email',
        'telepon',
        'jenis',
        'lokasi',
        'status',
    ];

    protected $attributes = [
        'status' => 'Aktif',
    ];
}
