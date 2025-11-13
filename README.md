# API Gestion Propriétés

API Laravel pour la gestion des biens immobiliers (CRUD, filtrage, upload d’images).

---

## 📦 Installation

1. **Cloner le projet :**

git clone https://github.com/Laminelrb/digitup_api.git
cd immobiliers-api

2. **Installer les dépendances :**

composer install

3. **Copier le fichier `.env` et configurer la base de données :**

### Linux / macOS
cp .env.example .env

### Windows
copy .env.example .env

4. **Générer la clé d’application :**

php artisan key:generate

5. **Installer Sanctum :**

composer require laravel/sanctum

# Publier la configuration et la migration
php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider"

6. **Lancer les migrations :**

php artisan migrate --seed

7. **Créer le lien pour les images :**

php artisan storage:link

8. **Lancer le serveur :**

php artisan serve

---

## 🔑 Exemple Variables d'environnement

- APP_NAME=Laravel
- APP_ENV=local
- APP_DEBUG=true
- APP_URL=http://localhost

- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=digitup_company
- DB_USERNAME=root
- DB_PASSWORD=
- APP_URL=http://localhost

- FILESYSTEM_DISK=local

 ### Authentification Sanctum
- SANCTUM_STATEFUL_DOMAINS=localhost:8000
- SESSION_DOMAIN=localhost
- SESSION_DRIVER=database

---

## 🚀 Exemples de requêtes API

URL de base de l'API : http://localhost:8000

### Login

POST `/api/v1/login`

{
  "email": "agent@example.com",
  "password": "password"
}

Réponse :

{
  "token": "..."
}

→ Ensuite, utiliser le token dans les en-têtes :  
Authorization: Bearer YOUR_TOKEN_HERE  
Accept: application/json

---

### Créer un bien immobilier (authentifié) avec images

POST `/api/v1/properties`

Headers :

Authorization: Bearer YOUR_TOKEN_HERE    # Remplace YOUR_TOKEN_HERE par le token reçu après login

Content-Type: multipart/form-data

Body (form-data) :

Clé           | Valeur                        | Type
--------------|-------------------------------|------
type          | Appartement                   | Text
nbr_piece     | 3                             | Text
surface       | 80                            | Text
price         | 150000                        | Text
city          | Alger                         | Text
description   | Beau T3 lumineux              | Text
status        | disponible                    | Text
published     | true                          | Text
images[]      | fichier1.jpg                  | File
images[]      | fichier2.jpg                  | File

Exemple de réponse :


{
  "success": true,
  "message": "Propriété récupérée avec succès.",
  "data": {
    "id": 12,
    "type": "Appartement",
    "nbr_piece": 3,
    "surface": 80,
    "price": 150000,
    "city": "Alger",
    "description": "Beau T3 lumineux",
    "status": "disponible",
    "published": true,
    "images": [
      "storage/properties/fichier1.jpg",
      "storage/properties/fichier2.jpg"
    ],
    "created_at": "2025-11-13T12:00:00Z"
  }
}


---

### Liste filtrée des biens

GET `/api/v1/properties?city=Alger&type=Appartement&status=disponible&minPrice=100000&maxPrice=200000&q=lumineux&per_page=10&page=1`

Paramètres disponibles :  

- `city` → filtre par ville (exemple : "Alger"). L’API ne renverra que les biens situés dans cette ville.  
- `type` → filtre par type de bien (exemple : "Appartement", "Maison", "Villa", etc.).  
- `status` → filtre par statut du bien (exemple : "disponible", "vendu").  
- `minPrice` → prix minimum souhaité (exemple : 100000). L’API ne renverra que les biens dont le prix est supérieur ou égal à cette valeur.  
- `maxPrice` → prix maximum souhaité (exemple : 200000). L’API ne renverra que les biens dont le prix est inférieur ou égal à cette valeur.  
- `q` → recherche full-text sur le `title` et la `description` (exemple : "lumineux").  
- `per_page` → nombre de résultats par page (pagination), par défaut 15.  
- `page` → numéro de la page à récupérer (pagination), par défaut 1.

#### 🔍 Recherche full-text
Pour permettre la recherche sur le titre et la description des biens, un index FULLTEXT a été créé sur les colonnes title et description de la table properties.

ALTER TABLE properties
ADD FULLTEXT INDEX ft_title_description (title, description);


---

### Liste des biens supprimés (trashed) – admin uniquement

GET /api/v1/properties/trashed/list

Headers : 

Authorization: Bearer YOUR_ADMIN_TOKEN_HERE

Accept: application/json


Exemple de réponse :


{
  "success": true,
  "message": "Propriété supprimée récupérée avec succès.",
  "data": {
    "id": 5,
    "type": "Villa",
    "nbr_piece": 4,
    "surface": 120,
    "price": 350000,
    "city": "Alger",
    "description": "Villa spacieuse à Bab Ezzouar",
    "status": "disponible",
    "published": true,
    "deleted_at": "2025-11-12T15:30:00Z",
    "images": [
      "storage/properties/fichier1.jpg",
      "storage/properties/fichier2.jpg"
    ]
  }
} 



---

### Restaurer un bien supprimé – admin uniquement

POST /api/v1/properties/{id}/restore

Headers : 

Authorization: Bearer YOUR_ADMIN_TOKEN_HERE

Accept: application/json


