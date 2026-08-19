<?php
require_once 'funciones.php';

$data = json_decode(file_get_contents('php://input'), true);

$resultado = agregarLibro(
    $conn,
    $data['titulo'],
    $data['autor'],
    $data['editorial'],
    $data['anio'],
    $data['isbn'],
    $data['categoria'],
    $data['cantidad']
);

header('Content-Type: application/json');
echo json_encode(['exito' => $resultado]);

cerrarConexion($conn);
?>