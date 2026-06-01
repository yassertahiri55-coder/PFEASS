<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sinistre;
use App\Models\User;

class SinistreAgentSeeder extends Seeder
{
    /**
     * Associe tous les sinistres à un agent existant.
     */
    public function run(): void
    {
        // Prend le premier agent trouvé
        $agent = User::where('role', 'agent')->first();
        if (!$agent) {
            $this->command->warn('Aucun agent trouvé (role = agent).');
            return;
        }
        $count = 0;
        foreach (Sinistre::all() as $sinistre) {
            if ($sinistre->user_id !== $agent->id) {
                $sinistre->user_id = $agent->id;
                $sinistre->save();
                $count++;
            }
        }
        $this->command->info("$count sinistres liés à l'agent #{$agent->id} ({$agent->email})");
    }
}