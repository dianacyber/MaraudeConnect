<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table 'utilisateur'.
     * Doit être créée APRÈS 'role' car elle contient une clé étrangère vers role.
     */
    public function up(): void
    {
        Schema::create('utilisateur', function (Blueprint $table) {
            // Clé primaire
            $table->id();

            // Nom de l'utilisateur : VARCHAR(100)
            $table->string('nom', 100);

            // Email unique : on ne peut pas avoir deux comptes avec le même email
            $table->string('email')->unique();

            // Mot de passe stocké en hash bcrypt (jamais en clair !)
            $table->string('mot_de_passe');

            // Clé étrangère vers la table 'role'
            // ->unsigned() = entier positif (correspond au type de 'id' dans role)
            // ->constrained('role') = ajoute la contrainte FK automatiquement
            // ->onDelete('restrict') = interdit de supprimer un rôle si des utilisateurs l'ont
            $table->foreignId('role_id')->constrained('role')->onDelete('restrict');

            // Timestamps Laravel : ajoute created_at et updated_at automatiquement
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // On supprime d'abord la FK avant de dropper la table
        Schema::dropIfExists('utilisateur');
    }
};
