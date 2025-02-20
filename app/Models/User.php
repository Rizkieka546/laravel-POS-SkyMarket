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

    // public function produk()
    // {
    //     return $this->hasMany(produk::class);
    // }

    // public function pembelian()
    // {
    //     return $this->hasMany(Pembelian::class);
    // }

    // public function penjualan()
    // {
    //     return $this->hasMany(Penjualan::class);
    // }
}