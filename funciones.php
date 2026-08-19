<?php
// (El resto de tus funciones deben estar aquí arriba...)

// ---------------------------------------------------------
// FUNCIÓN PARA REGISTRAR UN PRÉSTAMO
// ---------------------------------------------------------
function registrarPrestamo($conn, $libro_id, $usuario_id) {
    // 1. Verificar que el libro existe y tiene disponibilidad
    $query_check = "SELECT disponible FROM libros WHERE id = $libro_id";
    $result_check = $conn->query($query_check);
    
    if (!$result_check || $result_check->num_rows == 0) {
        return "El libro no existe en la base de datos.";
    }
    
    $row = $result_check->fetch_assoc();
    if ($row['disponible'] <= 0) {
        return "No hay ejemplares disponibles de este libro.";
    }
    
    // 2. Insertar el préstamo en la tabla 'prestamos'
    $fecha_prestamo = date('Y-m-d');
    $fecha_devolucion_esperada = date('Y-m-d', strtotime('+15 days')); // 15 días de plazo
    
    $query = "INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, fecha_devolucion_esperada, estado) 
              VALUES ($libro_id, $usuario_id, '$fecha_prestamo', '$fecha_devolucion_esperada', 'activo')";
    
    if ($conn->query($query) === TRUE) {
        // 3. Actualizar la disponibilidad del libro (restar 1)
        $update_query = "UPDATE libros SET disponible = disponible - 1 WHERE id = $libro_id";
        $conn->query($update_query);
        
        return true; // Éxito
    } else {
        return "Error al guardar en la base de datos: " . $conn->error;
    }
}

// ---------------------------------------------------------
// (El resto de tus funciones deben estar aquí abajo...)
?>
