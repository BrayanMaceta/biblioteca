<?php
require_once 'funciones.php';

$sql = "SELECT p.*, 
        l.titulo as libro_titulo, 
        l.autor as libro_autor,
        u.nombre as usuario_nombre, 
        u.apellido as usuario_apellido,
        u.email as usuario_email,
        u.telefono as usuario_telefono
        FROM prestamos p 
        JOIN libros l ON p.libro_id = l.id 
        JOIN usuarios u ON p.usuario_id = u.id 
        ORDER BY p.fecha_prestamo DESC";

$result = $conn->query($sql);
?>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Libro</th>
                <th>Autor</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Fecha Préstamo</th>
                <th>Días</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($prestamo = $result->fetch_assoc()): 
                    $fecha_prestamo = new DateTime($prestamo['fecha_prestamo']);
                    $fecha_actual = new DateTime();
                    $diferencia = $fecha_prestamo->diff($fecha_actual);
                    $dias_prestado = $diferencia->days;
                    
                    if ($dias_prestado > 15 && $prestamo['estado'] == 'activo') {
                        $conn->query("UPDATE prestamos SET estado = 'vencido' WHERE id = " . $prestamo['id']);
                        $prestamo['estado'] = 'vencido';
                    }
                ?>
                    <tr>
                        <td><?= $prestamo['id'] ?></td>
                        <td><strong><?= htmlspecialchars($prestamo['libro_titulo']) ?></strong></td>
                        <td><?= htmlspecialchars($prestamo['libro_autor']) ?></td>
                        <td><?= htmlspecialchars($prestamo['usuario_nombre'] . ' ' . $prestamo['usuario_apellido']) ?></td>
                        <td><?= htmlspecialchars($prestamo['usuario_email']) ?></td>
                        <td><?= htmlspecialchars($prestamo['usuario_telefono'] ?: 'No registrado') ?></td>
                        <td><?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?></td>
                        <td>
                            <?php if ($prestamo['estado'] == 'activo'): ?>
                                <span style="font-weight: bold; <?= $dias_prestado > 15 ? 'color: #fc8181;' : 'color: #48bb78;' ?>">
                                    <?= $dias_prestado ?> días
                                    <?php if ($dias_prestado > 15): ?> ⚠️<?php endif; ?>
                                </span>
                            <?php elseif ($prestamo['estado'] == 'vencido'): ?>
                                <span style="color: #fc8181; font-weight: bold;">VENCIDO</span>
                            <?php else: ?>
                                <span style="color: #a0aec0;">Completado</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($prestamo['estado'] == 'activo'): ?>
                                <span class="estado-activo">✅ Activo</span>
                            <?php elseif ($prestamo['estado'] == 'vencido'): ?>
                                <span class="estado-vencido">⚠️ VENCIDO</span>
                            <?php else: ?>
                                <span class="estado-devuelto">📖 Devuelto</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($prestamo['estado'] == 'activo'): ?>
                                <button class="btn btn-success btn-sm" onclick="devolverLibro(<?= $prestamo['id'] ?>)">
                                    <i class="fas fa-undo"></i> Devolver
                                </button>
                            <?php elseif ($prestamo['estado'] == 'vencido'): ?>
                                <button class="btn btn-warning btn-sm" onclick="devolverLibro(<?= $prestamo['id'] ?>)">
                                    <i class="fas fa-undo"></i> Devolver
                                </button>
                            <?php else: ?>
                                <span style="color: #a0aec0;">Completado</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10" style="text-align: center; padding: 30px; color: #718096;">
                        <i class="fas fa-hand-holding-heart" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                        No hay préstamos registrados
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php cerrarConexion($conn); ?>