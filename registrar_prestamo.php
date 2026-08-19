<?php
require_once 'funciones.php';

// Leer los datos enviados desde JavaScript
$data = json_decode(file_get_contents('php://input'), true);

$libro_id = $data['libro_id'] ?? null;
$usuario_id = $data['usuario_id'] ?? null;

// Validar datos
if (!$libro_id || !$usuario_id) {
    header('Content-Type: application/json');
    echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos']);
    exit;
}

// Llamar a la función
$resultado = registrarPrestamo($conn, $libro_id, $usuario_id);

header('Content-Type: application/json');

if ($resultado === true) {
    echo json_encode(['exito' => true, 'mensaje' => 'Préstamo registrado correctamente']);
} else {
    echo json_encode(['exito' => false, 'mensaje' => $resultado]); // Devuelve el error real
}

cerrarConexion($conn);
?>
