<?php
// Leer la URL completa de la base de datos desde las variables de entorno de Render
$database_url = getenv('DATABASE_URL');

// Conexión nativa a PostgreSQL (NO usa PDO, por eso no necesita el driver)
$conexion = pg_connect($database_url);

// Verificar si la conexión falló
if (!$conexion) {
    die("Error de conexión: " . pg_last_error());
}
?>
