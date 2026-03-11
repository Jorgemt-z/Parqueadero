<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$resultado = $conn->query("
    SELECT v.placa, v.tipo, v.modalidad, v.fecha_ingreso, v.fecha_salida, 
           p.monto 
    FROM vehiculos v 
    LEFT JOIN pagos p ON v.id = p.vehiculo_id 
    ORDER BY v.fecha_ingreso DESC
");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Historial de Vehículos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<div class="container">
    <h2>Historial de Vehículos</h2>

    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background-color:#1976D2; color:#fff;">
                <th>Placa</th>
                <th>Tipo</th>
                <th>Modalidad</th>
                <th>Ingreso</th>
                <th>Salida</th>
                <th>Pago</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $resultado->fetch_assoc()): ?>
            <tr style="border-bottom:1px solid #ddd;">
                <td><?= $row['placa'] ?></td>
                <td><?= ucfirst($row['tipo']) ?></td>
                <td><?= ucfirst($row['modalidad']) ?></td>
                <td><?= $row['fecha_ingreso'] ?></td>
                <td><?= $row['fecha_salida'] ?? '-' ?></td>
                <td><?= $row['monto'] !== null ? '$' . number_format($row['monto'], 0, ',', '.') : '-' ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="panel.php" class="btn">Regresar</a>
</div>
</body>
</html>