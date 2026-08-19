<?php
require_once 'funciones.php';

$result = obtenerUsuarios($conn);
?>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($usuario = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $usuario['id'] ?></td>
                        <td><strong><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?></strong></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= htmlspecialchars($usuario['telefono'] ?: 'No registrado') ?></td>
                        <td>
                            <div class="acciones">
                                <button class="btn btn-warning btn-sm" onclick="editarUsuario(<?= $usuario['id'] ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(<?= $usuario['id'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: #718096;">
                        <i class="fas fa-users" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                        No hay usuarios registrados
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php cerrarConexion($conn); ?>