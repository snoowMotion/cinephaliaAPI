#!/bin/bash

# Création automatique du dossier d'upload si absent
mkdir -p /var/www/html/public/upload/affiche
chown -R www-data:www-data /var/www/html/public/upload
chmod -R 775 /var/www/html/public/upload

# Lancer PHP-FPM (important !)
exec docker-php-entrypoint php-fpm