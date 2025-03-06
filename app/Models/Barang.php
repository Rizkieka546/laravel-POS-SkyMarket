<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';
    protected $fillable = ['kode_barang', 'produk_id', 'kategori_id', 'nama_barang', 'satuan', 'harga_jual', 'stok', 'stok_minimal', 'ditarik', 'user_id'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
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