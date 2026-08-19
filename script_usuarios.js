// =============================================
// SCRIPT PARA GESTIÓN DE USUARIOS
// =============================================

function cargarUsuarios() {
    console.log('👥 Cargando usuarios...');
    fetch('cargar_usuarios_completo.php')
        .then(response => response.text())
        .then(data => {
            const tabla = document.getElementById('tablaUsuarios');
            if (tabla) tabla.innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
            const tabla = document.getElementById('tablaUsuarios');
            if (tabla) tabla.innerHTML = '<div class="mensaje mensaje-error">Error al cargar usuarios</div>';
        });
}

function mostrarFormularioUsuario() {
    document.getElementById('modalTituloUsuario').innerHTML = '<i class="fas fa-user"></i> Agregar Usuario';
    document.getElementById('formUsuario').reset();
    document.getElementById('usuarioId').value = '';
    document.getElementById('modalUsuario').style.display = 'block';
}

function editarUsuario(id) {
    fetch('obtener_usuario.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            document.getElementById('modalTituloUsuario').innerHTML = '<i class="fas fa-edit"></i> Editar Usuario';
            document.getElementById('usuarioId').value = data.id;
            document.getElementById('nombre').value = data.nombre;
            document.getElementById('apellido').value = data.apellido;
            document.getElementById('email').value = data.email;
            document.getElementById('telefono').value = data.telefono || '';
            document.getElementById('direccion').value = data.direccion || '';
            document.getElementById('modalUsuario').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del usuario');
        });
}

function eliminarUsuario(id) {
    if (!confirm('¿Estás seguro de eliminar este usuario?')) {
        return;
    }
    
    fetch('eliminar_usuario.php?id=' + id, {
        method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
        if (data.exito) {
            alert('Usuario eliminado correctamente');
            cargarUsuarios();
            if (typeof cargarUsuariosSelect === 'function') cargarUsuariosSelect();
            if (typeof cargarEstadisticas === 'function') cargarEstadisticas();
        } else {
            alert('Error al eliminar el usuario');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar el usuario');
    });
}

function guardarUsuario(event) {
    event.preventDefault();
    const id = document.getElementById('usuarioId').value;
    const datos = {
        id: id,
        nombre: document.getElementById('nombre').value,
        apellido: document.getElementById('apellido').value,
        email: document.getElementById('email').value,
        telefono: document.getElementById('telefono').value,
        direccion: document.getElementById('direccion').value
    };
    const url = id ? 'actualizar_usuario.php' : 'agregar_usuario.php';
    
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
            alert(id ? 'Usuario actualizado correctamente' : 'Usuario agregado correctamente');
            cerrarModal('modalUsuario');
            cargarUsuarios();
            if (typeof cargarUsuariosSelect === 'function') cargarUsuariosSelect();
            if (typeof cargarEstadisticas === 'function') cargarEstadisticas();
        } else {
            alert('Error al guardar el usuario');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el usuario');
    });
}

function cerrarModal(id) {
    document.getElementById(id).style.display = 'none';
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}