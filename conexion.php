<?php
// Obtener los datos de las variables de entorno de Render
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

// Conectar a la base de datos
$conexion = new mysqli($host, $user, $pass, $dbname);

// Verificar si hubo un error de conexión
if ($conexion->connect_error) {
    // Si falla, mostramos el error real para saber qué pasa
    die("Error de conexión: " . $conexion->connect_error);
}
?>
