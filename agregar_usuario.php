<?php
require_once 'funciones.php';

$data = json_decode(file_get_contents('php://input'), true);

$resultado = agregarUsuario(
    $conn,
    $data['nombre'],
    $data['apellido'],
    $data['email'],
    $data['telefono'],
    $data['direccion']
);

header('Content-Type: application/json');
echo json_encode(['exito' => $resultado]);

cerrarConexion($conn);
?>