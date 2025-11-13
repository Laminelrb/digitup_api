# API Gestion Propriétés

API Laravel pour la gestion des biens immobiliers (CRUD, filtrage, upload d’images).

---

## 📦 Installation

1. Cloner le projet :

```bash
git clone <url-du-projet>
cd <nom-du-projet>
```

2. Installer les dépendances :

```bash
composer install
```

3. Copier le fichier `.env` et configurer la base de données :

```bash
cp .env.example .env
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

- DB_CONNECTION
- DB_HOST
- DB_PORT
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD
- APP_URL

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

- **admin** : gérer tous les utilisateurs et propriétés  
- **agent** : gérer uniquement ses propriétés

---

## 🏗 Architecture

- **Contrôleur → Service → Repository** pour une séparation claire des responsabilités  
- Utilisation des **DTOs** (Create/Update) pour manipuler les données de manière sécurisée  
- Modèles Eloquent avec relations :  
  - `Property` → images, owner  
  - `User` → gestion des rôles  
- **Policies** pour l’autorisation des actions  
- Middleware **Sanctum** pour l’authentification

---

## 📄 Documentation OpenAPI / Swagger

La documentation interactive est disponible à :  
[http://localhost:8000/docs](http://localhost:8000/docs)
