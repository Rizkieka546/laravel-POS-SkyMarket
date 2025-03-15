<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjualan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class PenjualanSeeder extends Seeder
{
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        Penjualan::truncate();
        Schema::enableForeignKeyConstraints();

        $file = File::get(database_path('data/penjualan.json'));
        $data = json_decode($file);

        foreach ($data->penjualan as $item) {
            Penjualan::create([
                'no_faktur' => $item->no_faktur,
                'tgl_faktur' => $item->tgl_faktur,
                'total_bayar' => $item->total_bayar,
                'pelanggan_id' => $item->pelanggan_id,
                'user_id' => $item->user_id,
                'duitku_reference' => $item->duitku_reference,
                'metode_pembayaran' => $item->metode_pembayaran,
                'status_pembayaran' => $item->status_pembayaran,
                'duitku_payment_url' => $item->duitku_payment_url,
            ]);
        }
    }
}