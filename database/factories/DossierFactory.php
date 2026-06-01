<?php

namespace Database\Factories;

use App\Models\Dossier;
use Illuminate\Database\Eloquent\Factories\Factory;

class DossierFactory extends Factory
{
    protected $model = Dossier::class;

    public function definition(): array
    {
        return [
            'numero' => 'DOS' . strtoupper($this->faker->unique()->bothify('######')), // Unique et aléatoire
            'sinistre_id' => \App\Models\Sinistre::factory(),
            'statut' => $this->faker->randomElement(['en_attente', 'en_cours', 'termine', 'refuse']),
            'date_ouverture' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'date_cloture' => $this->faker->optional()->dateTimeBetween('-1 years', 'now'),
        ];
    }
}
