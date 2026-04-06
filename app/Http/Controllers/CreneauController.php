<?php

namespace App\Http\Controllers;

use App\Models\Creneau;
use App\Models\Participation;
use Illuminate\Http\Request;

class CreneauController extends Controller
{
    // Vérifie si l'utilisateur connecté est admin
    // Retourne true/false — utilisé dans toutes les méthodes ci-dessous
    private function estAdmin(Request $request): bool
    {
        $user = $request->session()->get('user'); // null si pas connecté
        return $user && $user['role'] === 'admin';
        // $user && ... = on vérifie d'abord que $user n'est pas null
        // sinon $user['role'] planterait avec "array offset on null"
    }

    // Affiche la liste des créneaux (GET /creneaux)
    // Accessible sans être connecté (visiteur peut consulter les créneaux)
    public function index(Request $request)
    {
        $creneaux = Creneau::orderBy('date_heure', 'asc')->get();

        // Si un utilisateur est connecté, on calcule ses inscriptions
        // Sinon $userId est null et dejaInscrit sera false pour tous les créneaux
        $userId = $request->session()->get('user')['id'] ?? null;

        foreach ($creneaux as $creneau) {
            $creneau->dejaInscrit = $userId
                ? Participation::where('utilisateur_id', $userId)
                    ->where('creneau_id', $creneau->id)
                    ->where('statut', 'inscrit')
                    ->exists()
                : false;
        }

        return view('creneaux.index', ['creneaux' => $creneaux]);
    }

    // Affiche le formulaire de création (GET /creneaux/create)
    public function create(Request $request)
    {
        if (!$this->estAdmin($request)) {
            return redirect()->route('creneaux.index');
        }

        return view('creneaux.create');
    }

    // Enregistre le nouveau créneau (POST /creneaux)
    public function store(Request $request)
    {
        if (!$this->estAdmin($request)) {
            return redirect()->route('creneaux.index');
        }

        $request->validate([
            'titre'      => 'required|max:150',
            'lieu'       => 'required|max:150',
            'date_heure' => 'required|date',
            'places_max' => 'required|integer|min:1',
        ], [
            'titre.required'      => 'Le titre est obligatoire.',
            'lieu.required'       => 'Le lieu est obligatoire.',
            'date_heure.required' => 'La date est obligatoire.',
            'date_heure.date'     => 'La date n\'est pas valide.',
            'places_max.required' => 'Le nombre de places est obligatoire.',
            'places_max.min'      => 'Il faut au moins 1 place.',
        ]);

        Creneau::create([
            'titre'       => $request->titre,
            'lieu'        => $request->lieu,
            'date_heure'  => $request->date_heure,
            'places_max'  => $request->places_max,
            'createur_id' => $request->session()->get('user')['id'],
        ]);

        return redirect()->route('creneaux.index')->with('success', 'Créneau créé avec succès.');
    }

    // Affiche le formulaire de modification (GET /creneaux/{id}/edit)
    public function edit(Request $request, $id)
    {
        if (!$this->estAdmin($request)) {
            return redirect()->route('creneaux.index');
        }

        // findOrFail : récupère le créneau ou renvoie une erreur 404 automatiquement
        $creneau = Creneau::findOrFail($id);

        return view('creneaux.edit', ['creneau' => $creneau]);
    }

    // Enregistre les modifications (PUT /creneaux/{id})
    public function update(Request $request, $id)
    {
        if (!$this->estAdmin($request)) {
            return redirect()->route('creneaux.index');
        }

        $request->validate([
            'titre'      => 'required|max:150',
            'lieu'       => 'required|max:150',
            'date_heure' => 'required|date',
            'places_max' => 'required|integer|min:1',
        ], [
            'titre.required'      => 'Le titre est obligatoire.',
            'lieu.required'       => 'Le lieu est obligatoire.',
            'date_heure.required' => 'La date est obligatoire.',
            'places_max.min'      => 'Il faut au moins 1 place.',
        ]);

        $creneau = Creneau::findOrFail($id);

        // update() modifie uniquement les colonnes listées dans $fillable
        $creneau->update([
            'titre'      => $request->titre,
            'lieu'       => $request->lieu,
            'date_heure' => $request->date_heure,
            'places_max' => $request->places_max,
        ]);

        return redirect()->route('creneaux.index')->with('success', 'Créneau modifié avec succès.');
    }

    // Supprime un créneau (DELETE /creneaux/{id})
    public function destroy(Request $request, $id)
    {
        if (!$this->estAdmin($request)) {
            return redirect()->route('creneaux.index');
        }

        $creneau = Creneau::findOrFail($id);
        $creneau->delete(); // Les participations liées sont supprimées par cascade (onDelete)

        return redirect()->route('creneaux.index')->with('success', 'Créneau supprimé.');
    }

    // Affiche la liste des inscrits d'un créneau (GET /creneaux/{id}/inscrits)
    public function inscrits(Request $request, $id)
    {
        if (!$this->estAdmin($request)) {
            return redirect()->route('creneaux.index');
        }

        $creneau = Creneau::findOrFail($id);

        // with('utilisateur') = charge les données utilisateur en une seule requête SQL
        // (évite le problème N+1 : une requête par ligne au lieu de N requêtes)
        $participations = Participation::where('creneau_id', $id)
            ->with('utilisateur')
            ->get();

        return view('creneaux.inscrits', [
            'creneau'        => $creneau,
            'participations' => $participations,
        ]);
    }
}
