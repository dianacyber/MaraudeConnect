<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Une migration = une classe qui décrit comment créer (up) ou supprimer (down) une table
return new class extends Migration
{
    /**
     * Crée la table 'role' en base de données.
     * Appelée avec : php artisan migrate
     */
    public function up(): void
    {
        Schema::create('role', function (Blueprint $table) {
            // Clé primaire auto-incrémentée (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY)
            $table->id();

            // Colonne libelle : VARCHAR(50) NOT NULL
            // Contiendra 'admin' ou 'benevole'
            $table->string('libelle', 50);
        });
        // Pas de timestamps() ici : la table role est simple, pas besoin de created_at/updated_at
    }

    /**
     * Supprime la table 'role'.
     * Appelée avec : php artisan migrate:rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('role');
    }
};
