# Gunakan image PHP resmi dengan Apache
FROM php:8.2-apache

# Install ekstensi sistem yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Aktifkan mod_rewrite Apache (Penting untuk routing Laravel)
RUN a2enmod rewrite

# Set Document Root ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy semua file project ke dalam container
COPY . /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Beri izin akses folder storage dan cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Port default Apache
EXPOSE 80

CMD ["apache2-foreground"]