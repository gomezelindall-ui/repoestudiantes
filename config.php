<?php
// Configuración de conexión a MySQL (AlwaysData)
$host = 'mysql-mario99.alwaysdata.net';
$password = 'luis1009';
$username = 'mario99';
$dbname = 'mario99_alumnos';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>