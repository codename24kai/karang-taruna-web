# 1. Pake PHP 8.2 dengan Apache
FROM php:8.2-apache

# 2. Install Library yang dibutuhkan (termasuk Driver PostgreSQL)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

# 3. Install Ekstensi PHP untuk Laravel & Postgres
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# 4. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Set Folder Kerja
WORKDIR /var/www/html

# 6. Copy semua file project ke dalam server
COPY . .

# 7. Install Paket Laravel (Composer)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. Atur Izin Folder Storage (Biar bisa upload gambar/log)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Setting Apache biar baca folder 'public'
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 10. Aktifkan Mod Rewrite (Penting buat Route Laravel)
RUN a2enmod rewrite

# 11. Setting Port (Render pake variabel $PORT)
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-enabled/000-default.conf /etc/apache2/ports.conf
