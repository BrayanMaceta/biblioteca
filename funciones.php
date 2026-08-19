<?php
// =============================================
// ARCHIVO DE FUNCIONES PARA LA BIBLIOTECA
// =============================================

// Incluir la conexión a la base de datos (se espera que la variable $conn esté disponible globalmente)
require_once 'conexion.php';

// ---------------------------------------------------------
// FUNCIONES PARA LIBROS
// ---------------------------------------------------------

// Obtener todos los libros
function obtenerLibros($conn) {
    $query = "SELECT * FROM libros ORDER BY id DESC";
    $result = $conn->query($query);
    return $result;
}

// Obtener un libro por su ID
function obtenerLibroPorId($conn, $id) {
    $sql = "SELECT * FROM libros WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $libro = $result->fetch_assoc();
    return $libro;
}

// Agregar un nuevo libro
function agregarLibro($conn, $titulo, $autor, $editorial, $anio, $isbn, $categoria, $cantidad) {
    $query = "INSERT INTO libros (titulo, autor, editorial, anio_publicacion, isbn, categoria, cantidad, disponible) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssisssi", $titulo, $autor, $editorial, $anio, $isbn, $categoria, $cantidad, $cantidad);
    return $stmt->execute();
}

// Actualizar un libro existente
function actualizarLibro($conn, $id, $titulo, $autor, $editorial, $anio, $isbn, $categoria, $cantidad) {
    $sql = "UPDATE libros SET titulo=?, autor=?, editorial=?, anio_publicacion=?, isbn=?, categoria=?, cantidad=?, disponible=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisssii", $titulo, $autor, $editorial, $anio, $isbn, $categoria, $cantidad, $cantidad, $id);
    return $stmt->execute();
}

// Eliminar un libro por ID
function eliminarLibro($conn, $id) {
    $sql = "DELETE FROM libros WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// ---------------------------------------------------------
// FUNCIONES PARA USUARIOS
// ---------------------------------------------------------

// Obtener todos los usuarios
function obtenerUsuarios($conn) {
    $query = "SELECT * FROM usuarios ORDER BY id DESC";
    $result = $conn->query($query);
    return $result;
}

// Obtener un usuario por su ID
function obtenerUsuarioPorId($conn, $id) {
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Agregar un nuevo usuario
function agregarUsuario($conn, $nombre, $apellido, $email, $telefono, $direccion) {
    $query = "INSERT INTO usuarios (nombre, apellido, email, telefono, direccion) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $nombre, $apellido, $email, $telefono, $direccion);
    return $stmt->execute();
}

// Actualizar un usuario existente
function actualizarUsuario($conn, $id, $nombre, $apellido, $email, $telefono, $direccion) {
    $sql = "UPDATE usuarios SET nombre=?, apellido=?, email=?, telefono=?, direccion=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $apellido, $email, $telefono, $direccion, $id);
    return $stmt->execute();
}

// Eliminar un usuario por ID
function eliminarUsuario($conn, $id) {
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// ---------------------------------------------------------
// FUNCIONES PARA PRÉSTAMOS
// ---------------------------------------------------------

// Registrar un nuevo préstamo
function registrarPrestamo($conn, $libro_id, $usuario_id) {
    // 1. Verificar disponibilidad
    $query_check = "SELECT disponible FROM libros WHERE id = $libro_id";
    $result_check = $conn->query($query_check);
    
    if (!$result_check || $result_check->num_rows == 0) {
        return "El libro no existe.";
    }
    
    $row = $result_check->fetch_assoc();
    if ($row['disponible'] <= 0) {
        return "No hay ejemplares disponibles.";
    }
    
    // 2. Insertar préstamo
    $fecha_prestamo = date('Y-m-d');
    $fecha_devolucion = date('Y-m-d', strtotime('+15 days'));
    
    $query = "INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, fecha_devolucion_esperada, estado) 
              VALUES ($libro_id, $usuario_id, '$fecha_prestamo', '$fecha_devolucion', 'activo')";
    
    if ($conn->query($query) === TRUE) {
        // 3. Actualizar disponibilidad del libro
        $conn->query("UPDATE libros SET disponible = disponible - 1 WHERE id = $libro_id");
        return true;
    } else {
        return "Error al guardar: " . $conn->error;
    }
}

// ---------------------------------------------------------
// FUNCIÓN PARA CERRAR LA CONEXIÓN (USADA EN ALGUNOS ARCHIVOS)
// ---------------------------------------------------------
function cerrarConexion($conn) {
    if ($conn) {
        $conn->close();
    }
}
?>
