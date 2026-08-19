<?php
// Incluir la conexión a la base de datos
require_once 'conexion.php';

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

// 5. Libros disponibles (con stock > 0)
$query_disponibles = "SELECT COUNT(*) as total FROM libros WHERE disponible > 0";
$result_disponibles = $conn->query($query_disponibles);
$total_disponibles = $result_disponibles->fetch_assoc()['total'];

// --- ESTADÍSTICAS DE PRÉSTAMOS POR USUARIO ---

$query_top_usuarios = "
    SELECT u.nombre, u.apellido, COUNT(p.id) as total_prestamos
    FROM usuarios u
    LEFT JOIN prestamos p ON u.id = p.usuario_id
    GROUP BY u.id
    ORDER BY total_prestamos DESC
    LIMIT 5
";
$result_top_usuarios = $conn->query($query_top_usuarios);
$top_usuarios = [];
if ($result_top_usuarios) {
    while ($row = $result_top_usuarios->fetch_assoc()) {
        $top_usuarios[] = $row;
    }
}

// --- ESTADÍSTICAS DE LIBROS MÁS PRESTADOS ---

$query_top_libros = "
    SELECT l.titulo, COUNT(p.id) as total_prestamos
    FROM libros l
    LEFT JOIN prestamos p ON l.id = p.libro_id
    GROUP BY l.id
    ORDER BY total_prestamos DESC
    LIMIT 5
";
$result_top_libros = $conn->query($query_top_libros);
$top_libros = [];
if ($result_top_libros) {
    while ($row = $result_top_libros->fetch_assoc()) {
        $top_libros[] = $row;
    }
}
?>

<!-- ============================================= -->
<!-- VISTA HTML DE LAS ESTADÍSTICAS               -->
<!-- ============================================= -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - Biblioteca Digital</title>
    <link rel="stylesheet" href="style.css">
    <!-- Iconos FontAwesome (para los íconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-chart-bar"></i> Estadísticas de la Biblioteca</h1>
            <a href="index.php" class="btn-back"><i class="fas fa-arrow-left"></i> Volver al inicio</a>
        </header>

        <div class="stats-grid">
            <!-- Tarjeta: Total Libros -->
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-book"></i></div>
                <div class="stat-number"><?php echo $total_libros; ?></div>
                <div class="stat-label">Total Libros</div>
            </div>

            <!-- Tarjeta: Total Usuarios -->
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-number"><?php echo $total_usuarios; ?></div>
                <div class="stat-label">Usuarios Registrados</div>
            </div>

            <!-- Tarjeta: Préstamos Activos -->
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <div class="stat-number"><?php echo $total_prestamos_activos; ?></div>
                <div class="stat-label">Préstamos Activos</div>
            </div>

            <!-- Tarjeta: Préstamos Vencidos -->
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-number"><?php echo $total_prestamos_vencidos; ?></div>
                <div class="stat-label">Préstamos Vencidos</div>
            </div>

            <!-- Tarjeta: Libros Disponibles -->
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-number"><?php echo $total_disponibles; ?></div>
                <div class="stat-label">Libros Disponibles</div>
            </div>
        </div>

        <div class="stats-details">
            <div class="detail-section">
                <h2>Top 5 Usuarios con más préstamos</h2>
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Total Préstamos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($top_usuarios)): ?>
                            <?php foreach ($top_usuarios as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></td>
                                    <td><?php echo $usuario['total_prestamos']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2">No hay datos de préstamos disponibles.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="detail-section">
                <h2>Top 5 Libros más prestados</h2>
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Libro</th>
                            <th>Total Préstamos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($top_libros)): ?>
                            <?php foreach ($top_libros as $libro): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                                    <td><?php echo $libro['total_prestamos']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2">No hay datos de préstamos disponibles.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
