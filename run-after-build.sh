#!/bin/bash


echo "Limpando os caches..."

# Limpa pastas específicas em storage, mantendo os arquivos .gitignore
find storage/framework/cache/data -type f ! -name '.gitignore' -delete
find storage/framework/views -type f ! -name '.gitignore' -delete
find storage/framework/sessions -type f ! -name '.gitignore' -delete
find storage/framework/logs -type f ! -name '.gitignore' -delete

echo "Iniciando instalação das dependências do Composer..."

#primeiro esse
composer install --no-interaction --optimize-autoloader

#depois esse
# composer update --no-interaction --optimize-autoloader

echo "Gerando chave da aplicação..."
php artisan key:generate

echo "Cacheando configurações..."
php artisan config:cache

echo "Criando link simbólico para storage..."
php artisan storage:link

# Opcional: Descomente a linha abaixo para executar as migrações automaticamente
# echo "Executando migrações do banco de dados..."
# php artisan migrate --force

echo "Limpando caches de configuração e aplicação..."
php artisan config:clear
php artisan cache:clear

echo "Iniciando servidor de desenvolvimento Laravel..."
exec php artisan serve --host=0.0.0.0