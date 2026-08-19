<?php
require_once 'funciones.php';

$result = obtenerUsuarios($conn);

if ($result && $result->num_rows > 0) {
    while($usuario = $result->fetch_assoc()) {
        echo '<option value="' . $usuario['id'] . '">' . 
             htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) . 
             ' - ' . htmlspecialchars($usuario['email']) . 
             '</option>';
    }
}

cerrarConexion($conn);
?>