<?php
$conn = new mysqli("mysql-apijorge.alwaysdata.net", "apijorge", "clase1234", "apijorge_reloj_checador");
if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}
session_start();
?>