@extends('layout')

@section('contenu')
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Créneaux de maraude</h1>

    {{-- Bouton visible uniquement par les admins --}}
    @if (session('user') && session('user')['role'] === 'admin')
        <a href="{{ route('creneaux.create') }}" class="btn btn-success">
            + Nouveau créneau
        </a>
    @endif
</div>

{{-- $creneaux = variable envoyée par le contrôleur --}}
@forelse ($creneaux as $creneau)
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title">{{ $creneau->titre }}</h5>
                    <p class="card-text text-muted mb-1">
                        📍 {{ $creneau->lieu }} &nbsp;|&nbsp;
                        🗓️ {{ \Carbon\Carbon::parse($creneau->date_heure)->format('d/m/Y à H:i') }}
                    </p>
                    <p class="card-text">
                        {{-- nbInscrits() = méthode définie dans le modèle Creneau --}}
                        <span class="badge bg-info text-dark">
                            {{ $creneau->nbInscrits() }} / {{ $creneau->places_max }} inscrits
                        </span>
                    </p>
                </div>

                <div class="d-flex flex-column gap-2 align-items-end">

                    {{-- BOUTONS ADMIN --}}
                    @if (session('user') && session('user')['role'] === 'admin')
                        <a href="{{ route('creneaux.edit', $creneau->id) }}" class="btn btn-warning btn-sm">
                            Modifier
                        </a>

                        {{-- Formulaire de suppression avec confirmation --}}
                        <form method="POST" action="{{ route('creneaux.destroy', $creneau->id) }}"
                              onsubmit="return confirm('Supprimer ce créneau ?')">
                            @csrf
                            {{-- @method DELETE : HTML ne supporte que GET/POST, --}}
                            {{-- Laravel lit ce champ caché pour simuler DELETE --}}
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                Supprimer
                            </button>
                        </form>

                        <a href="{{ route('creneaux.inscrits', $creneau->id) }}" class="btn btn-secondary btn-sm">
                            Voir inscrits
                        </a>
                    @endif

                    {{-- BOUTONS BENEVOLE --}}
                    @if (session('user') && session('user')['role'] === 'benevole')
                        @if ($creneau->dejaInscrit)
                            {{-- Annuler l'inscription --}}
                            <form method="POST" action="{{ route('participations.annuler', $creneau->id) }}"
                                  onsubmit="return confirm('Annuler votre inscription ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    Annuler mon inscription
                                </button>
                            </form>
                        @elseif ($creneau->aDesPaces())
                            {{-- S'inscrire --}}
                            <form method="POST" action="{{ route('participations.inscrire', $creneau->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    S'inscrire
                                </button>
                            </form>
                        @else
                            <span class="badge bg-danger">Complet</span>
                        @endif
                    @endif

                    {{-- VISITEUR NON CONNECTÉ --}}
                    @if (!session('user'))
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                            Connectez-vous pour vous inscrire
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>
@empty
    {{-- @forelse : si la liste est vide, on affiche ce message --}}
    <p class="text-muted">Aucun créneau disponible pour le moment.</p>
@endforelse
@endsection
