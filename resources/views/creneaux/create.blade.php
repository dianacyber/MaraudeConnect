@extends('layout')

@section('contenu')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="mb-4">Nouveau créneau</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $erreur)
                        <li>{{ $erreur }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('creneaux.store') }}">
            @csrf

            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" id="titre" name="titre"
                       class="form-control" value="{{ old('titre') }}" required>
            </div>

            <div class="mb-3">
                <label for="lieu" class="form-label">Lieu</label>
                <input type="text" id="lieu" name="lieu"
                       class="form-control" value="{{ old('lieu') }}" required>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" id="date" name="date"
                       class="form-control" value="{{ old('date') }}" required>
            </div>

            <div class="mb-3">
                <label for="heure" class="form-label">Heure</label>
                <select id="heure" name="heure" class="form-select" required>
                    <option value="">-- Choisir une heure --</option>
                    <option value="18:00" {{ old('heure') == '18:00' ? 'selected' : '' }}>18h00</option>
                    <option value="20:00" {{ old('heure') == '20:00' ? 'selected' : '' }}>20h00</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="places_max" class="form-label">Nombre de places</label>
                <input type="number" id="places_max" name="places_max"
                       class="form-control" min="1" value="{{ old('places_max') }}" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">Créer</button>
                <a href="{{ route('creneaux.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
