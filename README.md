# Plateforme de Partage de Connaissances - API RESTful

## Contexte du Projet

### Contexte
Une entreprise souhaite développer une plateforme en ligne permettant aux utilisateurs de partager des connaissances, de se former mutuellement et de suivre leurs progrès. L'API REST servira de backend pour des applications web et mobiles, offrant une expérience utilisateur sécurisée et performante.

### Objectifs
- Développer une API RESTful avec Laravel 11 pour gérer les cours, mentors, élèves, communautés, catégories, sous-catégories et tags
- Permettre aux utilisateurs de créer des cours, s'inscrire à des formations, suivre leur progression et obtenir des badges
- Fournir une interface d'administration sécurisée pour la gestion des ressources pédagogiques
- Concevoir des endpoints structurés pour les opérations CRUD sur toutes les entités
- Implémenter un système d'authentification robuste pour protéger les données sensibles
- Gérer efficacement les rôles et permissions (administrateurs, mentors, élèves)
- Intégrer un système de notifications pour informer les utilisateurs des mises à jour importantes

---

## Fonctionnalités Principales

### 1. Gestion des Cours
- Création et gestion complète de cours par les mentors (nom, description, durée, niveau de difficulté)
- Association des cours aux catégories et sous-catégories appropriées
- Gestion des statuts de cours (ouvert, en cours, terminé)
- Système d'inscription et suivi de progression pour les élèves
- Fonctionnalité de tagging pour faciliter la recherche et la classification
- Support pour l'intégration de multiples vidéos pédagogiques par cours

### 2. Gestion des Vidéos
- Interface pour l'ajout et la gestion de vidéos par les mentors
- Association des vidéos à des cours spécifiques avec métadonnées (titre, description, URL)
- Système de visionnage sécurisé pour les élèves inscrits aux cours correspondants
- Fonctionnalités administratives complètes de gestion des ressources vidéo

### 3. Gestion des Catégories et Sous-Catégories
- Structure hiérarchique de classification des cours
- Relation one-to-many entre catégories et sous-catégories
- Interface d'administration pour la gestion complète de la taxonomie

### 4. Gestion des Tags
- Système de tagging flexible pour améliorer la découvrabilité des cours
- Association many-to-many entre cours et tags
- Interface d'administration pour la gestion des tags

### 5. Statistiques et Analytique
- Tableau de bord affichant la distribution des cours par statut
- Visualisation de la répartition des cours par catégories et sous-catégories
- Métriques d'engagement et de complétion des cours

### 6. Bonnes Pratiques
- Validation rigoureuse des données pour garantir l'intégrité et prévenir les attaques
- Gestion standardisée des erreurs avec codes HTTP appropriés et messages explicites
- Documentation complète des endpoints via Swagger

---

## Spécifications Techniques

### Technologies
- **Framework** : Laravel 11
- **Base de données** : MySQL ou PostgreSQL
- **Authentification** : Laravel Sanctum
- **Gestion des dépendances** : Composer
- **Architecture** : Service Repository Pattern
- **Tests** : Pest pour les tests unitaires et fonctionnels
- **Documentation** : L5-Swagger

### Structure des Endpoints API

#### Cours
- `GET /api/v1/courses` : Récupération de tous les cours (avec pagination)
- `GET /api/v1/courses/{id}` : Récupération des détails d'un cours spécifique
- `POST /api/v1/courses` : Création d'un nouveau cours
- `PUT /api/v1/courses/{id}` : Mise à jour d'un cours existant
- `DELETE /api/v1/courses/{id}` : Suppression d'un cours

#### Vidéos
- `POST /api/v1/courses/{id}/videos` : Ajout d'une vidéo à un cours
- `GET /api/v1/courses/{id}/videos` : Récupération des vidéos d'un cours
- `GET /api/v1/videos/{id}` : Récupération des détails d'une vidéo spécifique
- `PUT /api/v1/videos/{id}` : Mise à jour d'une vidéo
- `DELETE /api/v1/videos/{id}` : Suppression d'une vidéo

