# Guide d'Hébergement sur Alwaysdata (Laravel + React)

Ce guide décrit en détail les étapes pour héberger votre projet sur Alwaysdata. Nous avons configuré l'application pour utiliser **deux sites distincts** sous le même compte Alwaysdata afin de séparer proprement le backend (API Laravel & dashboards d'administration/expert) et le frontend (React pour les clients et agents).

---

## Étape 1 : Création de la Base de Données sur Alwaysdata

1. Connectez-vous à votre espace d'administration Alwaysdata.
2. Allez dans **Bases de données > MySQL**.
3. Cliquez sur **Ajouter une base de données**.
   - Donnez-lui un nom (ex: `pfeass` - Alwaysdata préfixera automatiquement avec votre nom d'utilisateur, ex: `tahiri_pfeass`).
4. Créez un **Utilisateur** MySQL (comme sur votre capture d'écran, cochez "tous les droits" pour la base `tahiri_pfeass*` et cliquez sur **Valider**) :
   - Notez le nom d'utilisateur généré (ex: `tahiri`) et définissez un mot de passe solide.

---

## Étape 2 : Configuration du Fichier `.env` sur le Serveur

Une fois vos fichiers téléchargés (via SSH, Git ou FTP) dans le répertoire `/home/tahiri/PFEASS/` de votre serveur Alwaysdata :

Créez le fichier `.env` à la racine de votre projet Laravel (en copiant le `.env.production`) et configurez-le ainsi :

```env
APP_NAME="PFEASS"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:UjfLs0j0b6Oh2sj40xUH2jjVw8hAjeyOvJPbnT84BYI= # Conservez ou générez une nouvelle clé via php artisan key:generate

# URL de votre API Backend (Laravel)
APP_URL=https://api.tahiri.alwaysdata.net

# URL de votre application React Frontend
FRONTEND_URL=https://tahiri.alwaysdata.net

# Configuration de la base de données de production sur Alwaysdata
DB_CONNECTION=mysql
DB_HOST=mysql-tahiri.alwaysdata.net
DB_PORT=3306
DB_DATABASE=tahiri_pfeass
DB_USERNAME=tahiri
DB_PASSWORD=votre_mot_de_passe_solide

# Pilotes et stockage
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public
```

---

## Étape 3 : Création des Deux Sites sur Alwaysdata

Dans le menu **Web > Sites** d'Alwaysdata, créez deux configurations de site :

### Site 1 : L'API Backend Laravel (dashboards Expert & Admin)
- **Nom** : Backend Laravel API
- **Adresses** : `api.tahiri.alwaysdata.net` (ou votre domaine personnalisé)
- **Type** : PHP
- **Version de PHP** : Choisissez `8.3` ou `8.4`
- **Chemin global** : `/home/tahiri/PFEASS/public` (Il est capital de faire pointer vers le dossier `/public` pour des raisons de sécurité !)

### Site 2 : Le Frontend React (Espace Client & Agent)
- **Nom** : Frontend React Client
- **Adresses** : `tahiri.alwaysdata.net` (ou votre domaine personnalisé principal)
- **Type** : Fichiers statiques
- **Chemin global** : `/home/tahiri/PFEASS/pfeassu/build`

---

## Étape 4 : Déploiement et Initialisation (Script de Déploiement)

Un script automatique `deploy.sh` a été fourni à la racine de votre projet. Pour l'exécuter en production :

1. Connectez-vous en SSH à votre compte Alwaysdata :
   ```bash
   ssh tahiri@ssh-tahiri.alwaysdata.net
   ```
2. Allez dans le répertoire de votre projet :
   ```bash
   cd PFEASS
   ```
3. Rendez le script exécutable et lancez-le :
   ```bash
   chmod +x deploy.sh
   ./deploy.sh
   ```

---

## Résumé des accès une fois déployé :
- **Application Client / Agent (React)** : Accès direct sur `https://tahiri.alwaysdata.net`
- **Dashboard Expert (Laravel Blade)** : Accès sur `https://api.tahiri.alwaysdata.net/expert/dashboard`
- **Dashboard Admin (Laravel Blade)** : Accès sur `https://api.tahiri.alwaysdata.net/admin/dashboard`
- **Point d'entrée de l'API** : `https://api.tahiri.alwaysdata.net/api/`
