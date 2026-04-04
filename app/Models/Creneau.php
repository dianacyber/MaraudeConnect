<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Creneau extends Model
{
    protected $table = 'creneau';

    protected $fillable = ['titre', 'lieu', 'date_heure', 'places_max', 'createur_id'];

    // Le créateur du créneau (un utilisateur admin)
    public function createur()
    {
        return $this->belongsTo(Utilisateur::class, 'createur_id');
    }

    // Les participations liées à ce créneau
    public function participations()
    {
        return $this->hasMany(Participation::class, 'creneau_id');
    }

    // Compte le nombre d'inscrits (statut = 'inscrit' uniquement)
    public function nbInscrits()
    {
        return $this->participations()->where('statut', 'inscrit')->count();
    }

    // Vérifie s'il reste des places
    public function aDesPaces()
    {
        return $this->nbInscrits() < $this->places_max;
    }
}
