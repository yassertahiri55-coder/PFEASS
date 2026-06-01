<?php

namespace Database\Factories;

use App\Models\RendezVous;
use Illuminate\Database\Eloquent\Factories\Factory;

class RendezVousFactory extends Factory
{
    protected $model = RendezVous::class;

    public function definition(): array
    {
        return [
            'dossier_id' => \App\Models\Dossier::factory(),
            'date' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'lieu' => $this->faker->city(),
            'description' => $this->faker->sentence(),
        ];
    }
}
