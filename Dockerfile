FROM php:8.2-apache

# Instalar la extensión mysqli para conectar con MySQL de Clever Cloud
RUN docker-php-ext-install mysqli

# Copiar todos los archivos al servidor
COPY . /var/www/html/

# Dar permisos
RUN chown -R www-data:www-data /var/www/html/
