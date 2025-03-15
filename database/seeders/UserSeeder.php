<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $file = File::get(database_path('data/users.json'));
        $data = json_decode($file);

        foreach ($data->users as $item) {
            User::create([
                'name' => $item->name,
                'email' => $item->email,
                'password' => Hash::make($item->password),
                'role' => $item->role,
            ]);
        }
    }
}