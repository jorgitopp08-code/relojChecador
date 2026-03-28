<?php
include 'db.php';
date_default_timezone_set('America/Bogota'); // Ajusta a tu zona horaria

if ($_POST) {
    $cedula = $_POST['cedula'];
    $accion = $_POST['accion'];
    $fecha_actual = date('Y-m-d');
    $hora_actual = date('H:i:s');

    // Verificar si el empleado existe
    $check_emp = mysqli_query($conn, "SELECT * FROM empleados WHERE cedula = '$cedula'");
    if (mysqli_num_rows($check_emp) == 0) {
        die("<script>alert('Empleado no encontrado'); window.location='index.php';</script>");
    }

    // Buscar si ya tiene un registro hoy
    $res = mysqli_query($conn, "SELECT * FROM asistencias WHERE cedula_empleado = '$cedula' AND fecha = '$fecha_actual'");
    $asistencia = mysqli_fetch_assoc($res);

    if ($accion == 'ingreso') {
        if ($asistencia) {
            echo "<script>alert('Ya registraste entrada hoy'); window.location='index.php';</script>";
        } else {
            mysqli_query($conn, "INSERT INTO asistencias (cedula_empleado, fecha, hora_ingreso) VALUES ('$cedula', '$fecha_actual', '$hora_actual')");
        }
    } else {
        if (!$asistencia) {
            echo "<script>alert('Primero debes marcar ENTRADA'); window.location='index.php';</script>";
        } else {
            // Determinar qué columna actualizar según el botón pulsado
            $columna = "";
            if ($accion == 'ini_refri') $columna = "inicio_refrigerio";
            if ($accion == 'fin_refri') $columna = "fin_refrigerio";
            if ($accion == 'salida') $columna = "hora_salida";

            mysqli_query($conn, "UPDATE asistencias SET $columna = '$hora_actual' WHERE id = " . $asistencia['id']);
        }
    }
    header("Location: index.php");
}
?>