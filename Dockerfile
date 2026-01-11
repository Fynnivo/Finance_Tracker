FROM php:8.2-apache

# 1. Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip libzip-dev unzip ca-certificates \
    && docker-php-ext-install gd pdo pdo_mysql zip

# 2. BERSIHKAN KONFIGURASI APACHE YANG MUNGKIN DISUNTIKKAN RAILWAY
# Menghapus semua file di mods-enabled agar kita bisa load ulang dari nol secara bersih
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
    && rm -f /etc/apache2/mods-enabled/mpm_worker.load

# 3. Aktifkan hanya modul yang kita butuhkan
RUN a2enmod mpm_prefork rewrite

# 4. Set Document Root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Copy file project & Install Composer
COPY . /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 6. Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]