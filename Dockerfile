FROM php:8.2-apache

# Install extension yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip libzip-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# --- BAGIAN KRUSIAL UNTUK FIX ERROR MPM ---
# Matikan modul event dan paksa pakai prefork agar tidak bentrok
RUN a2dismod mpm_event || true && a2enmod mpm_prefork

# Aktifkan mod_rewrite untuk routing Laravel
RUN a2enmod rewrite

# Set Document Root ke folder public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy project
COPY . /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Jalankan Apache langsung
CMD ["apache2-foreground"]