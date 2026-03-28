<?php include("db.php");
if(!isset($_SESSION['user'])) header("Location:index.php");
?>
<h2>Dashboard</h2>
<a href="empleados.php">Empleados</a><br>
<a href="marcar.php">Marcar</a><br>
<a href="historial.php">Historial</a><br>
<a href="logout.php">Salir</a>
