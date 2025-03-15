<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemasok;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class PemasokSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Pemasok::truncate();
        Schema::enableForeignKeyConstraints();

        $file = File::get(database_path('data/pemasok.json'));
        $data = json_decode($file);

        foreach ($data->pemasok as $item) {
            Pemasok::create([
                'nama_pemasok' => $item->nama_pemasok,
                'alamat' => $item->alamat,
                'telepon' => $item->telepon,
                'email' => $item->email,
                'catatan' => $item->catatan,
            ]);
        }
    }
}