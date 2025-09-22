<?php
session_start();
include("includes/conexion.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

// Guardar auto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = $_POST['placa'];
    $tipo_auto = $_POST['tipo_auto'];
    $servicio = $_POST['servicio'];
    $usuario_id = $_SESSION['usuario'];

    $sql = "INSERT INTO autos (placa, tipo_auto_id, servicio_id, hora_entrada, usuario_id) 
            VALUES ('$placa', '$tipo_auto', '$servicio', NOW(), 
                   (SELECT id FROM usuarios WHERE usuario='$usuario_id'))";

    if ($conn->query($sql) === TRUE) {
        $msg = "✅ Auto registrado correctamente";
    } else {
        $msg = "❌ Error: " . $conn->error;
    }
}

// Consultar autos del día actual
$result = $conn->query("SELECT a.id, a.placa, t.nombre AS tipo, s.nombre AS servicio, 
                               a.hora_entrada
                        FROM autos a
                        JOIN tipo_autos t ON a.tipo_auto_id = t.id
                        JOIN servicios s ON a.servicio_id = s.id
                        WHERE DATE(a.hora_entrada) = CURDATE()
                        ORDER BY a.id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" lang="es">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Autos</title>
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
    <label for="fecha"><h2>REGISTO DE AUTOS</h2><h3>FECHA</h3> &#160&#160</label><input type="date" id="fecha" name="fecha" required><br>
        <br>
    <?php if(isset($msg)) echo "<p>$msg</p>"; ?>

    <!-- Formulario -->
    <form method="POST">
        Placa: <input type="text" name="placa" ><br>
        Tipo de Auto:
        <select name="tipo_auto" required>
            <?php
            $tipos = $conn->query("SELECT * FROM tipo_autos");
            while($row = $tipos->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['nombre']}</option>";
            }
            ?>
        </select><br>
        Servicio:
        <select name="servicio" required>
            <?php
            $servicios = $conn->query("SELECT * FROM servicios");
            while($row = $servicios->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['nombre']} - $ {$row['precio']}</option>";
            }
            ?>
        </select><br>
        <button type="submit">Registrar Auto</button>
    </form>

    <!-- Tabla -->
    <h3>Autos Registrados Hoy</h3>
    <!--button onclick="imprimirTabla()">🖨️ Imprimir Tabla Completa</button-->

    <table id="tablaAutos" border="1">
        <tr>
            <th>ID</th><th>Placa</th><th>Tipo</th><th>Servicio</th><th>Entrada</th><th>Acción</th>
        </tr>
        <?php 
        // volvemos a ejecutar la consulta por seguridad
        $result = $conn->query("SELECT a.id, a.placa, t.nombre AS tipo, s.nombre AS servicio, 
                                       a.hora_entrada
                                FROM autos a
                                JOIN tipo_autos t ON a.tipo_auto_id = t.id
                                JOIN servicios s ON a.servicio_id = s.id
                                WHERE DATE(a.hora_entrada) = CURDATE()
                                ORDER BY a.id DESC");
        while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['placa'] ?></td>
                <td><?= $row['tipo'] ?></td>
                <td><?= $row['servicio'] ?></td>
                <td><?= $row['hora_entrada'] ?></td>
                <td>
                    <button onclick="imprimirAuto('<?= $row['id'] ?>','<?= $row['placa'] ?>','<?= $row['tipo'] ?>','<?= $row['servicio'] ?>','<?= $row['hora_entrada'] ?>')">🖨️ Ticket</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="dashboard_admin.php">Volver</a>
    </div>
    <script>
    // Imprimir tabla completa
    function imprimirTabla() {
        var contenido = document.getElementById("tablaAutos").outerHTML;
        var ventana = window.open("", "", "width=800,height=600");
        ventana.document.write("<h2>Autos Registrados (<?= date("d/m/Y") ?>)</h2>");
        ventana.document.write(contenido);
        ventana.document.close();
        ventana.print();
    }

    // Imprimir ticket por auto
    function imprimirAuto(id, placa, tipo, servicio, entrada) {
        var ventana = window.open("", "", "width=400,height=600");
        ventana.document.write("<style>body{font-family: monospace;}</style>");
        ventana.document.write("<h3 style='text-align:center;'>🚗 Ticket Auto Lavado</h3><hr>");
        ventana.document.write("<p><strong>ID:</strong> " + id + "</p>");
        ventana.document.write("<p><strong>Placa:</strong> " + placa + "</p>");
        ventana.document.write("<p><strong>Tipo:</strong> " + tipo + "</p>");
        ventana.document.write("<p><strong>Servicio:</strong> " + servicio + "</p>");
        ventana.document.write("<p><strong>Entrada:</strong> " + entrada + "</p>");
        ventana.document.write("<hr><p style='text-align:center;'>Gracias por su visita</p>");
        ventana.document.close();
        ventana.print();
    }

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
