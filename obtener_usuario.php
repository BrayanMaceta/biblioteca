<?php
require_once 'funciones.php';

$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

header('Content-Type: application/json');
if ($usuario) {
    echo json_encode($usuario);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}

cerrarConexion($conn);
?>