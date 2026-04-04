<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Les tests Feature simulent de vraies requêtes HTTP vers l'application
class InscriptionTest extends TestCase
{
    // RefreshDatabase : recrée les tables avant chaque test (sur SQLite en mémoire)
    // Comme ça chaque test repart d'une base vide et propre
    use RefreshDatabase;

    // -----------------------------------------------------------
    // Données nécessaires avant chaque test : les 2 rôles
    // -----------------------------------------------------------
    protected function setUp(): void
    {
        parent::setUp();

        // On insère les rôles manuellement car le seeder ne tourne pas automatiquement
        \DB::table('role')->insert([
            ['id' => 1, 'libelle' => 'admin'],
            ['id' => 2, 'libelle' => 'benevole'],
        ]);
    }

    // -----------------------------------------------------------
    // TEST 1 : inscription avec des données valides
    // -----------------------------------------------------------

    /** @test */
    public function inscription_avec_donnees_valides_cree_un_compte()
    {
        // $this->post() simule l'envoi du formulaire d'inscription
        $response = $this->post('/register', [
            'nom'                      => 'Diana Test',
            'email'                    => 'diana@test.fr',
            'mot_de_passe'             => 'MonMdp123456!',
            'mot_de_passe_confirmation' => 'MonMdp123456!',
        ]);

        // assertRedirect : vérifie que Laravel redirige bien vers /login
        $response->assertRedirect('/login');

        // assertDatabaseHas : vérifie qu'une ligne existe dans la table
        $this->assertDatabaseHas('utilisateur', [
            'email' => 'diana@test.fr',
            'nom'   => 'Diana Test',
        ]);
    }

    // -----------------------------------------------------------
    // TEST 2 : mot de passe trop court → erreur
    // -----------------------------------------------------------

    /** @test */
    public function inscription_avec_mot_de_passe_trop_court_echoue()
    {
        $response = $this->post('/register', [
            'nom'                      => 'Diana Test',
            'email'                    => 'diana@test.fr',
            'mot_de_passe'             => 'Court1!',
            'mot_de_passe_confirmation' => 'Court1!',
        ]);

        // assertSessionHasErrors : vérifie qu'il y a une erreur sur le champ mot_de_passe
        $response->assertSessionHasErrors('mot_de_passe');

        // Vérifie qu'aucun compte n'a été créé
        $this->assertDatabaseMissing('utilisateur', ['email' => 'diana@test.fr']);
    }

    // -----------------------------------------------------------
    // TEST 3 : protection XSS — le script ne doit pas s'exécuter
    // -----------------------------------------------------------

    /** @test */
    public function le_nom_avec_balise_script_est_eschappe_dans_la_vue()
    {
        // Un attaquant essaie d'injecter du JavaScript dans le champ nom
        $nomXSS = '<script>alert("xss")</script>';

        // On crée directement un utilisateur en base avec ce nom malveillant
        \DB::table('utilisateur')->insert([
            'nom'          => $nomXSS,
            'email'        => 'hacker@test.fr',
            'mot_de_passe' => bcrypt('Hacker123456!'),
            'role_id'      => 2,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // On connecte cet utilisateur manuellement via la session
        $response = $this->withSession([
            'user' => ['id' => 1, 'nom' => $nomXSS, 'role' => 'benevole']
        ])->get('/creneaux');

        // assertSee : vérifie que le HTML contient la version ÉCHAPPÉE
        // &lt;script&gt; = Blade échappe automatiquement {{ }} en HTML entities
        $response->assertSee('&lt;script&gt;', false);

        // assertDontSee : vérifie que le vrai <script> n'apparaît PAS dans le HTML brut
        $response->assertDontSee('<script>alert("xss")</script>', false);
    }

    // -----------------------------------------------------------
    // TEST 4 : confirmation de mot de passe incorrecte
    // -----------------------------------------------------------

    /** @test */
    public function inscription_echoue_si_confirmation_differente()
    {
        $response = $this->post('/register', [
            'nom'                      => 'Diana Test',
            'email'                    => 'diana@test.fr',
            'mot_de_passe'             => 'MonMdp123456!',
            'mot_de_passe_confirmation' => 'AutreMdp123!',
        ]);

        $response->assertSessionHasErrors('mot_de_passe');
        $this->assertDatabaseMissing('utilisateur', ['email' => 'diana@test.fr']);
    }
}
