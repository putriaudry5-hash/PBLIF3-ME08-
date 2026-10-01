<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $fillable = [
        'google_id',
        'nama',
        'email',
        'foto',
        'status',
    ];
}