<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailPembelian;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DetailPembelianSeeder extends Seeder
{
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        DetailPembelian::truncate();
        Schema::enableForeignKeyConstraints();

        $file = File::get(database_path('data/detail_pembelian.json'));
        $data = json_decode($file);

        foreach ($data->detail_pembelian as $item) {
            DetailPembelian::create([
                'pembelian_id' => $item->pembelian_id,
                'barang_id' => $item->barang_id,
                'harga_beli' => $item->harga_beli,
                'jumlah' => $item->jumlah,
                'sub_total' => $item->sub_total,
            ]);
        }
    }
}