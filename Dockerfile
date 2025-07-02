# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Install extension penting
RUN apt-get update && apt-get install -y \
    git zip unzip libzip-dev libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy file project Laravel ke container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Install dependensi Laravel
RUN composer install --optimize-autoloader --no-dev

# Set permission
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Enable Apache Rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80
