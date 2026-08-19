<?php
// =============================================
// FUNCIONES PARA LA BIBLIOTECA
// =============================================

require_once 'conexion.php';

// =============================================
// FUNCIONES PARA LIBROS
// =============================================

// Obtener todos los libros
function obtenerLibros($conn) {
    $sql = "SELECT * FROM libros ORDER BY titulo ASC";
    $result = $conn->query($sql);
    return $result;
}

// Buscar libros por término
function buscarLibros($conn, $termino) {
    $termino = $conn->real_escape_string($termino);
    $sql = "SELECT * FROM libros 
            WHERE titulo LIKE '%$termino%' 
            OR autor LIKE '%$termino%' 
            OR categoria LIKE '%$termino%' 
            OR isbn LIKE '%$termino%'
            ORDER BY titulo ASC";
    $result = $conn->query($sql);
    return $result;
}

// Agregar nuevo libro
function agregarLibro($conn, $titulo, $autor, $editorial, $anio, $isbn, $categoria, $cantidad) {
    $sql = "INSERT INTO libros (titulo, autor, editorial, anio_publicacion, isbn, categoria, cantidad, disponible) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssii", $titulo, $autor, $editorial, $anio, $isbn, $categoria, $cantidad, $cantidad);
    
    return $stmt->execute();
}

// Actualizar libro
function actualizarLibro($conn, $id, $titulo, $autor, $editorial, $anio, $isbn, $categoria, $cantidad) {
    // Primero obtener la cantidad actual para ajustar disponible
    $sql_get = "SELECT cantidad, disponible FROM libros WHERE id = ?";
    $stmt_get = $conn->prepare($sql_get);
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $result = $stmt_get->get_result();
    $row = $result->fetch_assoc();
    
    $diferencia = $cantidad - $row['cantidad'];
    $nuevo_disponible = $row['disponible'] + $diferencia;
    
    $sql = "UPDATE libros SET 
            titulo = ?, autor = ?, editorial = ?, anio_publicacion = ?, 
            isbn = ?, categoria = ?, cantidad = ?, disponible = ? 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiii", $titulo, $autor, $editorial, $anio, $isbn, $categoria, $cantidad, $nuevo_disponible, $id);
    
    return $stmt->execute();
}

// Eliminar libro
function eliminarLibro($conn, $id) {
    $sql = "DELETE FROM libros WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Obtener libro por ID
function obtenerLibroPorId($conn, $id) {
    $sql = "SELECT * FROM libros WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// =============================================
// FUNCIONES PARA USUARIOS
// =============================================

// Obtener todos los usuarios
function obtenerUsuarios($conn) {
    $sql = "SELECT * FROM usuarios ORDER BY apellido, nombre ASC";
    $result = $conn->query($sql);
    return $result;
}

// Agregar usuario
function agregarUsuario($conn, $nombre, $apellido, $email, $telefono, $direccion) {
    $sql = "INSERT INTO usuarios (nombre, apellido, email, telefono, direccion) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $apellido, $email, $telefono, $direccion);
    
    return $stmt->execute();
}

// =============================================
// FUNCIONES PARA PRÉSTAMOS
// =============================================

// Registrar préstamo
function registrarPrestamo($conn, $libro_id, $usuario_id) {
    // Verificar disponibilidad
    $sql_check = "SELECT disponible FROM libros WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $libro_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['disponible'] <= 0) {
        return false;
    }
    
    // Iniciar transacción
    $conn->begin_transaction();
    
    try {
        // Registrar préstamo
        $fecha = date('Y-m-d');
        $sql_prestamo = "INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, estado) 
                         VALUES (?, ?, ?, 'activo')";
        $stmt_prestamo = $conn->prepare($sql_prestamo);
        $stmt_prestamo->bind_param("iis", $libro_id, $usuario_id, $fecha);
        $stmt_prestamo->execute();
        
        // Actualizar disponibilidad
        $sql_update = "UPDATE libros SET disponible = disponible - 1 WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("i", $libro_id);
        $stmt_update->execute();
        
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

// Devolver libro
function devolverLibro($conn, $prestamo_id) {
    // Obtener libro_id del préstamo
    $sql_get = "SELECT libro_id FROM prestamos WHERE id = ? AND estado = 'activo'";
    $stmt_get = $conn->prepare($sql_get);
    $stmt_get->bind_param("i", $prestamo_id);
    $stmt_get->execute();
    $result = $stmt_get->get_result();
    $row = $result->fetch_assoc();
    
    if (!$row) {
        return false;
    }
    
    $libro_id = $row['libro_id'];
    
    // Iniciar transacción
    $conn->begin_transaction();
    
    try {
        // Actualizar estado del préstamo
        $fecha = date('Y-m-d');
        $sql_update = "UPDATE prestamos SET estado = 'devuelto', fecha_devolucion = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $fecha, $prestamo_id);
        $stmt_update->execute();
        
        // Aumentar disponibilidad
        $sql_libro = "UPDATE libros SET disponible = disponible + 1 WHERE id = ?";
        $stmt_libro = $conn->prepare($sql_libro);
        $stmt_libro->bind_param("i", $libro_id);
        $stmt_libro->execute();
        
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

// Obtener préstamos activos
function obtenerPrestamosActivos($conn) {
    $sql = "SELECT p.*, l.titulo as libro_titulo, u.nombre as usuario_nombre, u.apellido as usuario_apellido 
            FROM prestamos p 
            JOIN libros l ON p.libro_id = l.id 
            JOIN usuarios u ON p.usuario_id = u.id 
            WHERE p.estado = 'activo' 
            ORDER BY p.fecha_prestamo DESC";
    $result = $conn->query($sql);
    return $result;
}
?>