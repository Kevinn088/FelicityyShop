# Gunakan PHP 8.2 + Apache
FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    unzip zip git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Salin seluruh project Laravel
COPY . .

# Ganti document root ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update Apache config agar pakai folder public dan aktifkan AllowOverride
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf && \
    echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# Aktifkan mod_rewrite
RUN a2enmod rewrite

# Set permission Laravel (wajib)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install dependency dan cache Laravel
RUN composer install --no-dev --optimize-autoloader && \
    php artisan config:cache && \
    php artisan route:cache

# Expose port default Apache
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
