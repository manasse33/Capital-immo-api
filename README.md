# Capital Immo Group API

API backend Laravel pour le site web immobilier Capital Immo Group.

## 🚀 Installation

### Prérequis
- PHP 8.2+
- Composer
- MySQL 8.0+ ou MariaDB 10.6+
- Extension PHP : mbstring, openssl, pdo_mysql, tokenizer, xml, ctype, json, bcmath, curl

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone <repository-url>
cd capitalimmo-api
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurer la base de données**
Modifier le fichier `.env` avec vos informations de connexion :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=capitalimmo
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

5. **Créer la base de données**
```bash
mysql -u root -p -e "CREATE DATABASE capitalimmo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

6. **Exécuter les migrations et les seeders**
```bash
php artisan migrate --seed
```

7. **Créer le lien symbolique pour le stockage**
```bash
php artisan storage:link
```

8. **Démarrer le serveur**
```bash
php artisan serve
```

L'API sera accessible à `http://localhost:8000`

## 📚 Documentation API

### Authentification

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "admin@capitalimogroup.com",
  "password": "password"
}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

### Biens Immobiliers

#### Liste des biens
```http
GET /api/biens
GET /api/biens?type=villa&transaction=vente&zone=Centre-ville
GET /api/biens?en_vedette=true
GET /api/biens?search=appartement
```

#### Détail d'un bien
```http
GET /api/biens/{id}
GET /api/biens/{slug}
```

#### Créer un bien (Admin)
```http
POST /api/biens
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
  "titre": "Villa de luxe",
  "description": "Magnifique villa...",
  "prix": 450000000,
  "surface": 450,
  "type": "villa",
  "transaction": "vente",
  "zone": "Centre-ville",
  "quartier": "Ouenzé",
  "images[]": [fichier1, fichier2]
}
```

#### Biens similaires
```http
GET /api/biens/{id}/similaires
```

### Services

#### Liste des services
```http
GET /api/services
GET /api/services?active_only=true
```

### Témoignages

#### Liste des témoignages
```http
GET /api/temoignages
GET /api/temoignages?active_only=true
```

### Équipe

#### Liste des membres
```http
GET /api/equipe
GET /api/equipe?active_only=true
```

### Contact

#### Envoyer un message
```http
POST /api/contacts
Content-Type: application/json

{
  "nom": "Jean Dupont",
  "telephone": "+242 06 123 4567",
  "email": "jean@example.com",
  "objet": "achat",
  "message": "Je suis intéressé par...",
  "bien_id": 1
}
```

### Informations Entreprise

#### Récupérer les infos
```http
GET /api/entreprise
```

### Dashboard (Admin)

#### Statistiques
```http
GET /api/dashboard/stats
Authorization: Bearer {token}
```

#### Activité récente
```http
GET /api/dashboard/activity
Authorization: Bearer {token}
```

#### Données des graphiques
```http
GET /api/dashboard/charts
Authorization: Bearer {token}
```

## 🗄 Structure de la base de données

### Tables principales

- **users** : Utilisateurs administrateurs
- **biens** : Biens immobiliers
- **bien_images** : Images des biens
- **services** : Services proposés
- **temoignages** : Témoignages clients
- **membres_equipe** : Membres de l'équipe
- **contacts** : Messages de contact
- **configurations** : Paramètres du site

## 🔐 Sécurité

- Authentification via Laravel Sanctum
- Protection CORS configurée
- Validation des données sur toutes les routes
- Protection contre les injections SQL via Eloquent

## 📁 Structure du projet

```
capitalimmo-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/    # Contrôleurs API
│   │   ├── Middleware/         # Middleware personnalisé
│   │   └── Requests/           # Form Requests
│   ├── Models/                 # Modèles Eloquent
│   └── Services/               # Services métier
├── config/                     # Fichiers de configuration
├── database/
│   ├── migrations/             # Migrations
│   └── seeders/                # Seeders
├── routes/
│   ├── api.php                 # Routes API
│   └── web.php                 # Routes Web
└── storage/
    └── app/public/uploads/     # Fichiers uploadés
```

## 🛠 Commandes utiles

```bash
# Créer un nouvel admin
php artisan tinker
>>> App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')])

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Exécuter les migrations
php artisan migrate

# Exécuter les seeders
php artisan db:seed

# Créer une migration
php artisan make:migration create_table_name

# Créer un contrôleur
php artisan make:controller Api/ControllerName

# Créer un modèle
php artisan make:model ModelName
```

## 📄 License

Ce projet est propriétaire et confidentiel.
