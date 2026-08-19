// =============================================
// SCRIPT PARA LA BIBLIOTECA
// =============================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM cargado');
    cargarLibros();
    cargarEstadisticas();
    cargarUsuariosSelect();
});

function cargarLibros() {
    fetch('cargar_libros.php')
        .then(response => response.text())
        .then(data => {
            const tabla = document.getElementById('tablaLibros');
            if (tabla) tabla.innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
            const tabla = document.getElementById('tablaLibros');
            if (tabla) tabla.innerHTML = '<div class="mensaje mensaje-error">Error al cargar libros</div>';
        });
}

function cargarEstadisticas() {
    console.log('📊 Cargando estadísticas...');
    fetch('estadisticas.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            console.log('📊 Datos recibidos:', data);
            
            const totalLibros = document.getElementById('totalLibros');
            const totalUsuarios = document.getElementById('totalUsuarios');
            const prestamosActivos = document.getElementById('prestamosActivos');
            const prestamosVencidos = document.getElementById('prestamosVencidos');
            
            if (totalLibros) totalLibros.textContent = data.total_libros || 0;
            if (totalUsuarios) totalUsuarios.textContent = data.total_usuarios || 0;
            if (prestamosActivos) prestamosActivos.textContent = data.prestamos_activos || 0;
            if (prestamosVencidos) prestamosVencidos.textContent = data.prestamos_vencidos || 0;
            
            console.log('✅ Estadísticas actualizadas');
        })
        .catch(error => {
            console.error('❌ Error al cargar estadísticas:', error);
        });
}

function cargarUsuariosSelect() {
    fetch('cargar_usuarios.php')
        .then(response => response.text())
        .then(data => {
            const select = document.getElementById('prestamoUsuarioId');
            if (select) select.innerHTML = '<option value="">Seleccionar usuario...</option>' + data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function buscarLibros() {
    const termino = document.getElementById('searchInput').value.trim();
    if (termino === '') {
        cargarLibros();
        return;
    }
    
    fetch('buscar_libro.php?q=' + encodeURIComponent(termino))
        .then(response => response.text())
        .then(data => {
            document.getElementById('tablaLibros').innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al buscar libros');
        });
}

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        buscarLibros();
    }
});

function mostrarFormularioLibro() {
    document.getElementById('modalTitulo').innerHTML = '<i class="fas fa-book"></i> Agregar Libro';
    document.getElementById('formLibro').reset();
    document.getElementById('libroId').value = '';
    document.getElementById('modalLibro').style.display = 'block';
}

function editarLibro(id) {
    fetch('obtener_libro.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            
            document.getElementById('modalTitulo').innerHTML = '<i class="fas fa-edit"></i> Editar Libro';
            document.getElementById('libroId').value = data.id;
            document.getElementById('titulo').value = data.titulo;
            document.getElementById('autor').value = data.autor;
            document.getElementById('editorial').value = data.editorial || '';
            document.getElementById('anio').value = data.anio_publicacion || '';
            document.getElementById('isbn').value = data.isbn || '';
            document.getElementById('categoria').value = data.categoria || '';
            document.getElementById('cantidad').value = data.cantidad;
            document.getElementById('modalLibro').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del libro');
        });
}

function eliminarLibro(id) {
    if (!confirm('¿Estás seguro de eliminar este libro?')) {
        return;
    }
    
    fetch('eliminar_libro.php?id=' + id, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        if (data.exito) {
            alert('Libro eliminado correctamente');
            cargarLibros();
            cargarEstadisticas();
        } else {
            alert('Error al eliminar el libro');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar el libro');
    });
}

function mostrarPrestamo(libroId, libroTitulo) {
    document.getElementById('prestamoLibroId').value = libroId;
    document.getElementById('prestamoLibroTitulo').value = libroTitulo;
    document.getElementById('modalPrestamo').style.display = 'block';
}

function cerrarModal(id) {
    document.getElementById(id).style.display = 'none';
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

function guardarLibro(event) {
    event.preventDefault();
    
    const id = document.getElementById('libroId').value;
    const datos = {
        id: id,
        titulo: document.getElementById('titulo').value,
        autor: document.getElementById('autor').value,
        editorial: document.getElementById('editorial').value,
        anio: document.getElementById('anio').value,
        isbn: document.getElementById('isbn').value,
        categoria: document.getElementById('categoria').value,
        cantidad: document.getElementById('cantidad').value
    };
    
    const url = id ? 'actualizar_libro.php' : 'agregar_libro.php';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.exito) {
            alert(id ? 'Libro actualizado correctamente' : 'Libro agregado correctamente');
            cerrarModal('modalLibro');
            cargarLibros();
            cargarEstadisticas();
        } else {
            alert('Error al guardar el libro');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el libro');
    });
}

// =============================================
// FUNCIÓN CORREGIDA PARA REGISTRAR PRÉSTAMOS Y ACTUALIZAR LA VISTA
// =============================================
function registrarPrestamo(event) {
    event.preventDefault();
    
    const datos = {
        libro_id: document.getElementById('prestamoLibroId').value,
        usuario_id: document.getElementById('prestamoUsuarioId').value
    };
    
    if (!datos.usuario_id) {
        alert('Selecciona un usuario');
        return;
    }
    
    fetch('registrar_prestamo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datos)
    })
    .then(response => response.json())
    .then(data => {
        if (data.exito) {
            alert('Préstamo registrado correctamente');
            cerrarModal('modalPrestamo');
            
            // ACTUALIZAR TODOS LOS DATOS EN TIEMPO REAL
            cargarLibros();
            cargarEstadisticas();
            cargarPrestamos(); // Esta línea hace que aparezca el préstamo
            cargarEstadisticasPrestamos();
        } else {
            alert('Error al registrar el préstamo: ' + data.mensaje);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al registrar el préstamo');
    });
}

// Asegurar que cargarPrestamos esté definida (puede estar en otro archivo)
if (typeof cargarPrestamos !== 'function') {
    function cargarPrestamos() {
        fetch('cargar_prestamos.php')
            .then(response => response.text())
            .then(data => {
                const tabla = document.getElementById('tablaPrestamos');
                if (tabla) tabla.innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
    }
}

if (typeof cargarEstadisticasPrestamos !== 'function') {
    function cargarEstadisticasPrestamos() {
        fetch('estadisticas_prestamos.php')
            .then(response => response.json())
            .then(data => {
                const activos = document.getElementById('prestamosActivos');
                const vencidos = document.getElementById('prestamosVencidos');
                if (activos) activos.textContent = data.prestamosActivos || 0;
                if (vencidos) vencidos.textContent = data.prestamosVencidos || 0;
            })
            .catch(error => console.error('Error:', error));
    }
}
