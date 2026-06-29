FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip git \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:clear
RUN php artisan view:clear
RUN php artisan route:clear

CMD php artisan serve --host=0.0.0.0 --port=$PORT