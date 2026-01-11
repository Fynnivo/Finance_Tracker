FROM php:8.2-cli

# 1. Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip libzip-dev unzip ca-certificates \
    && docker-php-ext-install gd pdo pdo_mysql zip

# 2. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Copy project
COPY . /var/www/html
WORKDIR /var/www/html

# 4. Install dependencies Laravel
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 5. Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 6. Jalankan PHP Built-in Server
EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]