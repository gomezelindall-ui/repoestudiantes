<?php
// Configuración de conexión a MySQL
$host = 'mysql-mario99.alwaysdata.net';
$db   = 'mario99_alumnos';
$user = 'mario99';
$pass = 'luis1009';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

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
} catch (PDOException $e) {
    die("Error de conexión o creación de tabla: " . htmlspecialchars($e->getMessage()));
}
?>
