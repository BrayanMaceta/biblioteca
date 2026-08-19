FROM php:8.2-apache

# Instalar dependencias del sistema necesarias para PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev

# Instalar extensiones de PHP: MySQL y PostgreSQL
RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql

# Copiar el código al servidor
COPY . /var/www/html/

# Dar permisos
RUN chown -R www-data:www-data /var/www/html/
