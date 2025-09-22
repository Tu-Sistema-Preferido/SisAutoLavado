<?php
session_start();
include 'conexion.php';

// Verificar que sea admin
if($_SESSION['rol'] != 'admin'){
    header("Location: index.php");
    exit();
}

$result = $conn->query("SELECT * FROM servicios");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Servicios - Autolavado</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h2>Servicios</h2>
    <a href="agregar_servicio.php">➕ Agregar Servicio</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Servicio</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        <?php while($row = $result->fetch_assoc()){ ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['descripcion'] ?></td>
            <td>$<?= number_format($row['precio'], 2) ?></td>
            <td>
                <a href="editar_servicio.php?id=<?= $row['id'] ?>">✏️ Editar</a> | 
                <a href="eliminar_servicio.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Seguro?')">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
