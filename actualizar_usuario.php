<?php
require_once 'funciones.php';

$data = json_decode(file_get_contents('php://input'), true);

$sql = "UPDATE usuarios SET 
        nombre = ?, 
        apellido = ?, 
        email = ?, 
        telefono = ?, 
        direccion = ? 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", 
    $data['nombre'], 
    $data['apellido'], 
    $data['email'], 
    $data['telefono'], 
    $data['direccion'], 
    $data['id']
);

$resultado = $stmt->execute();

header('Content-Type: application/json');
echo json_encode(['exito' => $resultado]);

cerrarConexion($conn);
?>