<?php
-- Base de datos: elindall_repoestudiantes
-- Ejecutar este archivo desde phpMyAdmin de AlwaysData.
-- Si la base de datos ya existe, no es necesario crearla de nuevo.
 // Crea la tabla si no existe.

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS alumnos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(150) NOT NULL,
            identificacion VARCHAR(50) NOT NULL UNIQUE,
            telefono VARCHAR(30) NOT NULL,
            direccion VARCHAR(255) NOT NULL,
            creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
 catch (PDOException $e) {
    die("Error de conexión o creación de tabla: " . htmlspecialchars($e->getMessage()));
}
?>
