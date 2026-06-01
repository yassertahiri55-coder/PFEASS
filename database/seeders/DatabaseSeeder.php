<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Sinistre;
use App\Models\Dossier;
use App\Models\Notification;
use App\Models\Commentaire;
use App\Models\RendezVous;
use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Crée ou met à jour un expert (role = 'expert')
        \App\Models\User::updateOrCreate(
            ['email' => 'expert@example.com'],
            [
                'name' => 'Expert',
                'prenom' => 'Test',
                'role' => 'expert',
                'password' => bcrypt('password')
            ]
        );

        // Crée ou met à jour un administrateur (role = 'admin')
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'prenom' => 'Test',
                'role' => 'admin',
                'password' => bcrypt('password')
            ]
        );

    // Lier tous les sinistres à un agent pour affichage expert
    $this->call(\Database\Seeders\SinistreAgentSeeder::class);

}
}