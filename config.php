<?php
// Configuración de conexión a MySQL (AlwaysData)
$host = "mysql-elindall.alwaysdata.net";
$user = "elindall";
$pass = "jhosep2020";
$db   = "elindall_repoestudiantes";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>