<?php
$host = "sql10.freesqldatabase.com";
$user = "sql10799568"; 
$pass = "6iKKFD4uJI";     
$db = "sql10799568";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
