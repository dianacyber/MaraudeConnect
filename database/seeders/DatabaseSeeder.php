<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1. Rôles ---
        DB::table('role')->insert([
            ['id' => 1, 'libelle' => 'admin'],
            ['id' => 2, 'libelle' => 'benevole'],
        ]);

        // --- 2. Utilisateurs ---
        DB::table('utilisateur')->insert([
            [
                'nom'          => 'Admin',
                'email'        => 'admin@maraudeconnect.fr',
                'mot_de_passe' => Hash::make('Admin123!'),
                'role_id'      => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nom'          => 'Sophie Martin',
                'email'        => 'sophie@maraudeconnect.fr',
                'mot_de_passe' => Hash::make('Sophie123!'),
                'role_id'      => 2,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'nom'          => 'Lucas Bernard',
                'email'        => 'lucas@maraudeconnect.fr',
                'mot_de_passe' => Hash::make('Lucas123!'),
                'role_id'      => 2,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);

        // --- 3. Créneaux (créateur_id = 1 = admin) ---
        DB::table('creneau')->insert([
            [
                'titre'       => 'Maraude centre-ville',
                'lieu'        => 'Place de la République, Paris',
                'date_heure'  => '2026-04-10 19:00:00',
                'places_max'  => 8,
                'createur_id' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'titre'       => 'Distribution gare du Nord',
                'lieu'        => 'Parvis Gare du Nord, Paris',
                'date_heure'  => '2026-04-14 20:30:00',
                'places_max'  => 5,
                'createur_id' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'titre'       => 'Maraude quartier Belleville',
                'lieu'        => 'Rue de Belleville, Paris',
                'date_heure'  => '2026-04-18 18:00:00',
                'places_max'  => 6,
                'createur_id' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'titre'       => 'Soupe populaire Saint-Denis',
                'lieu'        => 'Place du 8 Mai 1945, Saint-Denis',
                'date_heure'  => '2026-04-22 12:00:00',
                'places_max'  => 10,
                'createur_id' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'titre'       => 'Maraude nocturne Pigalle',
                'lieu'        => 'Boulevard de Clichy, Paris',
                'date_heure'  => '2026-04-25 22:00:00',
                'places_max'  => 4,
                'createur_id' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'titre'       => 'Distribution vêtements Vincennes',
                'lieu'        => 'Bois de Vincennes, Paris',
                'date_heure'  => '2026-05-02 10:00:00',
                'places_max'  => 7,
                'createur_id' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'titre'       => 'Petit-déjeuner solidaire',
                'lieu'        => 'Église Saint-Merri, Paris 4e',
                'date_heure'  => '2026-05-08 08:30:00',
                'places_max'  => 6,
                'createur_id' => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // --- 4. Participations (Sophie inscrite sur 2 créneaux) ---
        DB::table('participation')->insert([
            [
                'utilisateur_id' => 2, // Sophie
                'creneau_id'     => 1, // Maraude centre-ville
                'statut'         => 'inscrit',
                'inscrit_le'     => now(),
            ],
            [
                'utilisateur_id' => 2, // Sophie
                'creneau_id'     => 2, // Distribution gare du Nord
                'statut'         => 'inscrit',
                'inscrit_le'     => now(),
            ],
            [
                'utilisateur_id' => 3, // Lucas
                'creneau_id'     => 1, // Maraude centre-ville
                'statut'         => 'inscrit',
                'inscrit_le'     => now(),
            ],
        ]);
    }
}
