# API Gestion Propriétés

API Laravel pour la gestion des biens immobiliers (CRUD, filtrage, upload d’images).

---

## 📦 Installation

1. Cloner le projet :

```bash
git clone https://github.com/Laminelrb/digitup_api.git
cd immobiliers-api
```

2. Installer les dépendances :

```bash
composer install
```

3. Copier le fichier `.env` et configurer la base de données :

### Linux / macOS
```bash
cp .env.example .env
```

### Windows
```bash
copy .env.example .env
```

4. Générer la clé d’application :

```bash
php artisan key:generate
```

5. Lancer les migrations :

```bash
php artisan migrate
```

6. Lancer le serveur :

```bash
php artisan serve
```

---

## 🔑 Variables d'environnement

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

```json
{
  "email": "agent@example.com",
  "password": "password"
}
```

### Créer un bien immobilier (authentifié)

POST `/api/v1/properties`

```json
{
  "type": "Appartement",
  "nbr_piece": 3,
  "surface": 80,
  "price": 150000,
  "city": "Alger",
  "description": "Beau T3 lumineux",
  "status": "disponible",
  "published": true
}
```

### Liste filtrée des biens

GET `/api/v1/properties?city=Alger&type=Appartement&minPrice=100000&maxPrice=200000`

### Créer un utilisateur (agent) - admin uniquement

POST `/api/v1/users`

```json
{
  "name": "Nom Agent",
  "email": "agent@example.com",
  "password": "password",
  "role": "agent"
}
```

---

## 🔒 Rôles et accès
  # RÔLES 
  
      1. ADMIN
      Accès complet

      Gérer tous les agents (créer, modifier, supprimer)
      Lire tous les agents
      Gérer tous les biens (créer, modifier, supprimer)
      Lire tous les biens

      2. AGENT
      Accès limité à ses biens

      Lire tous les biens
      Gérer uniquement ses propres biens (créer, modifier, supprimer)
      Pas d'accès à la gestion des agents

      3. GUEST
      Consultation uniquement

      Lire les biens
      Aucune gestion
      Aucun accès aux agents


  # ACCÈS 
        ACTION                    | ADMIN | AGENT | GUEST
        -------------------------------------------------
        Créer agent              |   ✓   |   ✗   |   ✗
        Modifier agent           |   ✓   |   ✗   |   ✗
        Supprimer agent          |   ✓   |   ✗   |   ✗
        Lire agents              |   ✓   |   ✗   |   ✗
        -------------------------------------------------
        Créer bien               |   ✓   |  ✓*   |   ✗
        Modifier bien            |   ✓   |  ✓*   |   ✗
        Supprimer bien           |   ✓   |  ✓*   |   ✗
        Lire biens               |   ✓   |   ✓   |   ✓

        * Uniquement ses propres biens

  # HIÉRARCHIE
    ADMIN
      |
    AGENT
      |
    GUEST

---

## 🏗 Architecture

L’application repose sur une architecture en couches suivant le schéma Contrôleur → Service → Repository, garantissant une séparation claire des responsabilités et une meilleure maintenabilité du code.

Les contrôleurs (AuthController, UserController, PropertyController) gèrent et valident les requêtes entrantes, tandis que les services (AuthService, UserService, PropertyService) contiennent la logique métier. Les repositories (EloquentUserRepository, EloquentPropertyRepository) assurent la communication avec la base de données via Eloquent.
Les DTOs (LoginUserDTO, RegisterUserDTO, CreateUserDTO, UpdateUserDTO, CreatePropertyDTO, UpdatePropertyDTO, FilterPropertiesDTO) permettent une transmission de données structurée et sécurisée entre les couches. Les modèles Eloquent (User, Property, Property_images) définissent la structure et les relations des entités.
L’application utilise Sanctum pour l’authentification par token et la PropertyPolicy pour définir les règles d’autorisation sur les actions liées aux biens immobiliers.

Lors d’une mise à jour de bien, le contrôleur valide la requête via UpdatePropertyRequest, puis transmet les données au service (PropertyService), qui applique la logique métier et encapsule les données dans un UpdatePropertyDTO. Ce service appelle ensuite le repository (EloquentPropertyRepository) pour effectuer la mise à jour dans la base. Enfin, la réponse est normalisée grâce à PropertyResource, et les erreurs éventuelles sont gérées de manière centralisée par le Handler, garantissant des réponses cohérentes et fiables.

---

## 📄 Documentation OpenAPI / Swagger

La documentation interactive est disponible à :  
[http://localhost:8000/docs](http://localhost:8000/docs)
