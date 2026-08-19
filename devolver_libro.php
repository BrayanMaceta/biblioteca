<?php
require_once 'funciones.php';

$data = json_decode(file_get_contents('php://input'), true);
$prestamo_id = $data['prestamo_id'] ?? 0;

if (!$prestamo_id) {
    header('Content-Type: application/json');
    echo json_encode(['exito' => false]);
    exit;
}

$conn->begin_transaction();

try {
    $sql = "SELECT libro_id FROM prestamos WHERE id = ? AND estado IN ('activo', 'vencido')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $prestamo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if (!$row) {
        throw new Exception('Préstamo no encontrado');
    }
    
    $libro_id = $row['libro_id'];
    
    $sql = "UPDATE prestamos SET estado = 'devuelto', fecha_devolucion_real = CURDATE() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $prestamo_id);
    $stmt->execute();
    
    $sql = "UPDATE libros SET disponible = disponible + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $libro_id);
    $stmt->execute();
    
    $conn->commit();
    
    header('Content-Type: application/json');
    echo json_encode(['exito' => true]);
    
} catch (Exception $e) {
    $conn->rollback();
    header('Content-Type: application/json');
    echo json_encode(['exito' => false]);
}

cerrarConexion($conn);
?>