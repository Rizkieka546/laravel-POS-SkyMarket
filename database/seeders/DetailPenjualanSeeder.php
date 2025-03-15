<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DetailPenjualanSeeder extends Seeder
{
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        DetailPenjualan::truncate();
        Schema::enableForeignKeyConstraints();

        $file = File::get(database_path('data/detail_penjualan.json'));
        $data = json_decode($file);

        foreach ($data->detail_penjualan as $item) {
            DetailPenjualan::create([
                'penjualan_id' => $item->penjualan_id,
                'barang_id' => $item->barang_id,
                'harga_jual' => $item->harga_jual,
                'jumlah' => $item->jumlah,
                'sub_total' => $item->sub_total,
            ]);
        }
    }
}