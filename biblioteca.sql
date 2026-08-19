-- =============================================
-- BASE DE DATOS PARA BIBLIOTECA
-- VERSIÓN COMPLETA Y CORREGIDA
-- =============================================

-- =============================================
-- 1. ELIMINAR Y CREAR LA BASE DE DATOS
-- =============================================

DROP DATABASE IF EXISTS biblioteca;
CREATE DATABASE biblioteca;
USE biblioteca;

-- =============================================
-- 2. TABLA: libros (CATÁLOGO DE LIBROS)
-- =============================================

CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(150) NOT NULL,
    editorial VARCHAR(100),
    anio_publicacion INT,
    isbn VARCHAR(20) UNIQUE,
    categoria VARCHAR(50),
    cantidad INT DEFAULT 1,
    disponible INT DEFAULT 1,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    descripcion TEXT,
    idioma VARCHAR(50) DEFAULT 'Español',
    paginas INT,
    ubicacion VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 3. TABLA: usuarios (USUARIOS REGISTRADOS)
-- =============================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tipo_usuario ENUM('estudiante', 'docente', 'administrativo', 'externo') DEFAULT 'estudiante',
    fecha_nacimiento DATE,
    genero ENUM('M', 'F', 'Otro'),
    activo BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 4. TABLA: prestamos (PRÉSTAMOS DE LIBROS)
-- =============================================

CREATE TABLE prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libro_id INT NOT NULL,
    usuario_id INT NOT NULL,
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion_esperada DATE NOT NULL,
    fecha_devolucion_real DATE,
    estado ENUM('activo', 'devuelto', 'vencido') DEFAULT 'activo',
    observaciones TEXT,
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 5. TABLA: categorias (CATEGORÍAS DE LIBROS)
-- =============================================

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 6. TABLA: autores (AUTORES DE LIBROS)
-- =============================================

CREATE TABLE autores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    nacionalidad VARCHAR(50),
    fecha_nacimiento DATE,
    fecha_fallecimiento DATE,
    biografia TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 7. TABLA: libro_autor (RELACIÓN LIBROS-AUTORES)
-- =============================================

