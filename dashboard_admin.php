<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" lang="es">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        body{
            display: flex;
        }
        a {
            color: #FFFFFF;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 2px;
            margin: 7px;
            background-color: transparent;
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(70px);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
            transition: background-color 0.3s, color 0.3s, transform 0.3s;
        }

        a:hover {
            background-color: #fff;
            color: #121212;
            transform: scale(1.05);
        }

        a:active {
            background-color: #333;
        }

        p {
            text-align: center;
            margin: 10px 0 0;
        }

        img {
            width: 5%;
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
    </style>
</head>
<body>
    <div class="container"><hr><br>
        <h1>Bienvenido Admin: <?= $_SESSION['usuario'] ?></h1><hr><br>
        <a href="autos.php">🚗 Registro de Autos</a><hr><br>
        <a href="productos.php">📦 Productos</a><hr><br>
        <a href="ventas.php">🛒 Ventas</a><hr><br>
        <a href="corte.php">💰 Corte de Caja</a><hr><br>
        <a href="usuarios.php">👤 Usuarios</a><hr><br>
        <!--li><a href="servicios.php">🚗 Servicios</a></li-->
        <a href="logout.php">Salir</a><hr><br>
    </div>
</body>
</html>
