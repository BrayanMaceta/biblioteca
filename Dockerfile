# Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# PASO CRUCIAL: Instalamos las librerías de PostgreSQL antes de instalar la extensión
RUN apt-get update && apt-get install -y libpq-dev

# Instalamos las extensiones necesarias: MySQL (por si acaso) y PostgreSQL
RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql

# Copiamos todo el código de tu repositorio a la carpeta donde Apache sirve las páginas
COPY . /var/www/html/

# Damos permisos
RUN chown -R www-data:www-data /var/www/html/