CREATE TABLE libro_autor (
    libro_id INT NOT NULL,
    autor_id INT NOT NULL,
    PRIMARY KEY (libro_id, autor_id),
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE,
    FOREIGN KEY (autor_id) REFERENCES autores(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 8. TABLA: historial_prestamos (HISTORIAL DE PRÉSTAMOS)
-- =============================================

CREATE TABLE historial_prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prestamo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    libro_id INT NOT NULL,
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion DATE,
    estado VARCHAR(20),
    FOREIGN KEY (prestamo_id) REFERENCES prestamos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 9. TABLA: multas (MULTAS POR RETRASO)
-- =============================================

CREATE TABLE multas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prestamo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha_multa DATE NOT NULL,
    pagada BOOLEAN DEFAULT FALSE,
    fecha_pago DATE,
    FOREIGN KEY (prestamo_id) REFERENCES prestamos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 10. TABLA: reservas (RESERVAS DE LIBROS)
-- =============================================

CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libro_id INT NOT NULL,
    usuario_id INT NOT NULL,
    fecha_reserva DATE NOT NULL,
    fecha_expiracion DATE NOT NULL,
    estado ENUM('activa', 'completada', 'expirada') DEFAULT 'activa',
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- 11. DATOS DE EJEMPLO - CATEGORÍAS
-- =============================================

INSERT INTO categorias (nombre, descripcion) VALUES
('Novela', 'Obras literarias de ficción'),
('Ciencia Ficción', 'Ficción especulativa y futurista'),
('Fantasía', 'Mundos imaginarios y criaturas mágicas'),
('Infantil', 'Libros para niños y jóvenes'),
('Poesía', 'Obras poéticas y líricas'),
('Ensayo', 'Obras de no ficción'),
('Tecnología', 'Libros sobre tecnología e informática'),
('Historia', 'Obras históricas'),
('Biografía', 'Vidas de personajes famosos'),
('Aventura', 'Historias de exploración y acción'),
('Misterio', 'Novelas de suspense y misterio'),
('Romance', 'Novelas de amor y relaciones'),
('Terror', 'Historias de miedo y suspenso'),
('Filosofía', 'Obras filosóficas'),
('Psicología', 'Libros sobre la mente y el comportamiento');

-- =============================================
-- 12. DATOS DE EJEMPLO - AUTORES
-- =============================================

INSERT INTO autores (nombre, apellido, nacionalidad, fecha_nacimiento, biografia) VALUES
('Gabriel', 'García Márquez', 'Colombiana', '1927-03-06', 'Escritor y periodista colombiano, premio Nobel de Literatura 1982'),
('George', 'Orwell', 'Británica', '1903-06-25', 'Escritor y periodista británico, autor de 1984 y Rebelión en la granja'),
('Antoine', 'Saint-Exupéry', 'Francesa', '1900-06-29', 'Aviador y escritor francés, autor de El Principito'),
('Miguel', 'Cervantes', 'Española', '1547-09-29', 'Novelista, poeta y dramaturgo español, autor de Don Quijote'),
('J.R.R.', 'Tolkien', 'Británica', '1892-01-03', 'Escritor y filólogo británico, autor de El Hobbit y El Señor de los Anillos'),
('Jane', 'Austen', 'Británica', '1775-12-16', 'Novelista británica, autora de Orgullo y Prejuicio'),
('Fiódor', 'Dostoyevski', 'Rusa', '1821-11-11', 'Novelista ruso, autor de Crimen y Castigo'),
('Herman', 'Melville', 'Estadounidense', '1819-08-01', 'Escritor estadounidense, autor de Moby Dick'),
('Mark', 'Twain', 'Estadounidense', '1835-11-30', 'Escritor estadounidense, autor de Las aventuras de Tom Sawyer'),
('Arthur Conan', 'Doyle', 'Británica', '1859-05-22', 'Escritor británico, creador de Sherlock Holmes'),
('Agatha', 'Christie', 'Británica', '1890-09-15', 'Escritora británica, autora de novelas de misterio'),
('Stephen', 'King', 'Estadounidense', '1947-09-21', 'Escritor estadounidense, autor de novelas de terror'),
('Paulo', 'Coelho', 'Brasileña', '1947-08-24', 'Escritor brasileño, autor de El Alquimista'),
('Isabel', 'Allende', 'Chilena', '1942-08-02', 'Escritora chilena, autora de La casa de los espíritus'),
('Mario', 'Vargas Llosa', 'Peruana', '1936-03-28', 'Escritor peruano, premio Nobel de Literatura 2010');

-- =============================================
-- 13. DATOS DE EJEMPLO - LIBROS (CON ISBN CORREGIDOS)
-- =============================================

INSERT INTO libros (titulo, autor, editorial, anio_publicacion, isbn, categoria, cantidad, disponible, descripcion, idioma, paginas, ubicacion) VALUES
('Cien años de soledad', 'Gabriel García Márquez', 'Sudamericana', 1967, '978-0307474728', 'Novela', 5, 5, 'La historia de la familia Buendía en Macondo', 'Español', 432, 'Estante A1'),
('1984', 'George Orwell', 'Secker & Warburg', 1949, '978-0451524935', 'Ciencia Ficción', 4, 4, 'Una distopía sobre un régimen totalitario', 'Español', 326, 'Estante B2'),
('El principito', 'Antoine de Saint-Exupéry', 'Reynal & Hitchcock', 1943, '978-0156012195', 'Infantil', 3, 3, 'Un cuento poético sobre la amistad y el amor', 'Español', 96, 'Estante C3'),
('Don Quijote de la Mancha', 'Miguel de Cervantes', 'Francisco de Robles', 1605, '978-8420412146', 'Novela', 2, 2, 'La obra maestra de la literatura española', 'Español', 1200, 'Estante A1'),
('El Hobbit', 'J.R.R. Tolkien', 'George Allen & Unwin', 1937, '978-0547928227', 'Fantasía', 3, 3, 'La aventura de Bilbo Bolsón en la Tierra Media', 'Español', 310, 'Estante B1'),
('Orgullo y Prejuicio', 'Jane Austen', 'T. Egerton', 1813, '978-0141439518', 'Novela', 3, 3, 'Historia de amor y orgullo en la Inglaterra del siglo XIX', 'Español', 279, 'Estante A2'),
('Crimen y Castigo', 'Fiódor Dostoyevski', 'The Russian Messenger', 1866, '978-0140449136', 'Novela', 2, 2, 'La historia de un estudiante que comete un asesinato', 'Español', 500, 'Estante A1'),
('Moby Dick', 'Herman Melville', 'Harper & Brothers', 1851, '978-0142437247', 'Aventura', 2, 2, 'La obsesión del capitán Ahab con la ballena blanca', 'Español', 635, 'Estante D1'),
('Las aventuras de Tom Sawyer', 'Mark Twain', 'American Publishing Co.', 1876, '978-0141321103', 'Infantil', 3, 3, 'Las aventuras de un niño en el Misisipi', 'Español', 250, 'Estante C1'),
('El Alquimista', 'Paulo Coelho', 'HarperCollins', 1988, '978-0062502174', 'Novela', 4, 4, 'La búsqueda de un joven por su leyenda personal', 'Español', 208, 'Estante A3'),
('El Señor de los Anillos', 'J.R.R. Tolkien', 'George Allen & Unwin', 1954, '978-0547928210', 'Fantasía', 2, 2, 'La épica aventura de la comunidad del anillo', 'Español', 1200, 'Estante B1'),
('Drácula', 'Bram Stoker', 'Archibald Constable and Co.', 1897, '978-0141439846', 'Terror', 2, 2, 'La historia del famoso vampiro', 'Español', 350, 'Estante D2'),
('El retrato de Dorian Gray', 'Oscar Wilde', 'Lippincott', 1890, '978-0141439570', 'Novela', 3, 3, 'La historia de un hombre que vende su alma por la belleza', 'Español', 280, 'Estante A2'),
('Las crónicas de Narnia', 'C.S. Lewis', 'Geoffrey Bles', 1950, '978-0066238500', 'Fantasía', 3, 3, 'Las aventuras en el mundo mágico de Narnia', 'Español', 450, 'Estante B2'),
('La casa de los espíritus', 'Isabel Allende', 'Plaza & Janés', 1982, '978-8401326123', 'Novela', 3, 3, 'La historia de la familia Trueba en Chile', 'Español', 450, 'Estante A1');

-- =============================================
-- 14. DATOS DE EJEMPLO - USUARIOS
-- =============================================

INSERT INTO usuarios (nombre, apellido, email, telefono, direccion, tipo_usuario, fecha_nacimiento, genero, activo) VALUES
('Juan', 'Pérez', 'juan.perez@email.com', '555-1234', 'Calle Principal 123', 'estudiante', '2000-05-15', 'M', TRUE),
('María', 'González', 'maria.gonzalez@email.com', '555-5678', 'Avenida Central 456', 'docente', '1985-08-20', 'F', TRUE),
('Carlos', 'López', 'carlos.lopez@email.com', '555-9012', 'Plaza Mayor 789', 'administrativo', '1990-03-10', 'M', TRUE),
('Ana', 'Martínez', 'ana.martinez@email.com', '555-3456', 'Calle Secundaria 456', 'estudiante', '2001-11-25', 'F', TRUE),
('Laura', 'Fernández', 'laura.fernandez@email.com', '555-7890', 'Boulevard de la Cultura 1', 'docente', '1988-07-12', 'F', TRUE),
('Pedro', 'Sánchez', 'pedro.sanchez@email.com', '555-2345', 'Calle de la Biblioteca 12', 'administrativo', '1975-09-30', 'M', TRUE),
('Sofia', 'Ramírez', 'sofia.ramirez@email.com', '555-6789', 'Avenida Universidad 45', 'estudiante', '2002-04-18', 'F', TRUE),
('Andrés', 'Torres', 'andres.torres@email.com', '555-0123', 'Calle del Parque 8', 'externo', '1995-12-01', 'M', TRUE),
('Valentina', 'Gómez', 'valentina.gomez@email.com', '555-4567', 'Carrera 45 #23-12', 'estudiante', '2000-06-22', 'F', TRUE),
('Diego', 'Molina', 'diego.molina@email.com', '555-8901', 'Diagonal 34A #15-20', 'docente', '1982-10-14', 'M', TRUE),
('Daniela', 'Rojas', 'daniela.rojas@email.com', '555-2341', 'Calle 8 #45-32', 'estudiante', '2003-08-07', 'F', TRUE),
('Felipe', 'Silva', 'felipe.silva@email.com', '555-5672', 'Avenida Libertad 100', 'administrativo', '1992-02-28', 'M', TRUE),
('Natalia', 'García', 'natalia.garcia@email.com', '555-9013', 'Calle de los Sueños 56', 'docente', '1986-11-09', 'F', TRUE),
('Alejandro', 'Reyes', 'alejandro.reyes@email.com', '555-3458', 'Carrera 12 #78-90', 'estudiante', '2001-07-19', 'M', TRUE),
('Camila', 'Ortega', 'camila.ortega@email.com', '555-7892', 'Transversal 6 #34-12', 'externo', '1998-03-04', 'F', TRUE);

-- =============================================
-- 15. DATOS DE EJEMPLO - PRÉSTAMOS
-- =============================================

INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, fecha_devolucion_esperada, fecha_devolucion_real, estado) VALUES
(1, 1, CURDATE() - INTERVAL 5 DAY, CURDATE() + INTERVAL 10 DAY, NULL, 'activo'),
(2, 2, CURDATE() - INTERVAL 3 DAY, CURDATE() + INTERVAL 12 DAY, NULL, 'activo'),
(3, 3, CURDATE() - INTERVAL 10 DAY, CURDATE() + INTERVAL 5 DAY, NULL, 'activo'),
(4, 4, CURDATE() - INTERVAL 20 DAY, CURDATE() - INTERVAL 5 DAY, NULL, 'vencido'),
(5, 5, CURDATE() - INTERVAL 2 DAY, CURDATE() + INTERVAL 13 DAY, NULL, 'activo'),
(6, 6, CURDATE() - INTERVAL 15 DAY, CURDATE() - INTERVAL 0 DAY, CURDATE() - INTERVAL 1 DAY, 'devuelto'),
(7, 7, CURDATE() - INTERVAL 7 DAY, CURDATE() + INTERVAL 8 DAY, NULL, 'activo'),
(8, 8, CURDATE() - INTERVAL 25 DAY, CURDATE() - INTERVAL 10 DAY, CURDATE() - INTERVAL 8 DAY, 'devuelto'),
(9, 9, CURDATE() - INTERVAL 1 DAY, CURDATE() + INTERVAL 14 DAY, NULL, 'activo'),
(10, 10, CURDATE() - INTERVAL 30 DAY, CURDATE() - INTERVAL 15 DAY, CURDATE() - INTERVAL 12 DAY, 'devuelto');

-- =============================================
-- 16. DATOS DE EJEMPLO - HISTORIAL DE PRÉSTAMOS
-- =============================================

INSERT INTO historial_prestamos (prestamo_id, usuario_id, libro_id, fecha_prestamo, fecha_devolucion, estado)
SELECT id, usuario_id, libro_id, fecha_prestamo, fecha_devolucion_real, estado
FROM prestamos;

-- =============================================
-- 17. DATOS DE EJEMPLO - MULTAS
-- =============================================

INSERT INTO multas (prestamo_id, usuario_id, monto, fecha_multa, pagada, fecha_pago) VALUES
(4, 4, 5.50, CURDATE() - INTERVAL 5 DAY, FALSE, NULL),
(8, 8, 8.00, CURDATE() - INTERVAL 10 DAY, TRUE, CURDATE() - INTERVAL 5 DAY),
(10, 10, 10.00, CURDATE() - INTERVAL 15 DAY, TRUE, CURDATE() - INTERVAL 10 DAY);

-- =============================================
-- 18. DATOS DE EJEMPLO - RESERVAS
-- =============================================

INSERT INTO reservas (libro_id, usuario_id, fecha_reserva, fecha_expiracion, estado) VALUES
(1, 11, CURDATE() - INTERVAL 2 DAY, CURDATE() + INTERVAL 5 DAY, 'activa'),
(2, 12, CURDATE() - INTERVAL 1 DAY, CURDATE() + INTERVAL 6 DAY, 'activa'),
(3, 13, CURDATE() - INTERVAL 10 DAY, CURDATE() - INTERVAL 3 DAY, 'expirada'),
(4, 14, CURDATE() - INTERVAL 15 DAY, CURDATE() - INTERVAL 8 DAY, 'completada'),
(5, 15, CURDATE() - INTERVAL 3 DAY, CURDATE() + INTERVAL 4 DAY, 'activa');

-- =============================================
-- 19. CREAR VISTAS (CONSULTAS ÚTILES)
-- =============================================

CREATE VIEW vw_libros_disponibles AS
SELECT id, titulo, autor, categoria, disponible, cantidad
FROM libros
WHERE disponible > 0
ORDER BY titulo;

CREATE VIEW vw_prestamos_activos AS
SELECT 
    p.id AS prestamo_id,
    l.titulo AS libro,
    u.nombre AS usuario,
    u.email,
    p.fecha_prestamo,
    p.fecha_devolucion_esperada,
    DATEDIFF(p.fecha_devolucion_esperada, CURDATE()) AS dias_restantes
FROM prestamos p
JOIN libros l ON p.libro_id = l.id
JOIN usuarios u ON p.usuario_id = u.id
WHERE p.estado = 'activo'
ORDER BY p.fecha_prestamo DESC;

CREATE VIEW vw_usuarios_top_prestamos AS
SELECT 
    u.id,
    u.nombre,
    u.apellido,
    u.email,
    COUNT(p.id) AS total_prestamos
FROM usuarios u
LEFT JOIN prestamos p ON u.id = p.usuario_id
GROUP BY u.id
ORDER BY total_prestamos DESC
LIMIT 10;

-- =============================================
-- 20. CREAR PROCEDIMIENTOS ALMACENADOS
-- =============================================

DELIMITER //
CREATE PROCEDURE sp_registrar_prestamo(
    IN p_libro_id INT,
    IN p_usuario_id INT,
    IN p_dias_prestamo INT
)
BEGIN
    DECLARE v_disponible INT;
    
    SELECT disponible INTO v_disponible FROM libros WHERE id = p_libro_id;
    
    IF v_disponible > 0 THEN
        INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, fecha_devolucion_esperada, estado)
        VALUES (p_libro_id, p_usuario_id, CURDATE(), CURDATE() + INTERVAL p_dias_prestamo DAY, 'activo');
        
        UPDATE libros SET disponible = disponible - 1 WHERE id = p_libro_id;
        
        SELECT 'Préstamo registrado exitosamente' AS mensaje;
    ELSE
        SELECT 'No hay ejemplares disponibles' AS mensaje;
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE sp_devolver_libro(
    IN p_prestamo_id INT
)
BEGIN
    DECLARE v_libro_id INT;
    
    SELECT libro_id INTO v_libro_id FROM prestamos WHERE id = p_prestamo_id AND estado = 'activo';
    
    IF v_libro_id IS NOT NULL THEN
        UPDATE prestamos 
        SET estado = 'devuelto', fecha_devolucion_real = CURDATE() 
        WHERE id = p_prestamo_id;
        
        UPDATE libros SET disponible = disponible + 1 WHERE id = v_libro_id;
        
        INSERT INTO historial_prestamos (prestamo_id, usuario_id, libro_id, fecha_prestamo, fecha_devolucion, estado)
        SELECT id, usuario_id, libro_id, fecha_prestamo, CURDATE(), 'devuelto'
        FROM prestamos WHERE id = p_prestamo_id;
        
        SELECT 'Libro devuelto exitosamente' AS mensaje;
    ELSE
        SELECT 'Préstamo no encontrado o ya devuelto' AS mensaje;
    END IF;
