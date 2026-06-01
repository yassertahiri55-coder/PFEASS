<?php

namespace Database\Factories;

use App\Models\Commentaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentaireFactory extends Factory
{
    protected $model = Commentaire::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'dossier_id' => \App\Models\Dossier::factory(),
            'contenu' => $this->faker->paragraph(),
        ];
    }
}
