# Attineos API — Micro API REST Symfony

API REST construite avec Symfony 7, permettant la gestion d'utilisateurs avec authentification JWT.

## Stack technique

- **Framework** : Symfony 7
- **ORM** : Doctrine
- **Base de données** : MariaDB 10.11
- **Authentification** : LexikJWTAuthenticationBundle
- **Validation** : Symfony Validator
- **Sérialisation** : Symfony Serializer
- **Conteneurisation** : Docker + Docker Compose

## Prérequis

- Docker
- Docker Compose

## Installation

```bash
# Cloner le projet
git clone <url-du-repo>
cd <nom-du-repo>

# Créer le fichier d'environnement local
cp .env .env.local 
# Modifier .env.local avec vos valeurs 

# Lancer les conteneurs
docker compose up -d

# Installer les dépendances
docker compose exec php composer install

# Générer les clés JWT
docker compose exec php php bin/console lexik:jwt:generate-keypair

# Lancer les migrations
docker compose exec php php bin/console doctrine:migrations:migrate
```


## Accès

- API : http://localhost:8080      
- PhpMyAdmin : http://localhost:8081      

## Variables d'environnement

```env
DATABASE_URL="mysql://user:password@mysql:3306/attineos_db?serverVersion=8.0.0&charset=utf8mb4"
(Par défaut en dev : DATABASE_URL="mysql://attineos_admin:symfony@mysql:3306/attineos_db?serverVersion=8.0.0&charset=utf8mb4")
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=votre_passphrase
```

## Endpoints

### Publics — aucun token requis

- GET `/api/users` — Liste tous les users
- GET `/api/users/{id}` — Récupère un user
- POST `/api/users` — Crée un user
- POST `/api/auth/login` — Connexion
### Protégés — token JWT requis (`Authorization: Bearer <token>`)

- PUT `/api/users` — Met à jour l'utilisateur connecté
- DELETE `/api/users` — Supprime l'utilisateur connecté

## Exemples de requêtes

### Créer un utilisateur
```json
POST /api/users
{
    "firstName": "Julien",
    "lastName": "Mallet",
    "email": "julien.mallet@gmail.com",
    "password": "Motdepasse1"
}
```

### Se connecter
```json
POST /api/auth/login
{
    "email": "julien.mallet@gmail.com",
    "password": "Motdepasse1"
}
```

Réponse :
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
}
```

### Mettre à jour son profil
```json
PUT /api/users
Authorization: Bearer <token>
{
    "firstName": "Julien",
    "lastName": "Mallet",
    "email": "julien.mallet@gmail.com",
    "password": "NouveauMdp1"
}
```

### Supprimer son profil
```json
DELETE /api/users
Authorization: Bearer <token>
```
