<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

// Les tests Unit n'ont pas besoin de Laravel ni de la base de données
// Ils testent une logique pure (ici : les règles du mot de passe)
class MotDePasseTest extends TestCase
{
    // -----------------------------------------------------------
    // Méthode helper : applique les mêmes règles que le contrôleur
    // Retourne true si le mot de passe est valide, false sinon
    // -----------------------------------------------------------
    private function estValide(string $mdp): bool
    {
        if (strlen($mdp) < 12)          return false; // min 12 caractères
        if (!preg_match('/[A-Z]/', $mdp)) return false; // au moins une majuscule
        if (!preg_match('/[0-9]/', $mdp)) return false; // au moins un chiffre
        if (!preg_match('/[@$!%*?&\-_#]/', $mdp)) return false; // caractère spécial
        return true;
    }

    // -----------------------------------------------------------
    // Chaque méthode qui commence par "test" est lancée par PHPUnit
    // -----------------------------------------------------------

    /** @test */
    public function mot_de_passe_valide_est_accepte()
    {
        // assertTrue : le test passe si la valeur est true
        // 'MonMdp123!' = 10 chars, trop court → on utilise 12+ chars
        $this->assertTrue($this->estValide('MonMdp123456!'));
        $this->assertTrue($this->estValide('Azerty123456@'));
    }

    /** @test */
    public function mot_de_passe_trop_court_est_refuse()
    {
        // assertFalse : le test passe si la valeur est false
        $this->assertFalse($this->estValide('Court1!'));
    }

    /** @test */
    public function mot_de_passe_sans_majuscule_est_refuse()
    {
        $this->assertFalse($this->estValide('monmdp123456!'));
    }

    /** @test */
    public function mot_de_passe_sans_chiffre_est_refuse()
    {
        $this->assertFalse($this->estValide('MonMotDePasse!'));
    }

    /** @test */
    public function mot_de_passe_sans_caractere_special_est_refuse()
    {
        $this->assertFalse($this->estValide('MonMotDePasse123'));
    }
}
