# 📚 Documentation API Capital Immo Group

## Base URL
```
http://localhost:8000/api
```

## Authentification

L'API utilise **Laravel Sanctum** pour l'authentification. Incluez le token dans le header de vos requêtes :

```
Authorization: Bearer {votre_token}
```

---

## 🔐 Endpoints d'Authentification

### Login
```http
POST /login
```

**Body:**
```json
{
  "email": "admin@capitalimogroup.com",
  "password": "password"
}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "Administrateur",
    "email": "admin@capitalimogroup.com",
    "phone": "+242 04 411 3436"
  },
  "token": "1|laravel_sanctum_token..."
}
```

### Logout
```http
POST /logout
Authorization: Bearer {token}
```

### Utilisateur connecté
```http
GET /me
Authorization: Bearer {token}
```

---

## 🏠 Endpoints Biens Immobiliers

### Liste des biens (Public)
```http
GET /biens
```

**Query Parameters:**
| Paramètre | Type | Description |
|-----------|------|-------------|
| type | string | maison, villa, appartement, local, terrain |
| transaction | string | vente, location |
| zone | string | Centre-ville, Périphérie, etc. |
| prix_min | integer | En millions FCFA |
| prix_max | integer | En millions FCFA |
| surface_min | integer | En m² |
| search | string | Recherche textuelle |
| en_vedette | boolean | true pour les biens en vedette |
| sort_by | string | prix, surface, created_at |
| sort_order | string | asc, desc |
| per_page | integer | Nombre de résultats par page |

**Response:**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "titre": "Villa Luxueuse avec Piscine",
      "slug": "villa-luxueuse-avec-piscine",
      "prix": 450000000,
      "prix_formate": "450 M FCFA",
      "surface": 450,
      "type": "villa",
      "transaction": "vente",
      "zone": "Centre-ville",
      "quartier": "Ouenzé",
      "statut": "disponible",
      "en_vedette": true,
      "reference": "CIG-V-001",
      "images": [...],
      "main_image": "https://..."
    }
  ],
  "total": 25,
  "per_page": 12
}
```

### Détail d'un bien (Public)
```http
GET /biens/{id}
GET /biens/{slug}
```

### Biens similaires (Public)
```http
GET /biens/{id}/similaires
```

### Créer un bien (Admin)
```http
POST /biens
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Body:**
```json
{
  "titre": "Nouvelle Villa",
  "description": "Description détaillée...",
  "prix": 350000000,
  "surface": 300,
  "pieces": 6,
  "chambres": 4,
  "salle_de_bain": 2,
  "etage": 1,
  "type": "villa",
  "transaction": "vente",
  "zone": "Centre-ville",
  "quartier": "Ouenzé",
  "caracteristiques": ["Piscine", "Jardin", "Garage"],
  "images[]": [fichier1, fichier2, ...]
}
```

### Modifier un bien (Admin)
```http
PUT /biens/{id}
Authorization: Bearer {token}
```

### Supprimer un bien (Admin)
```http
DELETE /biens/{id}
Authorization: Bearer {token}
```

### Mettre en vedette (Admin)
```http
PATCH /biens/{id}/vedette
Authorization: Bearer {token}
```

### Changer le statut (Admin)
```http
PATCH /biens/{id}/statut
Authorization: Bearer {token}

{
  "statut": "vendu"
}
```

### Statistiques des biens (Admin)
```http
GET /biens/stats
Authorization: Bearer {token}
```

**Response:**
```json
{
  "total": 50,
  "disponibles": 35,
  "vendus": 10,
  "reserves": 5,
  "en_vedette": 4,
  "par_type": {
    "villa": 15,
    "appartement": 20,
    "maison": 10,
    "local": 3,
    "terrain": 2
  },
  "vues_total": 12500
}
```

---

## 🛎 Endpoints Services

### Liste des services (Public)
```http
GET /services
```

**Query Parameters:**
| Paramètre | Type | Description |
|-----------|------|-------------|
| active_only | boolean | Filtrer les services actifs |

### Détail d'un service (Public)
```http
GET /services/{id}
GET /services/{slug}
```

### CRUD Services (Admin)
```http
POST   /services
PUT    /services/{id}
DELETE /services/{id}
PATCH  /services/{id}/active
POST   /services/reorder
```

---

## 💬 Endpoints Témoignages

### Liste des témoignages (Public)
```http
GET /temoignages
```

**Query Parameters:**
| Paramètre | Type | Description |
|-----------|------|-------------|
| active_only | boolean | Filtrer les témoignages actifs |

