FROM php:8.2-fpm

# Install system dependencies and PHP extensions commonly used by Laravel
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev libzip-dev zip curl libicu-dev \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip \
 && pecl install redis || true \
 && docker-php-ext-enable redis || true \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install PHP dependencies (allow failure in environments without network during image build)
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader || true

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
