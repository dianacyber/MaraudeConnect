<!DOCTYPE html>
{{-- lang="fr" : obligatoire pour l'accessibilité E6 --}}
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MaraudeConnect</title>
    {{-- Bootstrap 5 CDN : pas besoin de npm, chargé depuis internet --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fond légèrement cassé, moins blanc clinique */
        body {
            background-color: #f4f1ec;
            font-family: Georgia, serif;
            color: #2c2c2c;
        }

        /* Navbar couleur chaude au lieu du noir Bootstrap */
        .navbar {
            background-color: #2e4a3e !important;
            border-bottom: 3px solid #c8a96e;
        }

        .navbar-brand {
            font-size: 1.3rem;
            font-weight: bold;
            letter-spacing: 1px;
            color: #f0e6d3 !important;
        }

        /* Titres h1 plus humains */
        h1 {
            font-size: 1.6rem;
            color: #2e4a3e;
            border-bottom: 2px solid #c8a96e;
            padding-bottom: 0.4rem;
            margin-bottom: 1.5rem;
        }

        /* Cartes créneaux : ombre douce, pas de bord bleu Bootstrap */
        .card {
            border: 1px solid #ddd;
            border-left: 4px solid #2e4a3e;
            background-color: #fff;
            box-shadow: 1px 2px 6px rgba(0,0,0,0.06);
            border-radius: 6px;
        }

        /* Bouton principal : vert forêt */
        .btn-primary {
            background-color: #2e4a3e;
            border-color: #2e4a3e;
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #1e3329;
            border-color: #1e3329;
        }

        /* Bouton succès (créer créneau) : ocre */
        .btn-success {
            background-color: #c8a96e;
            border-color: #c8a96e;
            color: #fff;
        }
        .btn-success:hover {
            background-color: #b0924f;
            border-color: #b0924f;
        }

        /* Formulaires : fond blanc, focus vert */
        .form-control:focus {
            border-color: #2e4a3e;
            box-shadow: 0 0 0 0.2rem rgba(46,74,62,0.2);
        }

        /* Container un peu plus aéré */
        main.container {
            max-width: 860px;
        }
    </style>
</head>
<body>

    {{-- BARRE DE NAVIGATION --}}
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('creneaux.index') }}">MaraudeConnect</a>

            {{-- On affiche le menu seulement si l'utilisateur est connecté --}}
            {{-- session('user') = données stockées en session lors de la connexion --}}
            @if (session('user'))
                <div class="d-flex align-items-center gap-3">
                    {{-- Lien "Mes inscriptions" uniquement pour les bénévoles --}}
                    @if (session('user')['role'] === 'benevole')
                        <a href="{{ route('participations.mes') }}" class="btn btn-outline-light btn-sm">
                            Mes inscriptions
                        </a>
                    @endif

                    {{-- Affiche le nom et le rôle de l'utilisateur connecté --}}
                    <span class="text-white">
                        {{ session('user')['nom'] }}
                        <span class="badge bg-secondary">{{ session('user')['role'] }}</span>
                    </span>
                    {{-- Formulaire de déconnexion (POST pour la sécurité CSRF) --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            Déconnexion
                        </button>
                    </form>
                </div>
            @else
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-light btn-sm">Inscription</a>
                </div>
            @endif
        </div>
    </nav>

    {{-- CONTENU PRINCIPAL --}}
    <main class="container mt-4" role="main">

        {{-- Message de succès (ex: "Inscription réussie") --}}
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- Message d'erreur global --}}
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- @yield = emplacement réservé, rempli par chaque vue enfant --}}
        @yield('contenu')

    </main>

    {{-- Bootstrap JS (pour les modales de confirmation) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
