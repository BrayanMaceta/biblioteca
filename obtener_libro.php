<?php
require_once 'funciones.php';

$id = $_GET['id'] ?? 0;

$libro = obtenerLibroPorId($conn, $id);

header('Content-Type: application/json');
if ($libro) {
    echo json_encode($libro);
} else {
    echo json_encode(['error' => 'Libro no encontrado']);
}

cerrarConexion($conn);
?>