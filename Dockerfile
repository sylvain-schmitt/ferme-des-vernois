# =============================================================================
# TEMPLATE DOCKERFILE SIMPLIFIÉ - Symfony/PHP pour Coolify
# =============================================================================
# 
# VARIABLES À MODIFIER :
# - Ligne 9  : Version PHP (7.2, 7.4, 8.1, 8.3, etc.)
# - Ligne 13 : Version Composer si PHP < 7.4 (utiliser ligne 16 à la place)
# - Ligne 40 : Port d'exposition (8001, 8002, 8003, etc.)
# - Ligne 42 : Même port dans la commande
#
# =============================================================================

# ============ 1. VERSION PHP ============
FROM php:7.4-fpm
# Autres versions : php:7.2-fpm, php:7.4-fpm, php:8.1-fpm, php:8.2-fpm

# ============ 2. DÉPENDANCES SYSTÈME + EXTENSIONS PHP ============
RUN apt-get update && apt-get install -y \
    git unzip curl zip \
    libpq-dev libicu-dev libzip-dev libonig-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql intl zip opcache gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# ============ 3. COMPOSER ============
# Pour PHP >= 7.4
#RUN curl -sS https://getcomposer.org/installer | php && \
    #mv composer.phar /usr/local/bin/composer

# Pour PHP < 7.4 : décommentez cette ligne et commentez les 2 au-dessus
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# ============ 4. SYMFONY CLI (optionnel) ============
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# ============ 5. COPIE + INSTALLATION ============
WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction \
    && composer dump-autoload --optimize

# ============ 6. DOSSIERS (uniquement ceux NON montés en volume) ============
RUN mkdir -p var/cache var/log public/bundles

# ============ 7. CONFIGURATION OPCACHE ============
RUN echo "opcache.enable=1\n\
opcache.memory_consumption=128\n\
opcache.max_accelerated_files=10000\n\
opcache.validate_timestamps=0" > /usr/local/etc/php/conf.d/opcache.ini

# ============ 8. ENVIRONNEMENT ============
ENV APP_ENV=prod

# ============ 9. PORT ET DÉMARRAGE ============
EXPOSE 8002
CMD ["php", "-S", "0.0.0.0:8002", "-t", "public"]