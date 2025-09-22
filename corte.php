<?php
session_start();
include("includes/conexion.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

// Fecha fija inicial = hoy, pero modificable con el selector
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date("Y-m-d");

// Total de servicios por fecha
$ventas_servicios = $conn->query("SELECT SUM(s.precio) as total 
                                  FROM autos a 
                                  JOIN servicios s ON a.servicio_id = s.id
                                  WHERE DATE(a.hora_entrada) = '$fecha'")->fetch_assoc();

// Total de productos por fecha
$ventas_productos = $conn->query("SELECT SUM(total) as total 
                                  FROM ventas
                                  WHERE DATE(fecha) = '$fecha'")->fetch_assoc();

// Listado de usuarios que registraron autos (servicios) en la fecha
$usuarios_servicios = $conn->query("SELECT u.usuario, COUNT(a.id) as autos, SUM(s.precio) as total
                                    FROM autos a
                                    JOIN usuarios u ON a.usuario_id = u.id
                                    JOIN servicios s ON a.servicio_id = s.id
                                    WHERE DATE(a.hora_entrada) = '$fecha'
                                    GROUP BY u.usuario");

// Listado de usuarios que registraron ventas de productos en la fecha
$usuarios_productos = $conn->query("SELECT u.usuario, COUNT(v.id) as ventas, SUM(v.total) as total
                                    FROM ventas v
                                    JOIN usuarios u ON v.usuario_id = u.id
                                    WHERE DATE(v.fecha) = '$fecha'
                                    GROUP BY u.usuario");

// Autos ingresados en esa fecha
$autos_ingresados = $conn->query("SELECT a.placa, t.nombre AS tipo, s.nombre AS servicio, 
                                         s.precio, u.usuario, a.hora_entrada
                                  FROM autos a
                                  JOIN tipo_autos t ON a.tipo_auto_id = t.id
                                  JOIN servicios s ON a.servicio_id = s.id
                                  JOIN usuarios u ON a.usuario_id = u.id
                                  WHERE DATE(a.hora_entrada) = '$fecha'
                                  ORDER BY a.hora_entrada ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corte de Caja</title>
    <link rel="stylesheet" href="css/estilos.css">

    <style>
        .main-content {
            margin-left: 210px;
            padding: 5px;
            width: calc(85% - 210px);
            background-color: transparent;
            border: 2px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(50px);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }

        .container {
            width: auto;
            margin: 0 auto;
            padding: 20px;
            background-color: transparent;
            border: 2px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(50px);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
    <h2>Corte de Caja</h2>
        <!-- Formulario para seleccionar fecha -->
        <form method="POST">
            <label>Seleccionar fecha: </label>
            <label for="fecha">Fecha &#160&#160</label><input type="date" id="fecha" name="fecha" required><br>
        <br>
            <button type="submit">Filtrar</button>
        </form>
        <br>

        <div class="container">
            <p><strong>Fecha seleccionada:</strong> <?= date("d/m/Y", strtotime($fecha)) ?></p>
            <p>Total Servicios: $<?= $ventas_servicios['total'] ?? 0 ?></p>
            <p>Total Productos: $<?= $ventas_productos['total'] ?? 0 ?></p>
            <p><strong>Total General: $<?= ($ventas_servicios['total'] + $ventas_productos['total']) ?></strong></p>
            <br>

            <!-- Usuarios que registraron autos -->
            <h3>Usuarios (Servicios - Autos)</h3>
            <table>
                <tr>
                    <th>Usuario</th><th>Autos Registrados</th><th>Total $</th>
                </tr>
                <?php while($row = $usuarios_servicios->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['usuario'] ?></td>
                    <td><?= $row['autos'] ?></td>
                    <td>$<?= $row['total'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <!-- Usuarios que registraron productos -->
            <h3>Usuarios (Ventas - Productos)</h3>
            <table>
                <tr>
                    <th>Usuario</th><th>Ventas Realizadas</th><th>Total $</th>
                </tr>
                <?php while($row = $usuarios_productos->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['usuario'] ?></td>
                    <td><?= $row['ventas'] ?></td>
                    <td>$<?= $row['total'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <!-- Autos ingresados -->
            <h3>Autos Ingresados</h3>
            <table>
                <tr>
                    <th>Placa</th><th>Tipo</th><th>Servicio</th><th>Precio</th><th>Usuario</th><th>Hora Entrada</th>
                </tr>
                <?php while($row = $autos_ingresados->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['placa'] ?></td>
                    <td><?= $row['tipo'] ?></td>
                    <td><?= $row['servicio'] ?></td>
                    <td>$<?= $row['precio'] ?></td>
                    <td><?= $row['usuario'] ?></td>
                    <td><?= $row['hora_entrada'] ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <br>
            <a href="dashboard_admin.php">Volver</a>
        </div>
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
