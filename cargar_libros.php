<?php
require_once 'funciones.php';

$result = obtenerLibros($conn);
?>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Categoría</th>
                <th>Disponible</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($libro = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $libro['id'] ?></td>
                        <td><strong><?= htmlspecialchars($libro['titulo']) ?></strong></td>
                        <td><?= htmlspecialchars($libro['autor']) ?></td>
                        <td><?= htmlspecialchars($libro['categoria'] ?: 'Sin categoría') ?></td>
                        <td class="<?= $libro['disponible'] > 0 ? 'estado-disponible' : 'estado-no-disponible' ?>">
                            <?= $libro['disponible'] ?> / <?= $libro['cantidad'] ?>
                        </td>
                        <td>
                            <div class="acciones">
                                <button class="btn btn-success btn-sm" onclick="mostrarPrestamo(<?= $libro['id'] ?>, '<?= addslashes($libro['titulo']) ?>')">
                                    <i class="fas fa-hand-holding-heart"></i>
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="editarLibro(<?= $libro['id'] ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarLibro(<?= $libro['id'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #718096;">
                        <i class="fas fa-book" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                        No hay libros registrados
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php cerrarConexion($conn); ?>