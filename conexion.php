<?php
// ==============================================
// CONEXIÓN A LA BASE DE DATOS (RENDER - PostgreSQL)
// ==============================================

// Leer las variables de entorno que configuraste en Render
$host = getenv('DB_HOST');
$puerto = getenv('DB_PORT');
$nombre_bd = getenv('DB_NAME');
$usuario = getenv('DB_USER');
$contrasena = getenv('DB_PASSWORD');

// Crear la conexión usando PDO (la forma moderna que funciona con PostgreSQL)
try {
    // Cadena de conexión para PostgreSQL
    $dsn = "pgsql:host=$host;port=$puerto;dbname=$nombre_bd;";
    
    // Crear el objeto de conexión
    $conexion = new PDO($dsn, $usuario, $contrasena);
    
    // Configurar para que muestre errores si falla algo
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Configurar caracteres UTF-8 (opcional, pero buena práctica)
    $conexion->exec("SET NAMES 'utf8'");
    
} catch (PDOException $e) {
    // Si falla la conexión, muere mostrando el error
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// Función para cerrar la conexión (funciona con PDO)
function cerrarConexion($conexion) {
    $conexion = null; // En PDO, se cierra asignándole null
}
?>
