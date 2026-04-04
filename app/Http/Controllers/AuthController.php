<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // -------------------------------------------------------
    // INSCRIPTION
    // -------------------------------------------------------

    // Affiche le formulaire d'inscription (GET /register)
    public function showRegister()
    {
        return view('auth.register');
    }

    // Traite le formulaire d'inscription (POST /register)
    public function register(Request $request)
    {
        // validate() vérifie les données du formulaire
        // Si une règle échoue, Laravel redirige automatiquement avec les erreurs
        $request->validate([
            'nom'          => 'required|max:100',
            'email'        => 'required|email|unique:utilisateur,email',
            // unique:utilisateur,email = vérifie que l'email n'existe pas déjà dans la table
            'mot_de_passe' => [
                'required',
                'min:12',               // 12 caractères minimum
                'confirmed',            // doit correspondre à mot_de_passe_confirmation
                'regex:/[A-Z]/',        // au moins une majuscule
                'regex:/[0-9]/',        // au moins un chiffre
                'regex:/[@$!%*?&\-_#]/', // au moins un caractère spécial
            ],
        ], [
            // Messages d'erreur en français
            'nom.required'          => 'Le nom est obligatoire.',
            'email.required'        => "L'email est obligatoire.",
            'email.email'           => "L'email n'est pas valide.",
            'email.unique'          => 'Cet email est déjà utilisé.',
            'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
            'mot_de_passe.min'      => 'Le mot de passe doit faire au moins 12 caractères.',
            'mot_de_passe.confirmed' => 'Les mots de passe ne correspondent pas.',
            'mot_de_passe.regex'    => 'Le mot de passe doit contenir une majuscule, un chiffre et un caractère spécial (@$!%*?&-_#).',
        ]);

        // Crée l'utilisateur en base
        // Hash::make() chiffre le mot de passe avec bcrypt (jamais en clair !)
        Utilisateur::create([
            'nom'          => $request->nom,
            'email'        => $request->email,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'role_id'      => 2, // 2 = bénévole par défaut
        ]);

        return redirect()->route('login')->with('success', 'Compte créé ! Vous pouvez vous connecter.');
    }

    // -------------------------------------------------------
    // CONNEXION
    // -------------------------------------------------------

    // Affiche le formulaire de connexion (GET /login)
    public function showLogin()
    {
        return view('auth.login');
    }

    // Traite le formulaire de connexion (POST /login)
    public function login(Request $request)
    {
        $request->validate([
            'email'        => 'required|email',
            'mot_de_passe' => 'required',
        ], [
            'email.required'        => "L'email est obligatoire.",
            'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
        ]);

        // Cherche l'utilisateur par email dans la base
        $utilisateur = Utilisateur::where('email', $request->email)->first();

        // Hash::check() compare le mot de passe saisi avec le hash en base
        if (!$utilisateur || !Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return back()->withErrors(['mot_de_passe' => 'Email ou mot de passe incorrect.']);
        }

        // Stocke les infos utiles en session (pas le mot de passe !)
        $request->session()->put('user', [
            'id'   => $utilisateur->id,
            'nom'  => $utilisateur->nom,
            'role' => $utilisateur->role->libelle, // 'admin' ou 'benevole'
        ]);

        return redirect()->route('creneaux.index');
    }

    // -------------------------------------------------------
    // DÉCONNEXION
    // -------------------------------------------------------

    // Vide la session et redirige (POST /logout)
    public function logout(Request $request)
    {
        // Supprime toutes les données de session
        $request->session()->flush();

        return redirect()->route('login')->with('success', 'Vous êtes déconnecté.');
    }
}