END //
DELIMITER ;

-- =============================================
-- 21. CREAR FUNCIONES
-- =============================================

DELIMITER //
CREATE FUNCTION fn_dias_retraso(p_prestamo_id INT) 
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE v_dias INT;
    
    SELECT DATEDIFF(CURDATE(), fecha_devolucion_esperada) INTO v_dias
    FROM prestamos
    WHERE id = p_prestamo_id AND estado = 'activo';
    
    IF v_dias < 0 THEN
        SET v_dias = 0;
    END IF;
    
    RETURN v_dias;
END //
DELIMITER ;

-- =============================================
-- 22. CREAR TRIGGERS
-- =============================================

DELIMITER //
CREATE TRIGGER trg_actualizar_vencidos
AFTER UPDATE ON prestamos
FOR EACH ROW
BEGIN
    IF NEW.estado = 'activo' AND NEW.fecha_devolucion_esperada < CURDATE() THEN
        UPDATE prestamos 
        SET estado = 'vencido' 
        WHERE id = NEW.id;
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER trg_historial_prestamo
AFTER INSERT ON prestamos
FOR EACH ROW
BEGIN
    INSERT INTO historial_prestamos (prestamo_id, usuario_id, libro_id, fecha_prestamo, fecha_devolucion, estado)
    VALUES (NEW.id, NEW.usuario_id, NEW.libro_id, NEW.fecha_prestamo, NULL, 'activo');
