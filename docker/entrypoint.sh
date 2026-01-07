#!/bin/sh

# Sair imediatamente se um comando falhar
set -e

# Otimizar o Laravel para produção
echo "Caching config and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Rodar as migrações da base de dados (o --force é obrigatório em produção)
echo "Running migrations..."

php artisan migrate:fresh --force
#php artisan migrate --force

# Iniciar o processo principal (ex: php-fpm ou o que estiver no seu Dockerfile)
exec "$@"