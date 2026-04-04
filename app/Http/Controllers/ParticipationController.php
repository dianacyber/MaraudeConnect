<?php

namespace App\Http\Controllers;

use App\Models\Creneau;
use App\Models\Participation;
use Illuminate\Http\Request;

class ParticipationController extends Controller
{
    // S'inscrire à un créneau (POST /creneaux/{id}/inscrire)
    public function inscrire(Request $request, $id)
    {
        if (!$request->session()->has('user')) {
            return redirect()->route('login');
        }

        $userId  = $request->session()->get('user')['id'];
        $creneau = Creneau::findOrFail($id);

        // Vérifie qu'il reste des places
        if (!$creneau->aDesPaces()) {
            return back()->with('error', 'Ce créneau est complet.');
        }

        // Vérifie que l'utilisateur n'est pas déjà inscrit
        $dejaInscrit = Participation::where('utilisateur_id', $userId)
            ->where('creneau_id', $id)
            ->where('statut', 'inscrit')
            ->exists();

        if ($dejaInscrit) {
            return back()->with('error', 'Vous êtes déjà inscrit à ce créneau.');
        }

        // Notre table n'a pas de colonne 'id' (clé composite) donc updateOrCreate ne fonctionne pas.
        // On vérifie si une ligne existe déjà (ex: participation annulée) puis on agit en conséquence.
        $participation = Participation::where('utilisateur_id', $userId)
            ->where('creneau_id', $id)
            ->first(); // Récupère la ligne ou null

        if ($participation) {
            // Une ligne existe déjà (statut 'annule') → on la remet à 'inscrit'
            $participation->statut = 'inscrit';
            $participation->save();
        } else {
            // Aucune ligne → on crée une nouvelle participation
            $p = new Participation();
            $p->utilisateur_id = $userId;
            $p->creneau_id     = $id;
            $p->statut         = 'inscrit';
            $p->save();
        }

        return back()->with('success', 'Inscription enregistrée !');
    }

    // Mes inscriptions : liste des créneaux du bénévole connecté (GET /mes-inscriptions)
    public function mesInscriptions(Request $request)
    {
        if (!$request->session()->has('user')) {
            return redirect()->route('login');
        }

        $userId = $request->session()->get('user')['id'];

        // Récupère toutes les participations de l'utilisateur, avec le créneau associé
        // with('creneau') = charge les données du créneau en une seule requête SQL
        $participations = Participation::where('utilisateur_id', $userId)
            ->with('creneau')
            ->orderBy('inscrit_le', 'desc') // les plus récentes en premier
            ->get();

        return view('participations.mes-inscriptions', ['participations' => $participations]);
    }

    // Annuler son inscription (DELETE /creneaux/{id}/annuler)
    public function annuler(Request $request, $id)
    {
        if (!$request->session()->has('user')) {
            return redirect()->route('login');
        }

        $userId = $request->session()->get('user')['id'];

        // Passe le statut à 'annule' (on ne supprime pas la ligne, on garde l'historique)
        Participation::where('utilisateur_id', $userId)
            ->where('creneau_id', $id)
            ->update(['statut' => 'annule']);

        return back()->with('success', 'Inscription annulée.');
    }
}
