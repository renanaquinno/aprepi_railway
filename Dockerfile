# Estágio 1: PHP com extensões e dependências
FROM php:8.2-fpm

# Define variáveis de ambiente úteis
ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    libpq-dev \
    libsqlite3-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    libvpx-dev \
    libicu-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala e configura extensões do PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl

# Instala Node.js 18.x e npm (para compilar o Vite/Tailwind)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Instala o Composer (versão mais recente)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www

# 1. Copia apenas os arquivos de dependências primeiro (otimização de cache)
COPY composer.json composer.lock package.json package-lock.json ./

# 2. Instala dependências do PHP e NPM
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
RUN npm install

# 3. Copia o restante dos arquivos do projeto
COPY . .

# 4. Finaliza a instalação do Composer (otimiza autoloader) e compila assets do Vite
RUN composer dump-autoload --optimize \
    && npm run build

# Ajusta permissões para as pastas que o Laravel precisa escrever
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Configura o script de entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expõe a porta padrão do PHP-FPM
#EXPOSE 9000

# O Entrypoint executa comandos de setup (migrations, cache) antes do CMD
ENTRYPOINT ["entrypoint.sh"]
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8080}

# Comando que inicia o PHP-FPM
#CMD ["php-fpm"]