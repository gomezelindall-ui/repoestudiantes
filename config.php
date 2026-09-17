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
}