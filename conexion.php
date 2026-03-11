<?php
$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "parqueadero_db";
date_default_timezone_set("America/Bogota");

$conn = new mysqli($host, $usuario, $clave, $bd);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
// echo "Conectado correctamente"; // Para pruebas
?>