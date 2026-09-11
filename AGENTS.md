# AGENTS.md — Projet TutorLink

## Stack
- Laravel 12 · PHP 8.3
- Base de données : MySQL
- Frontend : Blade · TailwindCSS · Alpine.js · Vite
- Authentification : Laravel Breeze (sans Laratrust, champ `role` natif)
- Données de test : seeders (`RoleSeeder`) + factories

## Structure
- Contrôleurs : `app/Http/Controllers` (DemandeController, OffreController, AvisController, Admin...)
- Modèles : `app/Models` (User, Demande, Offre, Avis, Commentaire)
- Policies & Autorisations : `app/Policies` (DemandePolicy)
- Notifications : `app/Notifications` (via database)
- Form Requests : `app/Http/Requests`
- Migrations & Seeders : `database/migrations`, `database/seeders`
- Routes : `routes/web.php`, `routes/auth.php`
- Vues Blade : `resources/views` (demandes, offres, admin, layouts, notifications)

## Le domaine (TutorLink — Place de marché inversée)
- **Utilisateurs** : 3 rôles stricts (`apprenant`, `tuteur`, `admin`).
- **Demande** : créée par un `apprenant`. Champs : `matiere`, `niveau`, `description`, `budget` (DH), `statut`, `motif_refus`.
  - `statut` prend UNIQUEMENT : `en_attente_moderation`, `ouverte`, `en_cours`, `terminee`, `refusee`.
- **Offre** : soumise par un `tuteur` sur une demande ouverte. Champs : `message`, `tarif_propose`, `statut` (`en_attente`, `acceptee`, `refusee`), `coordonnees_visibles`.
- **Avis** : déposé par l'apprenant pour le tuteur après fin de cours. Champs : `note` (1 à 5), `commentaire`.
- **WhatsApp Direct** : débloqué UNIQUEMENT quand l'offre passe à `acceptee` (`coordonnees_visibles = true`).

## Conventions
- Valider systématiquement les formulaires via des **Form Requests** (`StoreDemandeRequest`, etc.).
- Gérer les droits d'accès via les **Policies** (`$this->authorize`) et non des `if ($user->id)` isolés.
- Utiliser Eloquent avec **Eager Loading** (`with(['apprenant', 'tuteur'])`) pour éviter le problème N+1.
- Respecter le design system existant (`.card`, `.btn-primary`, `.stat-card`, etc.) sans casser la mise en page.
- Messages, notifications et libellés exclusivement en français.

## Interdits
- Ne pas inventer de méthode Eloquent : toujours vérifier les relations dans `app/Models`.
- Ne pas dévoiler les coordonnées du tuteur avant que l'offre ne soit `acceptee`.
- Ne pas contourner la modération admin pour les nouvelles demandes.
- Ne pas modifier la structure de la base de données sans créer une migration Laravel.
- Ne pas ajouter de dépendance ou package tiers sans accord explicite.
