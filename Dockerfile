# Usamos la imagen oficial de PHP con Apache y la extensión de MySQL
FROM php:8.2-apache

# Instalamos la extensión de MySQL para que PHP pueda conectarse a la base de datos
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiamos todo el código de tu repositorio a la carpeta donde Apache sirve las páginas
COPY . /var/www/html/

# Damos permisos (opcional, pero recomendado)
RUN chown -R www-data:www-data /var/www/html/
