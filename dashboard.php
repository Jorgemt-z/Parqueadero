<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$hoy = date('Y-m-d');

// Total vehículos registrados hoy
$q1 = $conn->query("SELECT COUNT(*) AS totalHoy FROM vehiculos WHERE DATE(fecha_ingreso) = '$hoy'");
$totalHoy = $q1->fetch_assoc()['totalHoy'];

// Vehículos sin salida
$q2 = $conn->query("SELECT COUNT(*) AS activos FROM vehiculos WHERE fecha_salida IS NULL");
$activos = $q2->fetch_assoc()['activos'];

// Pagos del día
$q3 = $conn->query("SELECT SUM(monto) AS totalPagado FROM pagos WHERE DATE(fecha_pago) = '$hoy'");
$totalPagado = $q3->fetch_assoc()['totalPagado'] ?? 0;

// Mensualidades vencidas (basado en fecha_ingreso de vehículos sin salida)
$q4 = $conn->query("SELECT fecha_ingreso FROM vehiculos WHERE modalidad = 'mensual' AND fecha_salida IS NULL");
$vencidas = 0;

while ($row = $q4->fetch_assoc()) {
    $fechaIngreso = new DateTime($row['fecha_ingreso']);
    $hoy = new DateTime();
    $diasPasados = $fechaIngreso->diff($hoy)->days;

    if ($diasPasados > 30) {
        $vencidas++;
    }
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
    <h2>📊 Panel de Control</h2>

    <div class="card-grid">

        <div class="card">
            <h3>🚗 Registrados Hoy</h3>
            <p><?= $totalHoy ?></p>
        </div>

        <div class="card">
            <h3>🚪 Activos sin salida</h3>
            <p><?= $activos ?></p>
        </div>

        <div class="card">
            <h3>💰 Pagos Hoy</h3>
            <p>$<?= number_format($totalPagado, 0, ',', '.') ?></p>
        </div>

        <div class="card" style="background-color:#FFCDD2;">
            <h3>⚠️ Mensualidades Vencidas</h3>
            <p><?= $vencidas ?></p>
        </div>

    </div>

    <a href="panel.php" class="btn">🔙 Volver al Panel</a>
</div>
</body>
</html>