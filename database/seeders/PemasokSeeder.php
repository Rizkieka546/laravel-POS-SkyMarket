<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemasokSeeder extends Seeder
{
    public function run()
    {
        DB::table('pemasok')->insert([
            [
                'nama_pemasok' => 'PT Sumber Jaya',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'telepon' => '081234567890',
                'email' => 'sumberjaya@email.com',
                'catatan' => 'Pemasok bahan baku utama',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_pemasok' => 'CV Berkah Sejahtera',
                'alamat' => 'Jl. Raya Sukabumi No. 45, Bandung',
                'telepon' => '085678901234',
                'email' => 'berkahsejahtera@email.com',
                'catatan' => 'Menyediakan peralatan tambahan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_pemasok' => 'UD Makmur Sentosa',
                'alamat' => 'Jl. Ahmad Yani No. 78, Surabaya',
                'telepon' => '082345678901',
                'email' => 'makmursentosa@email.com',
                'catatan' => 'Pemasok suku cadang',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}