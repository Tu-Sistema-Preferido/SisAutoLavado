<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'empleado') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Empleado</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        a {
            color: #fff;
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

        .main-content {
            margin-left: 210px;
            padding: 5px;
            width: calc(100% - 210px);
            background-color: transparent;
            border: 2px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(50px);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }

        .sidebar {
            width: 200px;
            background-color: transparent;
            backdrop-filter: blur(50px);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: #ecf0f1;
            padding: 0;
            height: 100vh;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
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
        <h1>Bienvenido Empleado: <?= $_SESSION['usuario'] ?></h1><hr><br>
        <a href="autos.php">🚗 Registro de Autos</a><hr><br>
        <a href="ventas.php">🛒 Ventas</a><hr><br>
        <a href="logout.php">Salir</a><hr><br>
    </div>
</body>
</html>
