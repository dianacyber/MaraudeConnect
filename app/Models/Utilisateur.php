<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    protected $table = 'utilisateur';

    // Colonnes que l'on autorise à remplir en masse (sécurité Laravel)
    protected $fillable = ['nom', 'email', 'mot_de_passe', 'role_id'];

    // Laravel ne doit jamais renvoyer le mot_de_passe dans les réponses JSON
    protected $hidden = ['mot_de_passe'];

    // Un utilisateur appartient à un rôle
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Un utilisateur a créé plusieurs créneaux (en tant qu'admin)
    public function creneaux()
    {
        return $this->hasMany(Creneau::class, 'createur_id');
    }

    // Un utilisateur participe à plusieurs créneaux (via la table participation)
    public function participations()
    {
        return $this->hasMany(Participation::class, 'utilisateur_id');
    }

    // Vérifie si l'utilisateur est admin (role_id = 1)
    public function estAdmin()
    {
        return $this->role_id === 1;
    }
}
