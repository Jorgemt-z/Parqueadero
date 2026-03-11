<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Buscar usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $fila = $resultado->fetch_assoc();

        // Comparar contraseña (sin encriptar aún)
        if ($clave === $fila['contraseña']) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $fila['rol'];
            header("Location: panel.php"); // Redireccionar al panel principal
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!-- HTML del formulario de login -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Parqueadero</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h2>Acceso al sistema - Control de parqueo</h2>
    <form method="POST" action="login.php">
        <label>Usuario:</label><br>
        <input type="text" name="usuario" required><br><br>

        <label>Contraseña:</label><br>
        <input type="password" name="clave" required><br><br>

        <input type="submit" value="Ingresar">
    </form>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
</body>
</html>