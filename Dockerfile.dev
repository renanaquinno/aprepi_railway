FROM php:8.2-fpm

# Instala dependências do PHP e extensões (como já está no seu Dockerfile)
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip curl git libzip-dev \
    libpq-dev libsqlite3-dev \
    libfreetype6-dev libjpeg62-turbo-dev \
    libwebp-dev libxpm-dev libvpx-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Instala Node.js e npm (exemplo para Debian/Ubuntu)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --optimize-autoloader --no-dev

EXPOSE 9000

CMD ["php-fpm"]
