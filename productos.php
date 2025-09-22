<?php
session_start();
include("includes/conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

// Agregar producto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    $sql = "INSERT INTO productos (nombre, precio, stock) VALUES ('$nombre', '$precio', '$stock')";
    if ($conn->query($sql) === TRUE) {
        $msg = "✅ Producto agregado";
    } else {
        $msg = "❌ Error: " . $conn->error;
    }
}

$result = $conn->query("SELECT * FROM productos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
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
    <h2>Gestión de Productos</h2>
    <?php if(isset($msg)) echo "<p>$msg</p>"; ?>
    <form method="POST">
        Nombre: <input type="text" name="nombre" required><br>
        Precio: <input type="number" step="0.01" name="precio" required><br>
        Stock: <input type="number" name="stock" required><br>
        <button type="submit">Agregar</button>
    </form>

    <h3>Lista de Productos</h3>
    <table border="1">
        <tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th></tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td>$<?= $row['precio'] ?></td>
                <td><?= $row['stock'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="dashboard_admin.php">Volver</a>
    </div>
</body>
</html>
