<?php
// Configuración de conexión a MySQL (AlwaysData)
$host = "mysql-jairoapi.alwaysdata.net";
$user = "jairoapi";
$pass = "clase1234";
$db   = "jairoapi_repoestudiantes";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>