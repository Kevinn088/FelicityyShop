FROM php:8.2-apache

# Install PHP ekstensi yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev unzip zip git curl \
    libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin semua file Laravel ke container
COPY . /var/www/html

# Ganti document root Apache ke folder public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update VirtualHost Apache agar pakai /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Aktifkan mod_rewrite untuk Laravel
RUN a2enmod rewrite

# Tambahkan permission agar Apache bisa baca semua file
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Jalankan Composer dan cache config Laravel
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:cache && php artisan route:cache

EXPOSE 8080
CMD ["apache2-foreground"]
