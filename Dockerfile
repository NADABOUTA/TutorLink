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

# Permissions pour storage, bootstrap et le script d'entrée
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod +x /var/www/html/docker-entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/bin/sh", "/var/www/html/docker-entrypoint.sh"]