#### Catégories
- `GET /api/v1/categories` : Récupération de toutes les catégories
- `GET /api/v1/categories/{id}` : Récupération des détails d'une catégorie
- `POST /api/v1/categories` : Création d'une nouvelle catégorie
- `PUT /api/v1/categories/{id}` : Mise à jour d'une catégorie
- `DELETE /api/v1/categories/{id}` : Suppression d'une catégorie

#### Tags
- `GET /api/v1/tags` : Récupération de tous les tags
- `GET /api/v1/tags/{id}` : Récupération des détails d'un tag
- `POST /api/v1/tags` : Création d'un nouveau tag
- `PUT /api/v1/tags/{id}` : Mise à jour d'un tag
- `DELETE /api/v1/tags/{id}` : Suppression d'un tag

#### Statistiques
- `GET /api/v1/stats/courses` : Statistiques globales sur les cours
- `GET /api/v1/stats/categories` : Analyse de la distribution par catégories
- `GET /api/v1/stats/tags` : Analyse de l'utilisation des tags

---

## Déploiement et Configuration

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- MySQL 8.0 ou PostgreSQL 14
- Node.js (pour la compilation des assets frontend si nécessaire)

### Procédure d'Installation
1. **Clonage du dépôt**
   ```bash
   git clone https://github.com/votre-utilisateur/votre-projet.git
   cd votre-projet 
   ```
2. **Installation des dépendances**
   ```bash
   composer install
   ```
3. **Configuration de l'environnement**
   - Création et configuration du fichier d'environnement
   ```bash
   cp .env.example .env
   ```
   - Configuration des paramètres de connexion à la base de données et autres variables d'environnement

4. **Génération de la clé d'application**
   ```bash
   php artisan key:generate
   ```
5. **Exécution des migrations et seeders**
   ```bash
   php artisan migrate --seed
   ```

### Lancement du serveur de développement
```bash
php artisan serve
```

### Commandes Utilitaires
#### Génération de ressources
Création d'un modèle avec migration, contrôleur et repository
```bash
php artisan make:model NomDuModele -mcr
```

#### Création d'un seeder
```bash
php artisan make:seeder NomDuSeeder
```

### Tests
Exécution de la suite de tests avec Pest
```bash
php artisan test
```

### Documentation API avec Swagger
#### Installation de Swagger
```bash
composer require darkaonline/l5-swagger
```

#### Publication de la configuration
```bash
php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"
```

#### Accès à la documentation
La documentation interactive de l'API est accessible à l'adresse http://localhost:8000/api/documentation.

#### Exemple d'annotation Swagger
```php
/**
 * @OA\Get(
 *     path="/api/v1/courses",
 *     summary="Récupération de la liste des cours",
 *     description="Retourne une liste paginée de tous les cours disponibles",
 *     tags={"Cours"},
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Numéro de page",
 *         required=false,
 *         @OA\Schema(type="integer", default=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Liste des cours récupérée avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Course")),
 *             @OA\Property(property="meta", type="object")
 *         )
 *     ),
 *     @OA\Response(response=401, description="Non authentifié"),
 *     @OA\Response(response=403, description="Accès interdit")
 * )
 */
public function index()
{
    // Logique pour lister les cours
}
```

### Contribution
1. Forkez le dépôt du projet
2. Créez une branche dédiée à votre fonctionnalité (`git checkout -b feature/ma-fonctionnalite`)
3. Committez vos modifications (`git commit -m 'Ajout de ma fonctionnalité'`)
4. Poussez la branche vers votre fork (`git push origin feature/ma-fonctionnalite`)
5. Ouvrez une Pull Request vers le dépôt principal

### Licence
Ce projet est distribué sous licence MIT. Veuillez consulter le fichier LICENSE pour plus de détails.

### Contact
Pour toute question technique ou suggestion d'amélioration, veuillez contacter :
**mouadhallaffou@gmail.com**

