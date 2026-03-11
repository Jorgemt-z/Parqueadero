<?php
session_start();
include 'conexion.php';

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// 🔔 Generar alertas de mensualidades vencidas
$sql = "SELECT placa, fecha_ingreso FROM vehiculos WHERE modalidad = 'mensual' AND fecha_salida IS NULL";
$result = $conn->query($sql);
$alertas = [];

while ($row = $result->fetch_assoc()) {
    $fechaIngreso = new DateTime($row['fecha_ingreso']);
    $hoy = new DateTime();
    $diasPasados = $fechaIngreso->diff($hoy)->days;

    if ($diasPasados > 30) {
        $alertas[] = "⚠️ Placa " . $row['placa'] . ": mensualidad vencida hace {$diasPasados} días.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel de control</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <h2>Bienvenido, <?= $_SESSION['usuario'] ?></h2>

    <?php if (!empty($alertas)): ?>
        <div class="alerta">
            <strong>Alertas de mensualidad:</strong>
            <ul>
                <?php foreach ($alertas as $msg): ?>
                    <li><?= $msg ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <nav>
        <a href="ingreso.php" class="btn">Registrar entrada</a>
        <a href="salida.php" class="btn">Registrar salida</a>
        <a href="historial.php" class="btn">Historial</a>
        <a href="dashboard.php" class="btn">📊 Ir al Dashboard</a>
        <a href="logout.php" class="btn">Cerrar sesión</a>
    </nav>

</body>
</html>