<?php
$host = "bphjwsfvbwciqlifjksf-mysql.services.clever-cloud.com";
$user = "utpfilnpk87cho5v";
$pass = "t1fTboji82NAjYFkyhl4";
$dbname = "bphjwsfvbwciqlifjksf";

$conexion = new mysqli($host, $user, $pass, $dbname);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
