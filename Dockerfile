FROM php:8.1-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_pgsql zip opcache intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configuration Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1

WORKDIR /app

# Copie de tous les fichiers
COPY . .

# Mise à jour de Symfony Flex AVANT l'installation complète
RUN composer require symfony/flex:"^2.0" --no-scripts --ignore-platform-reqs || true

# Installation des dépendances
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Permissions sur les dossiers var/ et public/
RUN mkdir -p var/cache var/log public/bundles \
    && chmod -R 777 var/ public/bundles || true

# Variables d'environnement
ENV APP_ENV=prod

EXPOSE 8000

# Commande de démarrage
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]