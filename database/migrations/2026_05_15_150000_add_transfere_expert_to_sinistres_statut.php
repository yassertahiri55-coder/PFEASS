<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ajoute 'transfere_expert' à l'ENUM statut de la table sinistres
        DB::statement("ALTER TABLE sinistres MODIFY statut ENUM('en_attente', 'en_cours', 'valide', 'refuse', 'transfere_expert') DEFAULT 'en_attente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à l'ancien ENUM sans 'transfere_expert'
        DB::statement("ALTER TABLE sinistres MODIFY statut ENUM('en_attente', 'en_cours', 'valide', 'refuse') DEFAULT 'en_attente'");
    }
};
