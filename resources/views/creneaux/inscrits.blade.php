@extends('layout')

@section('contenu')
<h1 class="mb-1">Inscrits — {{ $creneau->titre }}</h1>
<p class="text-muted mb-4">{{ $creneau->lieu }} | {{ \Carbon\Carbon::parse($creneau->date_heure)->format('d/m/Y à H:i') }}</p>

<a href="{{ route('creneaux.index') }}" class="btn btn-secondary btn-sm mb-3">← Retour</a>

@if ($participations->isEmpty())
    <p class="text-muted">Aucun inscrit pour ce créneau.</p>
@else
    <table class="table table-bordered" aria-label="Liste des inscrits">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Statut</th>
                <th>Inscrit le</th>
            </tr>
        </thead>
        <tbody>
            {{-- $participations vient du contrôleur, avec ->utilisateur chargé en avance --}}
            @foreach ($participations as $p)
                <tr>
                    <td>{{ $p->utilisateur->nom }}</td>
                    <td>{{ $p->utilisateur->email }}</td>
                    <td>
                        {{-- Badge couleur selon le statut --}}
                        @if ($p->statut === 'inscrit')
                            <span class="badge bg-success">Inscrit</span>
                        @else
                            <span class="badge bg-danger">Annulé</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($p->inscrit_le)->format('d/m/Y à H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
