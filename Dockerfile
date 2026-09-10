# Dockerfile pour déploiement en production (Render, Railway, Fly.io, ou VPS)
FROM php:8.3-fpm-alpine

# Installation des dépendances système & extensions PHP requises par Laravel
RUN apk update && apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    nodejs \
    npm \
    mysql-client

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

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

# Copier le script de démarrage
EXPOSE 8000

CMD php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=8000
