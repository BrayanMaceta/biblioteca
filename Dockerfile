# Usamos una imagen de PHP que YA incluye el driver de PostgreSQL
FROM php:8.2-pgsql-apache

# Copiamos todo el código de tu repositorio a la carpeta del servidor
COPY . /var/www/html/

# Damos permisos
RUN chown -R www-data:www-data /var/www/html/
