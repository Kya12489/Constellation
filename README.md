# Constellation

Constellation est une application web construite avec le framework **Laravel** et un frontend en **Vue.js** via Inertia et Breeze.
Le projet a pour but de servir de base moderne pour une application complète en PHP/Vue avec authentification, API et gestion d'associations.

Utilisation du framwork Laravel afin de faire le projet plus rapidement grâce au connaissance de base + installation de inertia afin de découvrir une nouvelle technologie (vue.js avec laravel) découverte de nouvelle syntaxe qui semble plus moderne et simple d'utilisation. Découverte de la syntaxe grâce au tutoriel du site Vue.js (ajout de la librairie js-confettie //non supprimer pour le moment. Il sera evidemment supprimée si le site est mis en production ).

## Fonctionnalités

- ✅ Verification de la validité des site web enregistré sur l'api des associations (fait grace a une requête http)
- ✅ Authentification utilisateur avec Laravel Breeze + Inertia.js
- ✅ Frontend Vue.js réactif avec Inertia.js (composants, pages, layout)
- ✅ API REST Laravel pour la récupération de données
- ✅ Gestion des associations avec système de rating intégré
- ✅ Compilation des assets via Vite, Tailwind CSS et PostCSS
- ✅ Architecture modulaire et extensible

## Stack technique

- **Backend** : Laravel (PHP 8.x)
- **Frontend** : Vue.js 3 avec Inertia.js et Laravel Breeze
- **Build & styles** : Vite, Tailwind CSS, PostCSS
- **Base de données** : MySQL/MariaDB/PostgreSQL (configurable)
- **Tests** : PHPUnit

## Prérequis

- PHP 8.0 ou supérieur
- Composer
- Node.js 16+ et npm
- Serveur de base de données (MySQL, MariaDB, PostgreSQL, etc.)
- Extensions PHP : OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/Kya12489/Constellation.git
cd Constellation
```

### 2. Installer les dépendances

```bash
# Dépendances PHP
composer install

# Dépendances Node.js
npm install
```

### 3. Configuration d'environnement

```bash
# Créer le fichier .env
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### 4. Configurer la base de données

Le fichier Constellation-bdd.sql est le script de la base de données.
Vous pouvez modifier le nom de la table a conditions de mettre a jour votre .env .
Le fichier se trouve dans la racine vous pouvez l'installez en local avec xampp (ou autre) ou directement avec une base de données.

Modifier le fichier `.env` avec vos identifiants de base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=constellation 
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Lancer les migrations

```bash
php artisan migrate
# php artisan db:seed   # pour ajouter des données de test (optionnel)
```

## Démarrage en développement

### Terminal 1 - Backend

```bash
php artisan serve
```

Le backend sera disponible sur `http://localhost:8000`

### Terminal 2 - Frontend (Vite)

```bash
npm run dev
```

L'application sera compilée en temps réel et accessible sur `http://localhost:8000`

## Scripts disponibles

### Développement

```bash
# Serveur Laravel
php artisan serve

# Compilation frontend en temps réel
npm run dev

# Builder pour la production
npm run build
```

### Tests

```bash
# Lancer tous les tests
php artisan test

# Tests avec couverture
php artisan test --coverage
```

### Artisan (commandes Laravel)

```bash
# Vider les caches
php artisan cache:clear
php artisan config:clear

# Créer une nouvelle migration
php artisan make:migration nom_migration

# Créer un nouveau modèle
php artisan make:model NomModele
```

## Structure du projet

```
Constellation/
├── app/                    # Code application (Modèles, Contrôleurs, etc.)
├── bootstrap/              # Bootstrap de l'application
├── config/                 # Fichiers de configuration
├── database/               # Migrations et seeders
├── public/                 # Fichiers publics (assets compilés, images)
├── resources/              # Vues Vue.js, CSS, images source
│   ├── js/                 # Composants Vue et pages
│   └── css/                # Styles (Tailwind)
├── routes/                 # Routes API et web
├── storage/                # Fichiers générés (logs, cache, uploads)
├── tests/                  # Tests PHPUnit
├── vite.config.js          # Configuration Vite
├── tailwind.config.js      # Configuration Tailwind
├── package.json            # Dépendances npm
└── composer.json           # Dépendances PHP
```

## Contribution

Les contributions sont les bienvenues ! Voici comment contribuer :

1. **Fork** le dépôt
2. **Créer une branche** pour ta fonctionnalité (`git checkout -b feature/ma-fonctionnalite`)
3. **Commit** les changements (`git commit -m 'Ajouter ma fonctionnalité'`)
4. **Push** vers la branche (`git push origin feature/ma-fonctionnalite`)
5. **Ouvrir une Pull Request**

## Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## Support

Pour toute question ou problème, n'hésites pas à ouvrir une [issue](https://github.com/Kya12489/Constellation/issues).

---

**Construit avec ❤️ en PHP et Vue.js**
