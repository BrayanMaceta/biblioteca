# Usamos la imagen oficial de PHP con Apache y la extensión de MySQL
FROM php:8.2-apache

# Instalamos la extensión de MySQL para que PHP pueda conectarse a la base de datos
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiamos todo el código de tu repositorio a la carpeta donde Apache sirve las páginas
COPY . /var/www/html/

# Damos permisos (opcional, pero recomendado)
RUN chown -R www-data:www-data /var/www/html/

# --- ESTO ES LO NUEVO QUE DEBES AGREGAR ---
# Instalamos el cliente de PostgreSQL para poder ejecutar comandos SQL desde la terminal de Render
RUN apt-get update && apt-get install -y postgresql-client

# Copiamos tu archivo SQL a la carpeta de la app
COPY biblioteca.sql /var/www/html/biblioteca.sql

# NOTA: No se puede ejecutar el SQL aquí (en construcción) porque la base de datos de Render aún no existe.
# Tendremos que hacerlo manual en la consola "Shell" de Render.
