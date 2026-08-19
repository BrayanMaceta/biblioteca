# Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalamos las extensiones necesarias: MySQL (por si acaso) y PostgreSQL (el importante)
RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql

# Copiamos todo el código de tu repositorio a la carpeta donde Apache sirve las páginas
COPY . /var/www/html/

# Damos permisos
RUN chown -R www-data:www-data /var/www/html/