### CRUD Témoignages (Admin)
```http
GET    /temoignages/{id}
POST   /temoignages
PUT    /temoignages/{id}
DELETE /temoignages/{id}
PATCH  /temoignages/{id}/active
POST   /temoignages/reorder
```

---

## 👥 Endpoints Équipe

### Liste des membres (Public)
```http
GET /equipe
```

**Query Parameters:**
| Paramètre | Type | Description |
|-----------|------|-------------|
| active_only | boolean | Filtrer les membres actifs |

### CRUD Équipe (Admin)
```http
GET    /equipe/{id}
POST   /equipe
PUT    /equipe/{id}
DELETE /equipe/{id}
PATCH  /equipe/{id}/active
POST   /equipe/reorder
```

---

## 📧 Endpoints Contact

### Envoyer un message (Public)
```http
POST /contacts
```

**Body:**
```json
{
  "nom": "Jean Dupont",
  "telephone": "+242 06 123 4567",
  "email": "jean@example.com",
  "objet": "achat",
  "message": "Je suis intéressé par...",
  "bien_id": 1,
  "reference_bien": "CIG-V-001"
}
```

**Objets possibles:**
- `achat` - Achat d'un bien
- `location` - Location
- `vente` - Vente de mon bien
- `gestion` - Gestion locative
- `estimation` - Estimation gratuite
- `autre` - Autre demande

### Liste des messages (Admin)
```http
GET /contacts
Authorization: Bearer {token}
```

**Query Parameters:**
| Paramètre | Type | Description |
|-----------|------|-------------|
| is_read | boolean | Filtrer par statut de lecture |
| objet | string | Filtrer par objet |

### Statistiques des contacts (Admin)
```http
GET /contacts/stats
Authorization: Bearer {token}
```

### Actions sur les messages (Admin)
```http
PATCH /contacts/{id}/read
PATCH /contacts/{id}/unread
PUT   /contacts/{id}
DELETE /contacts/{id}
POST  /contacts/bulk-delete
POST  /contacts/bulk-read
```

---

## ⚙️ Endpoints Configuration

### Informations entreprise (Public)
```http
GET /entreprise
```

**Response:**
```json
{
  "nom": "Capital Immo Group",
  "slogan": "Plus qu'un bien immobilier...",
  "adresse": "Rue Monseigneur Biéchy 2015, Brazzaville",
  "telephone": "+242 04 411 3436",
  "whatsapp": "+242 04 411 3436",
  "email": "contact@capitalimogroup.com",
  "facebook": "@capitalimogroup01",
  "facebook_url": "https://facebook.com/capitalimogroup01",
  "horaires": {
    "lundi": "08:00 - 17:00",
    "mardi": "08:00 - 17:00",
    ...
  },
  "coordonnees": {
    "lat": -4.2634,
    "lng": 15.2429
  }
}
```

### Modifier les informations (Admin)
```http
PUT /entreprise
Authorization: Bearer {token}
```

### Gestion des configurations (Admin)
```http
GET    /configurations
GET    /configurations/{key}
POST   /configurations
PUT    /configurations/{key}
DELETE /configurations/{key}
POST   /configurations/bulk
POST   /configurations/clear-cache
```

---

## 📊 Endpoints Dashboard (Admin)

### Statistiques globales
```http
GET /dashboard/stats
Authorization: Bearer {token}
```

**Response:**
```json
{
  "biens": {
    "total": 50,
    "disponibles": 35,
    "vendus": 10,
    "reserves": 5,
    "en_vedette": 4,
    "vues_total": 12500
  },
  "contacts": {
    "total": 150,
    "non_lus": 12,
    "ce_mois": 25
  },
  "temoignages": {
    "total": 20,
    "actifs": 15
  },
  "equipe": {
    "total": 6,
    "actifs": 5
  },
  "services": {
    "total": 4,
    "actifs": 4
  }
}
```

### Activité récente
```http
GET /dashboard/activity
Authorization: Bearer {token}
```

### Données des graphiques
```http
GET /dashboard/charts
Authorization: Bearer {token}
```

---

## 📋 Codes d'erreur

| Code | Description |
|------|-------------|
| 200 | Succès |
| 201 | Créé avec succès |
| 400 | Requête invalide |
| 401 | Non authentifié |
| 403 | Non autorisé |
| 404 | Ressource non trouvée |
| 422 | Erreur de validation |
| 500 | Erreur serveur |

---

## 🔒 Sécurité

- Toutes les routes admin nécessitent un token valide
- Les tokens expirent après 7 jours
- Les mots de passe sont hashés avec bcrypt
- Les uploads sont limités à 5Mo par image
- Formats d'image acceptés : jpeg, png, jpg, webp