## Authentification
### 1. Inscription (Register) - Méthode : POST - URL : http://localhost:8000/api/V1/auth/register - Body (JSON) : ``json { "name": "John Doe", "email": "john.doe@example.com", "password": "password123", "password_confirmation": "password123" } 2. **Connexion (Login)** * **Méthode** : POST * **URL** : http://localhost:8000/api/V1/auth/login` * Body (JSON) : json Copy

```
{ "email": "john.doe@example.com", "password": "password123" }
1. Profil Utilisateur (Profile)
2. Méthode : GET
3. URL : http://localhost:8000/api/V1/auth/profile
4. Headers :
   * Authorization: Bearer <token>
5. Déconnexion (Logout)
6. Méthode : POST
7. URL : http://localhost:8000/api/V1/auth/logout
8. Headers :
   * Authorization: Bearer <token>
9. Rafraîchir le Token (Refresh)
10. Méthode : POST
11. URL : http://localhost:8000/api/V1/auth/refresh
12. Headers :
   * Authorization: Bearer <token>
13. Mettre à Jour le Profil (Update Profile)
14. Méthode : POST
15. URL : http://localhost:8000/api/V1/auth/update-profile
16. Headers :
   * Authorization: Bearer <token>
17. Body (JSON) : json Copy

```
{ "name": "John Updated", "email": "john.updated@example.com" }
Rôles et Permissions 1. Lister tous les rôles * Méthode : GET * URL : http://localhost:8000/api/V1/roles 2. Créer un nouveau rôle * Méthode : POST * URL : http://localhost:8000/api/V1/roles * Body (JSON) : json Copy

```
{ "name": "editor", "permissions": ["view courses", "edit courses"] }
1. Modifier un rôle
2. Méthode : PUT
3. URL : http://localhost:8000/api/V1/roles/{id}
4. Body (JSON) : json Copy

```
{ "name": "supereditor", "permissions": ["delete courses"] }
1. Supprimer un rôle
2. Méthode : DELETE
3. URL : http://localhost:8000/api/V1/roles/{id}
4. Assigner un rôle à un utilisateur
5. Méthode : POST
6. URL : http://localhost:8000/api/V1/users/{userId}/assign-role
7. Body (JSON) : json Copy

```
{ "role": "editor" }
1. Retirer un rôle d'un utilisateur
2. Méthode : DELETE
3. URL : http://localhost:8000/api/V1/users/{userId}/remove-role
4. Body (JSON) : json Copy

```
{ "role": "editor" }
1. Synchroniser les permissions d'un rôle
2. Méthode : PUT
3. URL : http://localhost:8000/api/V1/roles/{id}/sync-permissions
4. Body (JSON) : json Copy

```
{ "permissions": ["view courses", "edit courses"] }
Statistiques 1. Statistiques des cours * Méthode : GET * URL : http://localhost:8000/api/V1/stats/courses 2. Statistiques des catégories * Méthode : GET * URL : http://localhost:8000/api/V1/stats/categories 3. Statistiques des tags * Méthode : GET * URL : http://localhost:8000/api/V1/stats/tags Enrôlement (Inscriptions aux Cours) 1. S'inscrire à un cours * Méthode : POST * URL : http://localhost:8000/api/V1/courses/{id}/enroll * Headers : * Authorization: Bearer <token> 2. Lister les inscriptions à un cours * Méthode : GET * URL : http://localhost:8000/api/V1/courses/{id}/enrollments * Headers : * Authorization: Bearer <token> 3. Mettre à jour le statut d'une inscription * Méthode : PUT * URL : http://localhost:8000/api/V2/enrollments/{id} * Headers : * Authorization: Bearer <token> * Body (JSON) : json Copy

```
{ "status": "accepted" }
1. Supprimer une inscription
2. Méthode : DELETE
3. URL : http://localhost:8000/api/V2/enrollments/{id}
4. Headers :
   * Authorization: Bearer <token>


