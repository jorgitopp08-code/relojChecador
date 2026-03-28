<?php
session_start(); // Necesario para mostrar mensajes de éxito/error
include 'db.php';
date_default_timezone_set('America/Bogota');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cedula'], $_POST['accion'])) {
    
    $cedula = trim($_POST['cedula']);
    $accion = $_POST['accion'];
    $fecha_actual = date('Y-m-d');
    $hora_actual = date('H:i:s');

    // 1. Verificar si el empleado existe con Prepared Statements
    $stmt = $conn->prepare("SELECT nombre FROM empleados WHERE cedula = ?");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $result = $stmt->get_result();
    $empleado = $result->fetch_assoc();

    if (!$empleado) {
        $_SESSION['mensaje'] = "Error: Cédula no registrada.";
        $_SESSION['tipo_mensaje'] = "danger";
        header("Location: index.php");
        exit();
    }

    // 2. Buscar si ya existe un registro para hoy
    $stmt = $conn->prepare("SELECT * FROM asistencias WHERE cedula_empleado = ? AND fecha = ?");
    $stmt->bind_param("ss", $cedula, $fecha_actual);
    $stmt->execute();
    $asistencia = $stmt->get_result()->fetch_assoc();

    // 3. Lógica Pro: Procesar según la acción
    if ($accion === 'ingreso') {
        if ($asistencia) {
            $_SESSION['mensaje'] = "{$empleado['nombre']}, ya registraste tu ingreso hoy a las {$asistencia['hora_ingreso']}.";
            $_SESSION['tipo_mensaje'] = "warning";
        } else {
            $stmt = $conn->prepare("INSERT INTO asistencias (cedula_empleado, fecha, hora_ingreso) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $cedula, $fecha_actual, $hora_actual);
            if ($stmt->execute()) {
                $_SESSION['mensaje'] = "Ingreso registrado con éxito. ¡Hola, {$empleado['nombre']}!";
                $_SESSION['tipo_mensaje'] = "success";
            }
        }
    } else {
        // Acciones que requieren que ya exista un ingreso (refri, salida)
        if (!$asistencia) {
            $_SESSION['mensaje'] = "Error: Debes registrar primero la ENTRADA.";
            $_SESSION['tipo_mensaje'] = "danger";
        } else {
            // Mapeo de columnas según el botón
            $columnas_permitidas = [
                'ini_refri' => 'inicio_refrigerio',
                'fin_refri' => 'fin_refrigerio',
                'salida'    => 'hora_salida'
            ];

            if (array_key_exists($accion, $columnas_permitidas)) {
                $columna = $columnas_permitidas[$accion];

                // Verificar si ya se marcó esa hora anteriormente
                if (!empty($asistencia[$columna])) {
                    $_SESSION['mensaje'] = "Esta acción ya fue registrada anteriormente.";
                    $_SESSION['tipo_mensaje'] = "info";
                } else {
                    $stmt = $conn->prepare("UPDATE asistencias SET $columna = ? WHERE id = ?");
                    $stmt->bind_param("si", $hora_actual, $asistencia['id']);
                    $stmt->execute();
                    $_SESSION['mensaje'] = "Registro actualizado correctamente.";
                    $_SESSION['tipo_mensaje'] = "success";
                }
            }
        }
    }

    header("Location: index.php");
    exit();
}