<?php
$host = "mysql-apijorge.alwaysdata.net";
$user = "apijorge";
$pass = "clase1234";
$db = "apijorge_reloj_lavoral";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>