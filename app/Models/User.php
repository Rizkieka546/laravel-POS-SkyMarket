<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'membership'];

    protected $hidden = ['password'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi ke tabel Barang (barang yang dimasukkan oleh user)
    public function barang()
    {
        return $this->hasMany(Barang::class);
    }

    // Relasi ke tabel Penjualan (transaksi yang dilakukan oleh user)
    public function penjualan()
    {
        return $this->hasMany(Penjualan::class);
    }

    // Relasi ke tabel Pembelian (pembelian yang dilakukan oleh user)
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class);
    }
}