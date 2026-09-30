FROM php:8.3-apache-bookworm

ENV APP_ENV=production \
    APP_DEBUG=false \
    COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
    && apt-get install -y --no-install-recommends libicu-dev libpq-dev libzip-dev unzip \
    && docker-php-ext-install -j"$(nproc)" intl opcache pdo_mysql pdo_pgsql \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --prefer-dist

COPY . .
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php-production.ini /usr/local/etc/php/conf.d/99-production.ini
COPY docker/start-container.sh /usr/local/bin/start-container

RUN composer dump-autoload --no-dev --optimize \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache public/uploads/content-assets \
    && chown -R www-data:www-data storage bootstrap/cache public/uploads \
    && chmod +x /usr/local/bin/start-container

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
  CMD php -r "exit(@file_get_contents('http://127.0.0.1/up') === false ? 1 : 0);"

ENTRYPOINT ["start-container"]
CMD ["apache2-foreground"]
