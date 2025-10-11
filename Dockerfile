# =============================================================================
# TEMPLATE DOCKERFILE - Symfony/PHP + Node.js pour Coolify
# =============================================================================

# ============ 1. VERSION PHP ============
FROM php:7.4-fpm

# ============ 2. INSTALLATION NODE.JS 16 ============
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
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
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# ============ 5. SYMFONY CLI ============
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# ============ 6. COPIE DU CODE ============
WORKDIR /var/www/html

COPY . .

# ============ 7. CRÉATION DES DOSSIERS AVEC PERMISSIONS ============
RUN mkdir -p var/cache var/log public/bundles public/build \
    && chmod -R 777 var/ public/build public/bundles

# ============ 8. INSTALLATION DÉPENDANCES PHP ============
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction \
    && composer dump-autoload --optimize

# ============ 9. INSTALLATION + BUILD ASSETS ============
RUN npm install && npm run build

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