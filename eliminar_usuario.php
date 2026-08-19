<?php
require_once 'funciones.php';

$id = $_GET['id'] ?? 0;

$sql = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$resultado = $stmt->execute();

header('Content-Type: application/json');
echo json_encode(['exito' => $resultado]);

cerrarConexion($conn);
?>