FROM php:8.2-cli

# Install extension
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip libzip-dev unzip ca-certificates \
    && docker-php-ext-install gd pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html
WORKDIR /var/www/html

RUN composer install --no-interaction --optimize-autoloader --no-dev

# Penting: Beri izin folder agar Laravel tidak crash saat nulis log
RUN chmod -R 775 storage bootstrap/cache && chown -R www-data:www-data /var/www/html

# Pakai port 8080 agar lebih aman di Railway
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]