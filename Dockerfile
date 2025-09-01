# Stage 1: Install Composer (PHP) dependencies
FROM php:8.3-cli as vendor

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip zip \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts
COPY . .
RUN composer dump-autoload --optimize && php artisan package:discover --ansi || true


# Stage 2: Build Node.js assets
FROM node:20-alpine as assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build


# Stage 3: Final production image
FROM dunglas/frankenphp:1-php8.3

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        libzip-dev libonig-dev libicu-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

WORKDIR /app

# Copy source + vendor + assets
COPY . /app
COPY --from=vendor /app/vendor /app/vendor
COPY --from=assets /app/public/build /app/public/build

# Permissions pakai www-data
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8000

CMD ["frankenphp", "php-server", "artisan", "octane:start"]


