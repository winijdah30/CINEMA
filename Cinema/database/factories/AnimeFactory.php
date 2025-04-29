<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Salle;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Anime>
 */
class AnimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $salleIds = Salle::all()->pluck('id');
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'duration' => 25, // Durée entre 60 et 180 min
            'date' => $this->faker->date(),
            'salle_id' => $this->faker->randomElement($salleIds),
        ];
    }
}
