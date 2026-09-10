#!/bin/sh

echo "=================================================="
echo "🚀 Starting TutorLink Production Entrypoint..."
echo "=================================================="

# Assurer que le dossier database et storage existent avec les bons droits
mkdir -p /var/www/html/database /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/storage/framework/cache
touch /var/www/html/database/database.sqlite
chmod -R 777 /var/www/html/storage /var/www/html/database

# Vider les caches pour prendre en compte les variables Railway
php artisan config:clear || true
php artisan cache:clear || true

# Exécuter les migrations en toute sécurité (sans crasher si déjà faites)
echo "📦 Running database migrations..."
php artisan migrate --force || echo "Migration notice: continuing..."

# Insérer les comptes de test s'ils n'existent pas
echo "🌱 Seeding default test users..."
php artisan db:seed --class=RoleSeeder --force || echo "Seeding notice: continuing..."

# Lancer le serveur HTTP sur le port dynamique Railway
PORT_TO_USE="${PORT:-8000}"
echo "🌐 Launching Laravel server on 0.0.0.0:${PORT_TO_USE}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT_TO_USE}"
