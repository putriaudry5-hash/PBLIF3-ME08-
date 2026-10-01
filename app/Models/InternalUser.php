<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class InternalUser extends Authenticatable
{
    protected $fillable = [
        'tenant_id',
        'nama',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}