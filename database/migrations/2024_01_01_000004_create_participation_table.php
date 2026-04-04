<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table 'participation'.
     *
     * C'est une table d'association PORTEUSE DE DONNÉES entre utilisateur et creneau.
     * Elle modélise la relation many-to-many (N,N) avec des données supplémentaires :
     *   - statut : l'utilisateur est 'inscrit' ou a 'annule' sa participation
     *   - inscrit_le : la date à laquelle l'inscription a été faite
     *
     * Un utilisateur peut s'inscrire à plusieurs créneaux,
     * et un créneau peut avoir plusieurs utilisateurs inscrits.
     */
    public function up(): void
    {
        Schema::create('participation', function (Blueprint $table) {
            // Clé étrangère vers utilisateur
            $table->foreignId('utilisateur_id')->constrained('utilisateur')->onDelete('cascade');
            // onDelete('cascade') : si l'utilisateur est supprimé, ses participations aussi

            // Clé étrangère vers creneau
            $table->foreignId('creneau_id')->constrained('creneau')->onDelete('cascade');
            // onDelete('cascade') : si le créneau est supprimé, les participations aussi

            // Clé primaire composite : un utilisateur ne peut s'inscrire qu'une fois par créneau
            $table->primary(['utilisateur_id', 'creneau_id']);

            // ENUM : valeur limitée à 'inscrit' ou 'annule'
            // Valeur par défaut : 'inscrit' (quand on crée la participation)
            $table->enum('statut', ['inscrit', 'annule'])->default('inscrit');

            // Date d'inscription : remplie automatiquement à la création
            $table->timestamp('inscrit_le')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participation');
    }
};
