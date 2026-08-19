<?php
require_once 'funciones.php';

$data = json_decode(file_get_contents('php://input'), true);
$prestamo_id = $data['prestamo_id'] ?? 0;

header('Content-Type: application/json');

if (!$prestamo_id) {
    echo json_encode(['exito' => false, 'mensaje' => 'ID de préstamo no válido']);
    exit;
}

// Iniciar transacción
$conn->begin_transaction();

try {
    // 1. Obtener el libro asociado al préstamo
    $sql = "SELECT libro_id FROM prestamos WHERE id = ? AND estado IN ('activo', 'vencido')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $prestamo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if (!$row) {
        throw new Exception('Préstamo no encontrado o ya devuelto');
    }
    
    $libro_id = $row['libro_id'];
    
    // 2. Actualizar el estado del préstamo a 'devuelto'
    $sql_update = "UPDATE prestamos SET estado = 'devuelto', fecha_devolucion_real = CURDATE() WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $prestamo_id);
    $stmt_update->execute();
    
    // 3. Devolver el libro al inventario (+1 disponible)
    $sql_libro = "UPDATE libros SET disponible = disponible + 1 WHERE id = ?";
    $stmt_libro = $conn->prepare($sql_libro);
    $stmt_libro->bind_param("i", $libro_id);
    $stmt_libro->execute();
    
    // Confirmar la transacción
    $conn->commit();
    
    echo json_encode(['exito' => true, 'mensaje' => 'Libro devuelto correctamente']);
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['exito' => false, 'mensaje' => $e->getMessage()]);
}
?>
