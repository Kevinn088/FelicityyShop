# Gunakan image dasar PHP
FROM php:8.2-apache

# Install ekstensi Laravel yang dibutuhkan
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    git \
    npm \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin file Laravel ke folder apache
COPY . /var/www/html

# Ubah owner agar Apache bisa akses
RUN chown -R www-data:www-data /var/www/html

# Aktifkan mod_rewrite untuk Laravel
RUN a2enmod rewrite

# Konfigurasi Apache untuk Laravel
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
</Directory>' >> /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Jalankan Composer
RUN composer install --no-dev --optimize-autoloader

# Jalankan artisan commands
RUN php artisan config:cache && php artisan route:cache

# Port untuk Railway (default 8080)
EXPOSE 8080

# Jalankan Apache di foreground
CMD ["apache2-foreground"]
