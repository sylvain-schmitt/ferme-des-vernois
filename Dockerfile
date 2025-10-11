# =============================================================================
# TEMPLATE DOCKERFILE - Symfony/PHP + Node.js pour Coolify
# =============================================================================
# 
# VARIABLES À MODIFIER :
# - Ligne 14 : Version PHP (7.2, 7.4, 8.1, 8.3, etc.)
# - Ligne 18 : Version Node.js (setup_18.x, setup_20.x, etc.)
# - Ligne 28 : Version Composer si PHP < 7.4
# - Ligne 53 : Port d'exposition (8001, 8002, 8003, etc.)
# - Ligne 55 : Même port dans la commande
#
# =============================================================================

# ============ 1. VERSION PHP ============
FROM php:7.4-fpm

# ============ 2. INSTALLATION NODE.JS ============
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# ============ 3. DÉPENDANCES SYSTÈME + EXTENSIONS PHP ============
RUN apt-get update && apt-get install -y \
    git unzip curl zip \
    libpq-dev libicu-dev libzip-dev libonig-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql intl zip opcache gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ============ 4. COMPOSER ============
# Pour PHP 7.4 : version 2.2 maximum
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# ============ 5. SYMFONY CLI (optionnel) ============
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# ============ 6. COPIE DU CODE ============
WORKDIR /var/www/html

COPY . .

# ============ 7. INSTALLATION DÉPENDANCES PHP ============
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction \
    && composer dump-autoload --optimize

# ============ 8. INSTALLATION + BUILD ASSETS (Node/Webpack Encore) ============
RUN npm install && npm run build

# ============ 9. DOSSIERS (uniquement ceux NON montés en volume) ============
RUN mkdir -p var/cache var/log public/bundles

# ============ 10. CONFIGURATION OPCACHE ============
RUN echo "opcache.enable=1\n\
opcache.memory_consumption=128\n\
opcache.max_accelerated_files=10000\n\
opcache.validate_timestamps=0" > /usr/local/etc/php/conf.d/opcache.ini

# ============ 11. ENVIRONNEMENT ============
ENV APP_ENV=prod

# ============ 12. PORT ET DÉMARRAGE ============
EXPOSE 8002
CMD ["php", "-S", "0.0.0.0:8002", "-t", "public"]