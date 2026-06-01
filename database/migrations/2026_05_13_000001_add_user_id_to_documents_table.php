<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void
    {

        // 1. Ajoute la colonne user_id nullable sans contrainte si elle n'existe pas
        if (!Schema::hasColumn('documents', 'user_id')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('dossier_id');
            });
        }

        // 2. Remplit les valeurs existantes (ici, on met tout à 1, à adapter si besoin)
        DB::table('documents')->update(['user_id' => 1]);

        // 3. Rend la colonne non nullable et ajoute la contrainte
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
