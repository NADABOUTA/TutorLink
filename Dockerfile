# Dockerfile pour déploiement en production (Render, Railway, Fly.io, ou VPS)
FROM php:8.3-fpm-alpine

# Installation des dépendances système & extensions PHP requises par Laravel
RUN apk update && apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    sqlite-dev \
    zip \
    unzip \
    nginx \
    nodejs \
    npm \
    mysql-client

RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet
COPY . .

# Installer les dépendances PHP et compiler le frontend Vite
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install && npm run build

# Configurer les permissions pour storage et bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Créer le fichier SQLite par défaut au cas où
RUN mkdir -p database && touch database/database.sqlite && chmod 777 database/database.sqlite

# Copier le script de démarrage
EXPOSE 8000

CMD sh -c "php artisan config:clear && php artisan migrate --force && php artisan db:seed --class=RoleSeeder --force && php artisan serve --host=0.0.0.0 --port=\${PORT:-8000}"

