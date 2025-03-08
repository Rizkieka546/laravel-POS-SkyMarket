<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    public function run()
    {

        Pelanggan::insert([
            [
                'kode_pelanggan' => 'PLG001',
                'nama' => 'Ahmad Syahputra',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta',
                'no_telp' => '081234567890',
                'email' => 'ahmad@example.com',
            ],
            [
                'kode_pelanggan' => 'PLG002',
                'nama' => 'Siti Aisyah',
                'alamat' => 'Jl. Sudirman No. 20, Bandung',
                'no_telp' => '082345678901',
                'email' => 'siti@example.com',
            ],
            [
                'kode_pelanggan' => 'PLG003',
                'nama' => 'Budi Santoso',
                'alamat' => 'Jl. Gajah Mada No. 5, Surabaya',
                'no_telp' => '083456789012',
                'email' => 'budi@example.com',
            ],
        ]);
    }
}