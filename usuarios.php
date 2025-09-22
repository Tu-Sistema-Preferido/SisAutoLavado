<?php
session_start();
include("includes/conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $password = $_POST['password']; // sin password_hash
    $rol = $_POST['rol'];

    // Guardar con mysql_native_password
    $sql = "INSERT INTO usuarios (nombre, usuario, password, rol) 
            VALUES ('$nombre', '$usuario', PASSWORD('$password'), '$rol')";
    if ($conn->query($sql) === TRUE) {
        $msg = "✅ Usuario creado";
    } else {
        $msg = "❌ Error: " . $conn->error;
    }
}

$result = $conn->query("SELECT * FROM usuarios");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .container {
            width: auto;
            margin: 0 auto;
            padding: 20px;
            background-color: transparent;
            border: 2px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(50px);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
    <h2>Gestión de Usuarios</h2>
    <?php if(isset($msg)) echo "<p>$msg</p>"; ?>
    <form method="POST">
        Nombre: <input type="text" name="nombre" required><br>
        Usuario: <input type="text" name="usuario" required><br>
        Contraseña: <input type="password" name="password" required><br>
        Rol:
        <select name="rol">
            <option value="admin">Admin</option>
            <option value="empleado">Empleado</option>
        </select><br>
        <button type="submit">Crear Usuario</button>
    </form>

    <h3>Usuarios Registrados</h3>
    <table border="1">
        <tr><th>ID</th><th>Nombre</th><th>Usuario</th><th>Rol</th></tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td><?= $row['usuario'] ?></td>
                <td><?= $row['rol'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="dashboard_admin.php">Volver</a>
    </div>
</body>
</html>
