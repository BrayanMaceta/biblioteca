FROM php:8.2-apache

# Instalar las herramientas del sistema para PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev

# Instalar primero el controlador de PostgreSQL (LO MÁS IMPORTANTE)
RUN docker-php-ext-install pdo_pgsql

# Instalar el resto de extensiones por si acaso
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar los archivos de tu página
COPY . /var/www/html/

# Dar permisos
RUN chown -R www-data:www-data /var/www/html/
