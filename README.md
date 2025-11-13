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

- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=digitup_company
- DB_USERNAME=root
- DB_PASSWORD=
- APP_URL=http://localhost

---

## 🚀 Exemples de requêtes API

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
Authorization: Bearer <token>  
Accept: application/json

---

### Créer un bien immobilier (authentifié) avec images

POST `/api/v1/properties`

Headers :
Authorization: Bearer YOUR_TOKEN_HERE  # Remplace YOUR_TOKEN_HERE par le token reçu après login

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

---

### Liste filtrée des biens

GET `/api/v1/properties?city=Alger&type=Appartement&minPrice=100000&maxPrice=200000&q=lumineux`

Paramètres optionnels :  
- q → recherche full-text sur title et description  
- per_page → pagination (par défaut : 15)

---

### Créer un utilisateur (agent) - admin uniquement

POST `/api/v1/users`

{
  "name": "Nom Agent",
  "email": "agent@example.com",
  "password": "password",
  "role": "agent"
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

Lors d’une mise à jour de bien, le contrôleur valide la requête via UpdatePropertyRequest, puis transmet les données au service (PropertyService), qui applique la logique métier et encapsule les données dans un UpdatePropertyDTO. Le service appelle ensuite le repository (EloquentPropertyRepository) pour effectuer la mise à jour dans la base. Le modèle Eloquent de la propriété peut également générer automatiquement le title si nécessaire, basé sur le type, le nombre de pièces et la ville. Enfin, la réponse est normalisée grâce à PropertyResource, et les erreurs éventuelles sont gérées de manière centralisée par le Handler, garantissant des réponses cohérentes et fiables.

---

## 📄 Documentation OpenAPI / Swagger

La documentation interactive est disponible à :  
http://localhost:8000/docs
