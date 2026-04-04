@extends('layout')

@section('contenu')
<div class="row justify-content-center">
    <div class="col-md-5">
        <h1 class="mb-4">Connexion</h1>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $erreur)
                    <p class="mb-0">{{ $erreur }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

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
                    aria-label="Votre mot de passe"
                    required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>

        <p class="mt-3 text-center">
            Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a>
        </p>
    </div>
</div>
@endsection
