<?php
// =============================================
// CONEXIÓN A LA BASE DE DATOS (LOCAL - XAMPP)
// =============================================

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configurar caracteres UTF-8
$conn->set_charset("utf8");

// Función para cerrar conexión
function cerrarConexion($conn) {
    if ($conn) {
        $conn->close();
    }
}
?>