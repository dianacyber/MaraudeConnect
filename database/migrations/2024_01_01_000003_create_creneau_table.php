<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table 'creneau'.
     * Représente un créneau de maraude créé par un admin.
     */
    public function up(): void
    {
        Schema::create('creneau', function (Blueprint $table) {
            $table->id();

            // Titre du créneau ex: "Maraude centre-ville"
            $table->string('titre', 150);

            // Lieu de la maraude ex: "Place de la République"
            $table->string('lieu', 150);

            // Date et heure du créneau (DATETIME en SQL)
            $table->dateTime('date_heure');

            // Nombre de places disponibles (entier positif)
            $table->unsignedInteger('places_max');

            // Clé étrangère : l'admin qui a créé ce créneau
            // Si l'admin est supprimé → restrict (on garde l'historique)
            $table->foreignId('createur_id')->constrained('utilisateur')->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creneau');
    }
};
