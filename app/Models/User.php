<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class);
    }

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class);
    }
}