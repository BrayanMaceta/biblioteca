<?php
require_once 'conexion.php';

$stats = [];

$result = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'");
$stats['prestamosActivos'] = $result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'devuelto'");
$stats['prestamosDevueltos'] = $result->fetch_assoc()['total'];

$result = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'vencido'");
$stats['prestamosVencidos'] = $result->fetch_assoc()['total'];

header('Content-Type: application/json');
echo json_encode($stats);

cerrarConexion($conn);
?>