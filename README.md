# 📚 Prestige Library — Système de Gestion de Bibliothèque

Projet web développé en **PHP + PDO + MySQL** avec un design **Tailwind CSS**.

---

## 🗂️ Structure du Projet

```
└── 📁 biblio
    ├── 📁 admin
    │   ├── auteurs.php       → Gestion des auteurs
    │   ├── dashboard.php     → Tableau de bord admin
    │   ├── editeurs.php      → Gestion des éditeurs
    │   ├── etudiants.php     → Gestion des étudiants
    │   ├── livres.php        → Gestion des livres
    │   ├── prets.php         → Gestion des prêts
    │   └── themes.php        → Gestion des thèmes
    ├── 📁 config
    │   └── db.php            → Connexion PDO à la base de données
    ├── 📁 etudiant
    │   ├── dashboard.php     → Tableau de bord étudiant
    │   ├── emprunter.php     → Logique d'emprunt d'un livre
    │   ├── inscription.php   → Formulaire d'inscription
    │   ├── livres.php        → Catalogue + recherche des livres
    │   ├── mes_prets.php     → Historique des emprunts
    │   └── profil.php        → Modifier son profil
    ├── get_photo.php         → Affichage des photos de livres (BLOB)
    ├── index.php             → Page d'accueil + connexion
    ├── logout.php            → Déconnexion
    └── README.md
```

---

## ⚙️ Installation

### 1. Prérequis
- XAMPP / WAMP
- PHP >= 7.4
- MySQL

### 2. Base de données

Créer la base et importer le schéma dans **phpMyAdmin** :

```sql
CREATE DATABASE biblio_db CHARACTER SET utf8 COLLATE utf8_general_ci;
```

Puis créer les tables :

| Table | Description |
|---|---|
| `etudiant` | Membres inscrits |
| `admin` | Administrateurs |
| `livre` | Catalogue des livres |
| `auteur` | Auteurs |
| `editeur` | Éditeurs |
| `theme` | Thèmes/Catégories |
| `pret` | Emprunts (NumEtd + NumLivre + DatePret) |

Compte admin par défaut :
```
Login    : admin
Password : password
```

> ⚠️ Changer le mot de passe après la première connexion.

### 3. Configuration

Modifier `config/db.php` selon votre environnement :

```php
$host = 'localhost';
$db   = 'biblio_db';
$user = 'root';
$mpt  = '';
```

### 4. Lancement

Placer le dossier `biblio` dans `htdocs` (XAMPP) ou `www` (WAMP) puis ouvrir :

```
http://localhost/biblio/index.php
```

---

## 👥 Fonctionnalités

### 🎓 Étudiant
| Fonctionnalité | Fichier |
|---|---|
| Inscription | `etudiant/inscription.php` |
| Connexion | `index.php` |
| Tableau de bord + prêts | `etudiant/dashboard.php` |
| Catalogue + recherche | `etudiant/livres.php` |
| Emprunter un livre | `etudiant/emprunter.php` |
| Historique des emprunts | `etudiant/mes_prets.php` |
| Modifier profil | `etudiant/profil.php` |

### 🛡️ Admin
| Fonctionnalité | Fichier |
|---|---|
| Tableau de bord | `admin/dashboard.php` |
| CRUD Livres + photo | `admin/livres.php` |
| CRUD Auteurs | `admin/auteurs.php` |
| CRUD Éditeurs | `admin/editeurs.php` |
| CRUD Thèmes | `admin/themes.php` |
| Gestion étudiants | `admin/etudiants.php` |
| Gestion prêts | `admin/prets.php` |

---

## 🔐 Sécurité

- Mots de passe hashés avec `password_hash()` / vérifiés avec `password_verify()`
- Requêtes préparées PDO (protection contre les injections SQL)
- Validation côté serveur sur tous les formulaires
- Navigation par `id` dans l'URL (pas de session)

---

## 🛠️ Technologies

| Technologie | Usage |
|---|---|
| PHP 8+ | Backend |
| PDO | Accès base de données |
| MySQL | Base de données |
| Tailwind CSS | Design responsive |
| Google Fonts | Typographie (Poppins + Playfair Display) |

---

## 👨‍💻 Auteur

Projet réalisé dans le cadre d'un cours de développement web.
