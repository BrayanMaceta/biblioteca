<?php
require_once 'conexion.php';

$stats = [];

// Total libros
$result = $conn->query("SELECT COUNT(*) as total FROM libros");
$stats['totalLibros'] = $result->fetch_assoc()['total'] ?? 0;

// Total usuarios
$result = $conn->query("SELECT COUNT(*) as total FROM usuarios");
$stats['totalUsuarios'] = $result->fetch_assoc()['total'] ?? 0;

// Préstamos activos
$result = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'");
$stats['prestamosActivos'] = $result->fetch_assoc()['total'] ?? 0;

// Préstamos vencidos
$result = $conn->query("SELECT COUNT(*) as total FROM prestamos WHERE estado = 'vencido'");
$stats['prestamosVencidos'] = $result->fetch_assoc()['total'] ?? 0;

header('Content-Type: application/json');
echo json_encode($stats);

cerrarConexion($conn);
?>