Remplace {id} par l’ID du bien supprimé que tu veux restaurer.

Exemple de réponse :


{
  "success": true,
  "message": "Propriété restaurée avec succès.",
  "data": {
    "id": 5,
    "type": "Villa",
    "nbr_piece": 4,
    "surface": 120,
    "price": 350000,
    "city": "Alger",
    "description": "Villa spacieuse à Bab Ezzouar",
    "status": "disponible",
    "published": true,
    "deleted_at": null,
    "images": [
      "storage/properties/fichier1.jpg",
      "storage/properties/fichier2.jpg"
    ]
  }
}



---

### Créer un utilisateur (agent) - admin uniquement

POST `/api/v1/users`

Headers : 

Authorization: Bearer YOUR_ADMIN_TOKEN_HERE

Accept: application/json


{
  "name": "Nom Agent",
  "email": "agent@example.com",
  "password": "password",
  "role": "agent"
}

Exemple de réponse : 
```json
{
  "success": true,
  "message": "Agent créé avec succès",
  "data": {
    "id": 5,
    "name": "Nom Agent",
    "email": "agent@example.com",
    "role": "agent",
    "created_at": "2025-11-13T12:30:00Z",
    "updated_at": "2025-11-13T12:30:00Z"
  }
}


---

## 🔒 Rôles et accès

### RÔLES  

1. **ADMIN**  
Accès complet  
- Gérer tous les agents (créer, modifier, supprimer)  
- Lire tous les agents  
- Gérer tous les biens (créer, modifier, supprimer)  
- Lire tous les biens  
- Voir la corbeille  
- Restaurer les biens supprimés  
- Supprimer définitivement les biens  

2. **AGENT**  
Accès limité à ses biens  
- Lire tous les biens  
- Gérer uniquement ses propres biens (créer, modifier, supprimer)  
- Pas d'accès à la gestion des agents  

3. **GUEST**  
Consultation uniquement  
- Lire les biens  
- Aucune gestion  
- Aucun accès aux agents  

---

### ACCÈS

ACTION                        | ADMIN | AGENT | GUEST
-------------------------------|-------|-------|------
Créer agent                   |   ✓   |   ✗   |   ✗
Modifier agent                |   ✓   |   ✗   |   ✗
Supprimer agent               |   ✓   |   ✗   |   ✗
Lire agents                   |   ✓   |   ✗   |   ✗
Créer bien                    |   ✓   |  ✓*   |   ✗
Modifier bien                 |   ✓   |  ✓*   |   ✗
Supprimer bien                |   ✓   |  ✓*   |   ✗
Lire biens                    |   ✓   |   ✓   |   ✓
Voir corbeille                |   ✓   |   ✗   |   ✗
Restaurer bien                |   ✓   |   ✗   |   ✗
Supprimer définitivement bien |   ✓   |   ✗   |   ✗

* Uniquement ses propres biens

---

### HIÉRARCHIE

ADMIN  
  |  
AGENT  
  |  
GUEST

---

## 🏗 Architecture

L’application repose sur une architecture en couches suivant le schéma Contrôleur → Service → Repository, garantissant une séparation claire des responsabilités et une meilleure maintenabilité du code.

Les contrôleurs (AuthController, UserController, PropertyController) reçoivent les requêtes et délèguent la validation aux classes Request correspondantes (par exemple UpdatePropertyRequest, CreatePropertyRequest, LoginRequest). Une fois validées, les données sont transmises aux services (AuthService, UserService, PropertyService) qui contiennent la logique métier. Les repositories (EloquentUserRepository, EloquentPropertyRepository) gèrent la communication avec la base de données via Eloquent.  
Les DTOs (LoginUserDTO, RegisterUserDTO, CreateUserDTO, UpdateUserDTO, CreatePropertyDTO, UpdatePropertyDTO, FilterPropertiesDTO) assurent une transmission structurée et sécurisée des données entre les couches. Les modèles Eloquent (User, Property, Property_images) définissent la structure et les relations des entités.  
L’application utilise Sanctum pour l’authentification par token et PropertyPolicy pour gérer les règles d’autorisation liées aux actions sur les biens immobiliers.

Lors d’une création ou mise à jour d’un bien, le contrôleur valide la requête via CreatePropertyRequest ou UpdatePropertyRequest, puis transmet les données au service (PropertyService), qui applique la logique métier et encapsule les données dans un DTO approprié (CreatePropertyDTO ou UpdatePropertyDTO). Le service appelle ensuite le repository (EloquentPropertyRepository) pour effectuer la création ou la mise à jour dans la base.
Le modèle Eloquent Property utilise la méthode statique generateTitle() pour générer automatiquement le title si celui-ci est vide. Cette fonction construit le titre en combinant le type du bien, le nombre de pièces et la ville, garantissant ainsi des titres cohérents et lisibles pour tous les biens.
Le modèle utilise également Soft Deletes, permettant la suppression logique des biens. Les biens supprimés restent dans la base et peuvent être restaurés via l’API par un administrateur.
Enfin, la réponse est normalisée grâce à PropertyResource, et les erreurs éventuelles sont gérées de manière centralisée par le Handler, assurant des réponses JSON cohérentes et fiables.

---

## 📄 Documentation OpenAPI / Swagger

La documentation interactive est disponible à :  
http://localhost:8000/docs
