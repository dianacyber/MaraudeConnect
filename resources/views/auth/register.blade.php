{{-- @extends = cette vue utilise le layout comme base --}}
@extends('layout')

@section('contenu')
<div class="row justify-content-center">
    <div class="col-md-5">
        <h1 class="mb-4">Créer un compte</h1>

        {{-- Affiche les erreurs de validation (mot de passe trop court, etc.) --}}
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $erreur)
                        <li>{{ $erreur }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- action = URL qui reçoit le formulaire | method POST = envoi sécurisé --}}
        <form method="POST" action="{{ route('register') }}" novalidate>
            {{-- @csrf génère un token caché pour protéger contre les attaques CSRF --}}
            @csrf

            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input
                    type="text"
                    id="nom"
                    name="nom"
                    class="form-control"
                    aria-label="Votre nom"
                    {{-- old('nom') = remet la valeur si le formulaire a une erreur --}}
                    value="{{ old('nom') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    aria-label="Votre adresse email"
                    value="{{ old('email') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input
                    type="password"
                    id="mot_de_passe"
                    name="mot_de_passe"
                    class="form-control"
                    aria-label="Choisissez un mot de passe"
                    required>
                <div class="form-text">
                    12 caractères min, une majuscule, un chiffre, un caractère spécial.
                </div>
            </div>

            <div class="mb-3">
                <label for="mot_de_passe_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input
                    type="password"
                    id="mot_de_passe_confirmation"
                    name="mot_de_passe_confirmation"
                    class="form-control"
                    aria-label="Confirmez votre mot de passe"
                    required>
            </div>

            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
        </form>

        <p class="mt-3 text-center">
            Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
        </p>
    </div>
</div>
@endsection
