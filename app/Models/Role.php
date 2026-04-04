<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Le nom de la table en base (Laravel chercherait 'roles' par défaut, on corrige)
    protected $table = 'role';

    // Pas de created_at / updated_at sur cette table
    public $timestamps = false;

    // Un rôle peut avoir plusieurs utilisateurs
    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class, 'role_id');
    }
}
