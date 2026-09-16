-- Base de datos: elindall_repoestudiantes
-- Ejecutar este archivo desde phpMyAdmin de AlwaysData.
-- Si la base de datos ya existe, no es necesario crearla de nuevo.

CREATE TABLE IF NOT EXISTS estudiantes (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(120) NOT NULL,
    identificacion VARCHAR(30) NOT NULL,
    telefono VARCHAR(30) DEFAULT NULL,
    direccion VARCHAR(180) DEFAULT NULL,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_identificacion (identificacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10 estudiantes de ejemplo.
INSERT INTO estudiantes (nombre, identificacion, telefono, direccion) VALUES
('Ana María López', '1001001001', '3001234567', 'Calle 10 # 15-20'),
('Carlos Andrés Pérez', '1001001002', '3012345678', 'Carrera 8 # 22-14'),
('Laura Valentina Gómez', '1001001003', '3023456789', 'Calle 25 # 7-31'),
('Juan Sebastián Rodríguez', '1001001004', '3034567890', 'Carrera 12 # 18-45'),
('Mariana Torres Díaz', '1001001005', '3045678901', 'Calle 33 # 10-16'),
('Daniel Felipe Martínez', '1001001006', '3056789012', 'Carrera 20 # 9-27'),
('Sofía Camila Herrera', '1001001007', '3067890123', 'Calle 14 # 30-08'),
('Mateo Alejandro Castro', '1001001008', '3078901234', 'Carrera 5 # 40-12'),
('Valentina Rojas Sánchez', '1001001009', '3089012345', 'Calle 41 # 16-23'),
('Santiago David Moreno', '1001001010', '3090123456', 'Carrera 15 # 28-36');
