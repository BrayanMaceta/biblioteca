<?php
// Leer las variables de entorno
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

try {
    // Cadena de conexión forzando SSL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    
    // Crear la conexión
    $conexion = new PDO($dsn, $user, $password);
    
    // Configurar errores
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Mostrar el error
    die("Error de conexión: " . $e->getMessage());
}
?>
