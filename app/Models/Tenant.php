<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'nama_tenant',
        'nama_penanggung_jawab',
        'email',
        'no_hp',
        'jenis_tenant',
        'lokasi_kios',
        'password',
        'status',
    ];
}