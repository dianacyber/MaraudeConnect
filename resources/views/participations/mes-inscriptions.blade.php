@extends('layout')

@section('contenu')
<h1 class="mb-4">Mes inscriptions</h1>

@if ($participations->isEmpty())
    <p class="text-muted">Vous n'êtes inscrit à aucun créneau pour l'instant.</p>
    <a href="{{ route('creneaux.index') }}" class="btn btn-primary">Voir les créneaux</a>
@else
    <table class="table table-bordered" aria-label="Mes inscriptions aux créneaux">
        <thead class="table-dark">
            <tr>
                <th>Créneau</th>
                <th>Lieu</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Inscrit le</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($participations as $p)
                <tr>
                    <td>{{ $p->creneau->titre }}</td>
                    <td>{{ $p->creneau->lieu }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->creneau->date_heure)->format('d/m/Y à H:i') }}</td>
                    <td>
                        @if ($p->statut === 'inscrit')
                            <span class="badge bg-success">Inscrit</span>
                        @else
                            <span class="badge bg-secondary">Annulé</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($p->inscrit_le)->format('d/m/Y à H:i') }}</td>
                    <td>
                        @if ($p->statut === 'inscrit')
                            <form method="POST"
                                  action="{{ route('participations.annuler', $p->creneau->id) }}"
                                  onsubmit="return confirm('Annuler cette inscription ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    Annuler
                                </button>
                            </form>
                        @else
                            {{-- Pas d'action si déjà annulé --}}
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('creneaux.index') }}" class="btn btn-secondary btn-sm">← Retour aux créneaux</a>
@endif
@endsection
