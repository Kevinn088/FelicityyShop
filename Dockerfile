# Gunakan image PHP resmi dengan Apache
FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    unzip zip git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Salin semua file ke dalam container
COPY . .

# Ganti document root Apache ke public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update Apache config agar pakai folder public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Aktifkan mod_rewrite (wajib untuk Laravel routing)
RUN a2enmod rewrite

# Beri permission agar storage bisa ditulis
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install dependency Laravel
RUN composer install --no-dev --optimize-autoloader \
    && php artisan config:cache \
    && php artisan route:cache

# Expose port 80 untuk Railway
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
