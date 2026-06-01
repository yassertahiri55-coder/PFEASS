<?php

namespace Database\Factories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'dossier_id' => \App\Models\Dossier::factory(),
            'sinistre_id' => \App\Models\Sinistre::factory(),
            'user_id' => \App\Models\User::factory(),
            'type' => $this->faker->randomElement(['pdf', 'image', 'doc']),
            'nom' => $this->faker->word() . '.pdf',
            'chemin' => $this->faker->filePath(),
        ];
    }
}
