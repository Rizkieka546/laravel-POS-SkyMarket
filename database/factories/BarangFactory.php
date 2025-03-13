<?php

namespace Database\Factories;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = Kategori::all()->random()->first();
        $user = User::all()->random()->first();

        return [
            'kode_barang' => 'BRG-' . sprintf('%08d', fake()->unique()
                ->numberBetween(1, 9999)),
            'kategori_id' => $kategori->id,
            'nama_barang' => $this->faker->word(),
            'harga_beli' => $this->faker->randomFloat(2, 1000, 50000),
            'harga_jual' => $this->faker->randomFloat(2, 5000, 100000),
            'stok' => $this->faker->numberBetween(1, 100),
            'stok_minimal' => $this->faker->numberBetween(1, 10),
            'gambar' => null,
            'ditarik' => $this->faker->boolean(10),
            'user_id' => $user->id,
        ];
    }
}