// =============================================
// SCRIPT PARA GESTIÓN DE PRÉSTAMOS
// =============================================

function cargarPrestamos() {
    console.log('📖 Cargando préstamos...');
    fetch('cargar_prestamos.php')
        .then(response => response.text())
        .then(data => {
            const tabla = document.getElementById('tablaPrestamos');
            if (tabla) tabla.innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
            const tabla = document.getElementById('tablaPrestamos');
            if (tabla) tabla.innerHTML = '<div class="mensaje mensaje-error">Error al cargar préstamos</div>';
        });
}

function cargarEstadisticasPrestamos() {
    console.log('📊 Cargando estadísticas...');
    fetch('estadisticas_prestamos.php')
        .then(response => response.json())
        .then(data => {
            const activos = document.getElementById('prestamosActivos');
            const vencidos = document.getElementById('prestamosVencidos');
            
            if (activos) activos.textContent = data.prestamosActivos || 0;
            if (vencidos) vencidos.textContent = data.prestamosVencidos || 0;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function devolverLibro(prestamo_id) {
    if (!confirm('¿Estás seguro de registrar la devolución de este libro?')) {
        return;
    }
    
    fetch('devolver_libro.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ prestamo_id: prestamo_id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.exito) {
            alert('✅ Libro devuelto correctamente');
            cargarPrestamos();
            cargarEstadisticasPrestamos();
            if (typeof cargarEstadisticas === 'function') cargarEstadisticas();
            if (typeof cargarLibros === 'function') cargarLibros();
        } else {
            alert('❌ Error al devolver el libro');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al devolver el libro');
    });
}
