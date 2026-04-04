<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreneauController;
use App\Http\Controllers\ParticipationController;

// Page d'accueil → redirige vers la liste des créneaux
Route::get('/', function () {
    return redirect()->route('creneaux.index');
});

// -------------------------------------------------------
// ROUTES AUTHENTIFICATION
// -------------------------------------------------------

// GET  /register → affiche le formulaire
// POST /register → traite l'inscription
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// POST /logout → déconnecte (POST pour la protection CSRF)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -------------------------------------------------------
// ROUTES CRÉNEAUX (CRUD)
// -------------------------------------------------------

// Liste des créneaux
Route::get('/creneaux', [CreneauController::class, 'index'])->name('creneaux.index');

// Créer un créneau (formulaire + traitement)
Route::get('/creneaux/create', [CreneauController::class, 'create'])->name('creneaux.create');
Route::post('/creneaux', [CreneauController::class, 'store'])->name('creneaux.store');

// Modifier un créneau (formulaire + traitement)
// {id} = paramètre dynamique dans l'URL, ex: /creneaux/3/edit
Route::get('/creneaux/{id}/edit', [CreneauController::class, 'edit'])->name('creneaux.edit');
Route::put('/creneaux/{id}', [CreneauController::class, 'update'])->name('creneaux.update');

// Supprimer un créneau
Route::delete('/creneaux/{id}', [CreneauController::class, 'destroy'])->name('creneaux.destroy');

// Liste des inscrits d'un créneau (admin)
Route::get('/creneaux/{id}/inscrits', [CreneauController::class, 'inscrits'])->name('creneaux.inscrits');

// -------------------------------------------------------
// ROUTES PARTICIPATIONS
// -------------------------------------------------------

Route::get('/mes-inscriptions', [ParticipationController::class, 'mesInscriptions'])->name('participations.mes');
Route::post('/creneaux/{id}/inscrire', [ParticipationController::class, 'inscrire'])->name('participations.inscrire');
Route::delete('/creneaux/{id}/annuler', [ParticipationController::class, 'annuler'])->name('participations.annuler');
