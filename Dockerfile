FROM php:8.2-apache

# Actualizar repositorios e instalar el driver de PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql

# Copiar el código
COPY . /var/www/html/

# Dar permisos
RUN chown -R www-data:www-data /var/www/html/
