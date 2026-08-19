<?php
require_once 'funciones.php';

$data = json_decode(file_get_contents('php://input'), true);

$resultado = registrarPrestamo(
    $conn,
    $data['libro_id'],
    $data['usuario_id']
);

header('Content-Type: application/json');
echo json_encode(['exito' => $resultado]);

cerrarConexion($conn);
?>