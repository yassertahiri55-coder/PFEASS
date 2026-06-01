<?php

namespace Database\Factories;

use App\Models\Sinistre;
use Illuminate\Database\Eloquent\Factories\Factory;

class SinistreFactory extends Factory
{
    protected $model = Sinistre::class;

    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(3),
            'type' => $this->faker->randomElement(['Incendie', 'Vol', 'Accident', 'Dégât des eaux']),
            'description' => $this->faker->paragraph(),
            'date_declaration' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'statut' => $this->faker->randomElement(['en_attente', 'en_cours', 'valide', 'refuse']),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
