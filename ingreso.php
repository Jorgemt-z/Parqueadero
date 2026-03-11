<?php
session_start();
include 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = strtoupper(trim($_POST['placa']));
    $tipo = $_POST['tipo'];
    $modalidad = $_POST['modalidad'];
    $fecha_ingreso = date("Y-m-d H:i:s");    

    // Verificar si la placa ya está registrada sin salida
    $consulta = "SELECT * FROM vehiculos WHERE placa = ? AND fecha_salida IS NULL";
    $stmt = $conn->prepare($consulta);
    $stmt->bind_param("s", $placa);
    $stmt->execute();
    $resultado = $stmt->get_result();
    


    if ($resultado->num_rows > 0) {
        $mensaje = "⚠️ Este vehículo ya está registrado y aún no ha salido.";
    } else {
        // Insertar nuevo vehículo
        $insertar = "INSERT INTO vehiculos (placa, tipo, modalidad, fecha_ingreso) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertar);
        $stmt->bind_param("ssss", $placa, $tipo, $modalidad, $fecha_ingreso);

        if ($stmt->execute()) {
            $mensaje = "✅ Vehículo registrado exitosamente.";
        } else {
            $mensaje = "❌ Error al registrar el vehículo.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ingreso de Vehículos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h2>Registrar Ingreso de Vehículo</h2>
    <form method="POST" action="ingreso.php">
        <label>Placa:</label><br>
        <input type="text" name="placa" required maxlength="10"><br><br>

        <label>Tipo:</label><br>
        <select name="tipo" required>
            <option value="carro">Carro</option>
            <option value="moto">Moto</option>
        </select><br><br>

        <label>Modalidad de pago:</label><br>
        <select name="modalidad" required>
            <option value="horas">Horas</option>
            <option value="días">Días</option>
            <option value="mensual">Mensual</option>
        </select><br><br>

        <input type="submit" value="Registrar Ingreso">
    </form>

    <?php if ($mensaje): ?>
    <p style="color:<?= strpos($mensaje, '✅') !== false ? 'green' : 'red' ?>;">
        <?= $mensaje ?>
    </p>
    <a href="panel.php" class="btn">Regresar</a>
<?php endif; ?>

</body>
</html>