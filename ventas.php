<?php
session_start();
include("includes/conexion.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $usuario_id = $_SESSION['usuario'];

    // Obtener precio
    $precio = $conn->query("SELECT precio, stock FROM productos WHERE id=$producto")->fetch_assoc();
    $total = $precio['precio'] * $cantidad;

    if ($cantidad <= $precio['stock']) {
        $conn->query("INSERT INTO ventas (producto_id, cantidad, total, fecha, usuario_id)
                      VALUES ($producto, $cantidad, $total, NOW(), 
                      (SELECT id FROM usuarios WHERE usuario='$usuario_id'))");
        $conn->query("UPDATE productos SET stock = stock - $cantidad WHERE id=$producto");
        $msg = "✅ Venta registrada";
    } else {
        $msg = "❌ Stock insuficiente";
    }
}

$result = $conn->query("SELECT * FROM productos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
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
    <h2>Registro de Ventas</h2>
    <?php if(isset($msg)) echo "<p>$msg</p>"; ?>
    <form method="POST">
        Producto:
        <select name="producto">
            <?php while($row = $result->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= $row['nombre'] ?> ($<?= $row['precio'] ?> - Stock: <?= $row['stock'] ?>)</option>
            <?php endwhile; ?>
        </select><br>
        Cantidad: <input type="number" name="cantidad" required><br>
        <button type="submit">Registrar Venta</button>
    </form>
    <br>
    <a href="dashboard_empleado.php">Volver</a>
    </div>
    <script>
        // FUNCIÓN PARA MOSTRAR LA HORA ACTUAL
        function actualizarHora() {
            const ahora = new Date();
            const horas = ahora.getHours().toString().padStart(2, '0');
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            const segundos = ahora.getSeconds().toString().padStart(2, '0');
            const horaActual = `${horas}:${minutos}:${segundos}`;
            document.getElementById('current-time').innerText = horaActual;
        }
        setInterval(actualizarHora, 1000);

        // FUNCIÓN PARA ESTABLECER LA FECHA ACTUAL EN EL CAMPO DE FECHA
        document.addEventListener('DOMContentLoaded', (event) => {
            const inputFecha = document.getElementById('fecha');
            const hoy = new Date();
            const dia = String(hoy.getDate()).padStart(2, '0');
            const mes = String(hoy.getMonth() + 1).padStart(2, '0'); // Enero es 0!
            const año = hoy.getFullYear();
            
            const fechaActual = `${año}-${mes}-${dia}`;
            inputFecha.value = fechaActual;
        });

    </script>
</body>
</html>