END //
DELIMITER ;

-- =============================================
-- 23. VERIFICACIÓN FINAL
-- =============================================

SELECT '✅ TABLAS CREADAS' as Mensaje;
SHOW TABLES;

SELECT '📚 LIBROS' as '';
SELECT COUNT(*) as Total_Libros FROM libros;

SELECT '👥 USUARIOS' as '';
SELECT COUNT(*) as Total_Usuarios FROM usuarios;

SELECT '📖 PRÉSTAMOS' as '';
SELECT COUNT(*) as Total_Prestamos FROM prestamos;

SELECT '📊 RESUMEN GENERAL' as '';
SELECT 
    (SELECT COUNT(*) FROM libros) AS Total_Libros,
    (SELECT COUNT(*) FROM usuarios) AS Total_Usuarios,
    (SELECT COUNT(*) FROM prestamos) AS Total_Prestamos,
    (SELECT COUNT(*) FROM prestamos WHERE estado = 'activo') AS Prestamos_Activos,
    (SELECT COUNT(*) FROM prestamos WHERE estado = 'vencido') AS Prestamos_Vencidos,
    (SELECT COUNT(*) FROM prestamos WHERE estado = 'devuelto') AS Prestamos_Devueltos;

SELECT '📚 EJEMPLO DE LIBROS' as '';
SELECT id, titulo, autor, categoria, disponible, cantidad FROM libros LIMIT 5;

SELECT '👥 EJEMPLO DE USUARIOS' as '';
SELECT id, nombre, apellido, email, tipo_usuario FROM usuarios LIMIT 5;

SELECT '📖 EJEMPLO DE PRÉSTAMOS ACTIVOS' as '';
SELECT p.id, l.titulo, u.nombre, p.fecha_prestamo, p.fecha_devolucion_esperada 
FROM prestamos p
JOIN libros l ON p.libro_id = l.id
JOIN usuarios u ON p.usuario_id = u.id
WHERE p.estado = 'activo'
LIMIT 5;

SELECT '✅ BASE DE DATOS COMPLETA Y FUNCIONAL' as Mensaje;