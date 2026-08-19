-- =============================================
-- CREAR TABLAS (sin DROP DATABASE)
-- =============================================

USE if0_42689336_biblioteca;

CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(150) NOT NULL,
    editorial VARCHAR(100),
    anio_publicacion INT,
    isbn VARCHAR(20) UNIQUE,
    categoria VARCHAR(50),
    cantidad INT DEFAULT 1,
    disponible INT DEFAULT 1,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libro_id INT NOT NULL,
    usuario_id INT NOT NULL,
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion DATE,
    estado ENUM('activo', 'devuelto', 'vencido') DEFAULT 'activo',
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO libros (titulo, autor, editorial, anio_publicacion, isbn, categoria, cantidad, disponible) VALUES
('Cien años de soledad', 'Gabriel García Márquez', 'Sudamericana', 1967, '978-0307474728', 'Novela', 5, 5),
('El principito', 'Antoine de Saint-Exupéry', 'Reynal & Hitchcock', 1943, '978-0156012195', 'Infantil', 3, 3),
('Don Quijote de la Mancha', 'Miguel de Cervantes', 'Francisco de Robles', 1605, '978-8420412146', 'Novela', 2, 2);

INSERT INTO usuarios (nombre, apellido, email, telefono, direccion) VALUES
('Juan', 'Pérez', 'juan.perez@email.com', '555-1234', 'Calle Principal 123'),
('María', 'González', 'maria.gonzalez@email.com', '555-5678', 'Avenida Central 456');