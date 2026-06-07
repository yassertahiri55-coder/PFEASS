#!/bin/bash

# Script de déploiement automatique pour Laravel & React sur Alwaysdata
# À exécuter depuis la racine du projet (ex: SSH ou via un hook Git)

set -e

echo "🚀 Début du déploiement..."

# 0. Nettoyage des fichiers de cache locaux s'ils ont été téléversés par FTP/SFTP
echo "🧹 Nettoyage des caches résiduels..."
rm -f bootstrap/cache/config.php bootstrap/cache/routes.php bootstrap/cache/routes-v7.php bootstrap/cache/services.php bootstrap/cache/packages.php

# 1. Récupérer les dernières modifications du code si Git est utilisé
if [ -d ".git" ]; then
    echo "📥 Mise à jour du code depuis Git..."
    git pull origin main || echo "⚠️ Attention: Impossible d'effectuer git pull. Poursuite du déploiement..."
fi

# 2. Installation/Mise à jour des dépendances Composer PHP
echo "📦 Installation des dépendances PHP (Composer)..."
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Exécution des migrations de base de données
echo "🗄️ Exécution des migrations de base de données..."
php artisan migrate --force --no-interaction

# 4. Optimisation de Laravel (Mise en cache de la configuration et des routes)
echo "⚡ Optimisation et mise en cache de Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Création du lien symbolique de stockage si inexistant
echo "🔗 Liaison du dossier storage public..."
php artisan storage:link || echo "🔗 Le lien storage existe déjà."

# 6. Déploiement du Frontend React (pfeassu)
echo "💻 Préparation du Frontend React..."
if [ -d "pfeassu" ]; then
    cd pfeassu
    
    # Récupérer l'URL de l'API depuis le .env du parent ou utiliser une variable
    # Toujours utiliser l'URL de l'API Laravel de production
    API_URL=$(grep "APP_URL" ../.env | cut -d '=' -f2 | tr -d '"' | tr -d "'")
    if [ -z "$API_URL" ]; then
        API_URL="http://localhost:8000"
    fi
    PROD_API_URL="${API_URL}/api"
    
    echo "📡 API Backend détectée pour React : ${PROD_API_URL}"
    
    echo "📦 Installation des dépendances NodeJS (React)..."
    npm install --no-audit --no-fund
    
    echo "🛠️ Compilation du frontend React pour la production..."
    REACT_APP_API_BASE_URL="$PROD_API_URL" VITE_API_BASE_URL="$PROD_API_URL" npm run build
    
    cd ..
    echo "✅ Frontend React compilé dans : pfeassu/build/"
else
    echo "⚠️ Dossier 'pfeassu' introuvable. Ignoré."
fi

echo "🎉 Déploiement terminé avec succès !"
