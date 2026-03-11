<?php
session_start();
include 'conexion.php';

// Verificar sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $placa = strtoupper(trim($_POST["placa"]));

    // Buscar vehículo por placa sin salida registrada
    $sql = "SELECT id, tipo, modalidad, fecha_ingreso FROM vehiculos WHERE placa = ? AND fecha_salida IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $placa);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($vehiculo = $result->fetch_assoc()) {
        $id = $vehiculo['id'];
        $modalidad = $vehiculo['modalidad'];
        $fecha_ingreso = new DateTime($vehiculo['fecha_ingreso']);
        $fecha_salida = new DateTime(); // Hora actual

        $intervalo = $fecha_ingreso->diff($fecha_salida);
        $horas = ($intervalo->days * 24) + $intervalo->h + ($intervalo->i / 60);

        // 🧮 Calcular tarifa según modalidad
        if ($modalidad === "horas") {
            $monto = ceil($horas) * 1000;
        } elseif ($modalidad === "días") {
            $monto = ceil($intervalo->days) * 10000;
        } elseif ($modalidad === "mensual") {
            $monto = 50000; // El pago ya se gestiona por contrato mensual
        } else {
            $monto = 0;
        }

        // Registrar salida
        $fecha_salida_str = $fecha_salida->format('Y-m-d H:i:s');
        $sql_salida = "UPDATE vehiculos SET fecha_salida = ? WHERE id = ?";
        $stmt_salida = $conn->prepare($sql_salida);
        $stmt_salida->bind_param("si", $fecha_salida_str, $id);
        $stmt_salida->execute();

        // Registrar el pago si aplica
        if ($monto > 0) {
            $sql_pago = "INSERT INTO pagos (vehiculo_id, monto, fecha_pago) VALUES (?, ?, ?)";
            $stmt_pago = $conn->prepare($sql_pago);
            $fecha_pago = $fecha_salida_str;
            $stmt_pago->bind_param("ids", $id, $monto, $fecha_pago);
            $stmt_pago->execute();
        }

        $mensaje = "✅ Salida registrada. Tiempo: " . round($horas, 2) . " h. Tarifa: \$" . number_format($monto);
    } else {
        $mensaje = "⚠️ Vehículo no encontrado o ya tiene salida registrada.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registrar salida</title>
    <link rel="stylesheet" href="estilo.css">
   

</head>
<body>
    <h2>Registro de salida de vehículo</h2>
    <form method="POST" action="salida.php">
        <label>Placa:</label><br>
        <input type="text" name="placa" required maxlength="10"><br><br>
        <input type="submit" value="Registrar salida">
    </form>

    <?php if ($mensaje): ?>
    <p style="color:<?= strpos($mensaje, '✅') !== false ? 'green' : 'red' ?>;">
        <?= $mensaje ?>
    </p>
    <a href="panel.php" class="btn">Regresar</a>
<?php endif; ?>

</body>
</html>