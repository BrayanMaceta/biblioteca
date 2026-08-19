<?php
require_once 'funciones.php';

$id = $_GET['id'] ?? 0;

$resultado = eliminarLibro($conn, $id);

header('Content-Type: application/json');
echo json_encode(['exito' => $resultado]);

cerrarConexion($conn);
?>