<?php

namespace Database\Factories;

use App\Models\Pemasok;
use Illuminate\Database\Eloquent\Factories\Factory;

class PemasokFactory extends Factory
{
    protected $model = Pemasok::class;

    public function definition()
    {
        return [
            'nama_pemasok' => $this->faker->company,
            'alamat' => $this->faker->address,
            'telepon' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'catatan' => $this->faker->sentence,
        ];
    }
}