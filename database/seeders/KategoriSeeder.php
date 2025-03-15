<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Kategori::truncate();
        Schema::enableForeignKeyConstraints();

        $file = File::get(database_path('data/kategori.json'));
        $data = json_decode($file);

        if (!empty($data->kategori)) {
            foreach ($data->kategori as $item) {
                Kategori::create([
                    'nama_kategori' => $item->nama_kategori,
                ]);
            }
        }
    }
}