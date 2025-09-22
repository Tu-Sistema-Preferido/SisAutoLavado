<?php
session_start();
include("includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Consulta con validación usando mysql_native_password
    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND password=PASSWORD('$password')";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['usuario'] = $row['usuario'];
        $_SESSION['rol'] = $row['rol'];

        if ($row['rol'] == 'admin') {
            header("Location: dashboard_admin.php");
        } else {
            header("Location: dashboard_empleado.php");
        }
        exit;
    } else {
        $error = "❌ Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" lang="es">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Autolavado</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="login-box">
        <h2>Autolavado - Login</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <label>Usuario:</label>
            <input type="text" name="usuario" required>
            <label>Contraseña:</label>
            <input type="password" name="password" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
