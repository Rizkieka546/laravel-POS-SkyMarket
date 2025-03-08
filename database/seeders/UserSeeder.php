<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => 'Admin',
            ],
            [
                'name' => 'Kasir User',
                'email' => 'kasir@gmail.com',
                'password' => Hash::make('kasir123'),
                'role' => 'Kasir',
            ],
            [
                'name' => 'gudang',
                'email' => 'gudang@gmail.com',
                'password' => Hash::make('gudang123'),
                'role' => 'Gudang',
            ],
        ]);
    }
}