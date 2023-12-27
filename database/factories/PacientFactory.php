<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PacientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nume' => fake()->lastName(),
            'prenume' => fake()->firstName(),
            'data_nastere' => \Carbon\Carbon::today()->subDays(rand(10000, 30000)),
            'sex' => fake()->numberBetween(1, 2),
            'email' => fake()->unique()->freeEmail(),
            // 'remember_token' => Str::random(10),
            'telefon' => '07' . fake()->numberBetween(10000000, 99999999),
            // 'judet' => fake()->city(),
            'localitate' => fake()->city(),
            'adresa' => fake()->address(),
            'cod_postal' => fake()->numberBetween(100000, 999999),
            'observatii' => fake()->text(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

}
