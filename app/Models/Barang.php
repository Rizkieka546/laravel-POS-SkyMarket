<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'kategori_id',
        'nama_barang',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimal',
        'gambar',
        'satuan',
        'ditarik',
        'user_id'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class);
    }
}