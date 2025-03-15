<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class BarangSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Barang::truncate();
        Schema::enableForeignKeyConstraints();

        $file = File::get(database_path('data/barang.json'));
        $data = json_decode($file);


        foreach ($data->barang as $item) {
            Barang::create([
                'kode_barang' => $item->kode_barang,
                'kategori_id' => $item->kategori_id,
                'nama_barang' => $item->nama_barang,
                'satuan' => $item->satuan,
                'harga_beli' => $item->harga_beli,
                'harga_jual' => $item->harga_jual,
                'stok' => $item->stok,
                'stok_minimal' => $item->stok_minimal,
                'gambar' => $item->gambar,
                'user_id' => $item->user_id,
            ]);
        }
    }
}