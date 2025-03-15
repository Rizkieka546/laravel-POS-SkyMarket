<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembelian;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class PembelianSeeder extends Seeder
{
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        Pembelian::truncate();
        Schema::enableForeignKeyConstraints();

        $file = File::get(database_path('data/pembelian.json'));
        $data = json_decode($file);

        foreach ($data->pembelian as $item) {
            Pembelian::create([
                'kode_masuk' => $item->kode_masuk,
                'tanggal_masuk' => $item->tanggal_masuk,
                'total' => $item->total,
                'pemasok_id' => $item->pemasok_id,
                'user_id' => $item->user_id,
            ]);
        }
    }
}