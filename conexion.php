<?php
// Leer las variables de entorno que configuramos en Render
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');

// Crear la conexión con los datos de Clever Cloud
$conexion = new mysqli($host, $user, $pass, $dbname);

// Verificar si hubo error
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
