<?php
// Incluir la conexión a la base de datos
require_once 'conexion.php';

// Configurar el tipo de contenido como JSON
header('Content-Type: application/json');

// --- OBTENER ESTADÍSTICAS GENERALES ---

// 1. Total de libros
$query_libros = "SELECT COUNT(*) as total FROM libros";
$result_libros = $conn->query($query_libros);
$total_libros = $result_libros->fetch_assoc()['total'];

// 2. Total de usuarios
$query_usuarios = "SELECT COUNT(*) as total FROM usuarios";
$result_usuarios = $conn->query($query_usuarios);
$total_usuarios = $result_usuarios->fetch_assoc()['total'];

// 3. Total de préstamos activos
$query_prestamos_activos = "SELECT COUNT(*) as total FROM prestamos WHERE estado = 'activo'";
$result_prestamos_activos = $conn->query($query_prestamos_activos);
$total_prestamos_activos = $result_prestamos_activos->fetch_assoc()['total'];

// 4. Total de préstamos vencidos
$query_prestamos_vencidos = "SELECT COUNT(*) as total FROM prestamos WHERE estado = 'vencido'";
$result_prestamos_vencidos = $conn->query($query_prestamos_vencidos);
$total_prestamos_vencidos = $result_prestamos_vencidos->fetch_assoc()['total'];

// Devolver todo como JSON
echo json_encode([
    'total_libros' => $total_libros,
    'total_usuarios' => $total_usuarios,
    'prestamos_activos' => $total_prestamos_activos,
    'prestamos_vencidos' => $total_prestamos_vencidos
]);
?>
