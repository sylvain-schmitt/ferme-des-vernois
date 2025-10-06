FROM php:7.4-fpm

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

# Installation de Composer 2.2 (compatible PHP 7.4)
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# Configuration Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1

WORKDIR /app

# Copie de tous les fichiers
COPY . .

# Configuration pour autoriser les plugins Composer
RUN composer config --no-plugins allow-plugins.symfony/flex true && \
    composer config --no-plugins allow-plugins.symfony/runtime true && \
    composer config --no-plugins allow-plugins.composer/package-versions-deprecated true || true

# Installation des dépendances
RUN composer install --no-dev --optimize-autoloader

# Permissions sur les dossiers var/ et public/
RUN mkdir -p var/cache var/log public/bundles \
    && chmod -R 777 var/ public/bundles || true

# Variables d'environnement
ENV APP_ENV=prod

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]