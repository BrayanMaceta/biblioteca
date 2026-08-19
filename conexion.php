<?php
$database_url = getenv('DATABASE_URL');
try {
    $conexion = new PDO($database_url);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
