FROM php:8.1-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copie des fichiers de dépendances d'abord (pour le cache Docker)
COPY composer.json composer.lock ./

# Installation des dépendances PHP
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copie du reste des fichiers
COPY . .

# Finalisation de l'installation Composer
RUN composer dump-autoload --optimize --classmap-authoritative

# Configuration Symfony pour la production
ENV APP_ENV=prod
RUN php bin/console cache:clear --env=prod --no-debug || true
RUN php bin/console cache:warmup --env=prod --no-debug || true

# Si vous avez des assets à compiler
# RUN php bin/console assets:install --env=prod --no-debug || true

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]