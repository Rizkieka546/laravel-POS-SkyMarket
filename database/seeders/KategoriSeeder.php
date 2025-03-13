<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $kategori = [
            ['nama_kategori' => 'Sembako'],
            ['nama_kategori' => 'Minuman & Produk Susu'],
            ['nama_kategori' => 'Makanan Ringan & Biskuit'],
            ['nama_kategori' => 'Bumbu Dapur & Kebutuhan Masak'],
            ['nama_kategori' => 'Produk Pembersih & Peralatan Rumah Tangga'],
            ['nama_kategori' => 'Produk Perawatan Tubuh & Kosmetik'],
            ['nama_kategori' => 'Produk Bayi & Anak-anak'],
            ['nama_kategori' => 'Rokok & Minuman Berenergi'],
            ['nama_kategori' => 'Elektronik & Aksesoris HP'],
            ['nama_kategori' => 'Alat Tulis & Keperluan Kantor'],
        ];

        Kategori::insert($kategori);
    }
}