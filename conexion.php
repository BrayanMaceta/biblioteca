<?php
// Leer las variables de entorno que configuramos en Render
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

try {
    // Crear la cadena de conexión para PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    
    // Conectar usando PDO
    $conexion = new PDO($dsn, $user, $password);
    
    // Configurar para que muestre errores si falla algo
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Si falla la conexión, mostrar el error (esto saldrá en la pantalla de tu web)
    die("Error de conexión: " . $e->getMessage());
}
?>
