# MaraudeConnect

Application web de coordination de bénévoles pour des maraudes associatives.

## Présentation

MaraudeConnect permet à une association de gérer des créneaux de maraude (distribution de repas, aide alimentaire). Les bénévoles peuvent consulter les créneaux disponibles et s'y inscrire via leur compte personnel.

Deux rôles :
- **Admin** : crée, modifie et supprime des créneaux, consulte la liste des inscrits
- **Bénévole** : s'inscrit ou annule sa participation à un créneau, consulte ses inscriptions


## Prérequis

- XAMPP (Apache + MySQL + PHP 8.2)
- Composer

## Installation

1. Cloner le repo dans le dossier `htdocs` de XAMPP :
```bash
git clone https://github.com/dianacyber/MaraudeConnect.git maraudeconnect-laravel
```

2. Installer les dépendances :
```bash
cd maraudeconnect-laravel
composer install
```

3. Copier le fichier d'environnement :
```bash
cp .env.example .env
php artisan key:generate
```

4. Dans `.env`, vérifier la config base de données :
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=maraudeconnect
DB_USERNAME=root
DB_PASSWORD=
```

5. Créer la base dans phpMyAdmin : `http://localhost/phpmyadmin`  
   Créer une base nommée `maraudeconnect`

6. Lancer les migrations et insérer les données de démo :
```bash
php artisan migrate --seed
```

7. Accéder au site : `http://localhost/maraudeconnect-laravel/public`

## Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@maraudeconnect.fr | Admin123! |
| Bénévole | sophie@maraudeconnect.fr | Sophie123! |
| Bénévole | lucas@maraudeconnect.fr | Lucas123! |


## Lancer les tests

```bash
php artisan test
```

11 tests — validation du mot de passe (longueur, majuscule, chiffre, caractère spécial) et protection XSS.
