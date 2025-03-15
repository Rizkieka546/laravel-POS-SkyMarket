<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Pelanggan::truncate();
        Schema::enableForeignKeyConstraints();

        $file = File::get(database_path('data/pelanggan.json'));
        $data = json_decode($file);

        foreach ($data->pelanggan as $item) {
            Pelanggan::create([
                'kode_pelanggan' => $item->kode_pelanggan,
                'nama' => $item->nama,
                'alamat' => $item->alamat,
                'no_telp' => $item->no_telp,
                'email' => $item->email,
                'membership' => $item->membership,
            ]);
        }
    }
}