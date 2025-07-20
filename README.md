# API de Gestion des Utilisateurs

Ce projet est une API RESTful développée avec Laravel 12, permettant la gestion des utilisateurs et le suivi de leurs activités. Elle utilise l'authentification JWT et propose des endpoints sécurisés pour l'administration et la consultation des utilisateurs.

## 🚀 Instructions d'installation

1. **Cloner le dépôt**

```bash
git clone <repo-url> api
cd api
```

2. **Installer les dépendances PHP**

```bash
composer install
```

3. **Copier le fichier d'environnement et générer la clé d'application**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurer la base de données**

Par défaut, l'API utilise SQLite (voir `config/database.php`). Vous pouvez modifier `.env` pour utiliser MySQL ou PostgreSQL si besoin.

5. **Générer la clé JWT**

```bash
php artisan jwt:secret
```

6. **Lancer les migrations**

```bash
php artisan migrate
```

7. **Créer l'utilisateur par defaut**

```bash
php artisan db:seed
```

7. **Démarrer le serveur de développement**

```bash
php artisan serve
```

7. **Données utilisateur par defaut**

-   `Email: test2@example.com`
-   `Password: password2`

## 🗂️ Structure du projet

-   `app/`
    -   `Http/`
        -   `Controllers/Api/` : Contrôleurs principaux (`UserController`, `AuthController`, `ActivityController`)
        -   `Requests/` : Validation des requêtes (`UserRequest`, `AuthRequest`)
        -   `Resources/` : Formatage des réponses API (`UserResource`, `ActivityResource`)
    -   `Models/` : Modèles Eloquent (`User`, `Activity`)
    -   `Repositories/` : Accès aux données (UserRepository, ActivityRepository)
    -   `Services/` : Logique métier (UserService, AuthService, ActivityService, UploadService)
-   `routes/api.php` : Définition des routes de l'API
-   `database/migrations/` : Migrations pour les tables `users`, `activities`, etc.
-   `public/avatars/` : Stockage des avatars utilisateurs

## 🔒 Fonctionnalités principales

-   Authentification JWT (connexion, déconnexion, profil)
-   Gestion des utilisateurs (CRUD, activation/désactivation, upload d'un avatar, filtre dynamique)
-   Suivi des activités des utilisateurs
-   Validation avancée des entrées (FormRequest)
-   Pagination et statistiques utilisateurs

## 🛠️ Technologies utilisées

-   **PHP 8.2+**
-   **Laravel 12**
-   **JWT Auth** (`tymon/jwt-auth`)
-   **Vite** (pour assets)
-   **SQLite** (par défaut) ou MySQL/PostgreSQL

## 📂 Exemple de routes API

-   `POST /api/auth/login` : Connexion (email, mot de passe)
-   `POST /api/auth/logout` : Déconnexion
-   `GET /api/me` : Infos utilisateur connecté
-   `GET /api/users` : Liste paginée des utilisateurs (recherche possible)
-   `POST /api/users` : Création d'utilisateur
-   `PUT /api/users/{id}` : Modification d'utilisateur
-   `DELETE /api/users/{id}` : Suppression d'utilisateur
-   `GET /api/users/{id}/change-status` : Activation/désactivation
-   `GET /api/activities` : Historique des activités de l'utilisateur connecté

## 📌 Remarques

-   Les endpoints (sauf login) nécessitent un token JWT valide dans l'en-tête `Authorization: Bearer <token>`.
-   Les avatars sont stockés dans `public/avatars/`.
-   Les migrations créent les tables nécessaires à l'usage de l'API.

---

Pour toute question, consultez le code source ou ouvrez une issue.
