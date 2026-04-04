<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    protected $table = 'participation';

    // Pas de timestamps automatiques (on a notre propre colonne inscrit_le)
    public $timestamps = false;

    // Laravel suppose qu'il existe une colonne 'id' par défaut.
    // Notre table a une clé primaire composite, donc on désactive l'auto-increment.
    public $incrementing = false;

    // On définit la clé primaire sur une des deux colonnes pour que ->save() fonctionne.
    // Laravel ne gère pas les vraies clés composites nativement, mais ça suffit pour nous.
    protected $primaryKey = 'utilisateur_id';

    protected $fillable = ['utilisateur_id', 'creneau_id', 'statut'];

    // La participation appartient à un utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    // La participation appartient à un créneau
    public function creneau()
    {
        return $this->belongsTo(Creneau::class, 'creneau_id');
    }
}
