<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!validate_csrf_token($_POST['csrf_token'] ?? null)) {
    redirect_with_message('index.php', 'La sesion expiro. Intenta de nuevo.', 'danger');
}

$cedula = normalize_cedula((string) ($_POST['cedula'] ?? ''));
$accion = (string) ($_POST['accion'] ?? '');

if ($cedula === '' || strlen($cedula) > 20) {
    redirect_with_message('index.php', 'Debes ingresar una cedula valida.', 'danger');
}

if (!is_valid_action($accion)) {
    redirect_with_message('index.php', 'La accion solicitada no es valida.', 'danger');
}

$fechaActual = date('Y-m-d');
$horaActual = date('H:i:s');

try {
    $employeeStmt = mysqli_prepare($conn, 'SELECT cedula, nombre FROM empleados WHERE cedula = ? LIMIT 1');
    mysqli_stmt_bind_param($employeeStmt, 's', $cedula);
    mysqli_stmt_execute($employeeStmt);
    mysqli_stmt_bind_result($employeeStmt, $employeeCedula, $employeeNombre);
    $employeeFound = mysqli_stmt_fetch($employeeStmt);
    mysqli_stmt_close($employeeStmt);

    if (!$employeeFound) {
        redirect_with_message('index.php', 'Empleado no encontrado.', 'danger');
    }

    $attendanceStmt = mysqli_prepare(
        $conn,
        'SELECT id, hora_ingreso, inicio_refrigerio, fin_refrigerio, hora_salida
         FROM asistencias
         WHERE cedula_empleado = ? AND fecha = ?
         LIMIT 1'
    );
    mysqli_stmt_bind_param($attendanceStmt, 'ss', $cedula, $fechaActual);
    mysqli_stmt_execute($attendanceStmt);
    mysqli_stmt_bind_result(
        $attendanceStmt,
        $attendanceId,
        $horaIngreso,
        $inicioRefrigerio,
        $finRefrigerio,
        $horaSalida
    );

    $asistencia = null;
    if (mysqli_stmt_fetch($attendanceStmt)) {
        $asistencia = [
            'id' => $attendanceId,
            'hora_ingreso' => $horaIngreso,
            'inicio_refrigerio' => $inicioRefrigerio,
            'fin_refrigerio' => $finRefrigerio,
            'hora_salida' => $horaSalida,
        ];
    }
    mysqli_stmt_close($attendanceStmt);

    if ($accion === 'ingreso') {
        if ($asistencia !== null) {
            redirect_with_message('index.php', 'Ya registraste tu entrada de hoy.', 'warning');
        }

        $insertStmt = mysqli_prepare(
            $conn,
            'INSERT INTO asistencias (cedula_empleado, fecha, hora_ingreso) VALUES (?, ?, ?)'
        );
        mysqli_stmt_bind_param($insertStmt, 'sss', $cedula, $fechaActual, $horaActual);
        mysqli_stmt_execute($insertStmt);
        mysqli_stmt_close($insertStmt);

        redirect_with_message('index.php', 'Entrada registrada correctamente.', 'success');
    }

    if ($asistencia === null) {
        redirect_with_message('index.php', 'Primero debes marcar tu entrada.', 'warning');
    }

    if ($accion === 'ini_refri') {
        if (!empty($asistencia['inicio_refrigerio'])) {
            redirect_with_message('index.php', 'El inicio de refrigerio ya fue registrado.', 'warning');
        }

        if (!empty($asistencia['hora_salida'])) {
            redirect_with_message('index.php', 'No puedes iniciar refrigerio despues de la salida.', 'danger');
        }
    }

    if ($accion === 'fin_refri') {
        if (empty($asistencia['inicio_refrigerio'])) {
            redirect_with_message('index.php', 'Debes registrar primero el inicio de refrigerio.', 'warning');
        }

        if (!empty($asistencia['fin_refrigerio'])) {
            redirect_with_message('index.php', 'El fin de refrigerio ya fue registrado.', 'warning');
        }

        if (!empty($asistencia['hora_salida'])) {
            redirect_with_message('index.php', 'No puedes finalizar refrigerio despues de la salida.', 'danger');
        }
    }

    if ($accion === 'salida') {
        if (!empty($asistencia['hora_salida'])) {
            redirect_with_message('index.php', 'La salida ya fue registrada.', 'warning');
        }

        if (!empty($asistencia['inicio_refrigerio']) && empty($asistencia['fin_refrigerio'])) {
            redirect_with_message('index.php', 'Debes cerrar el refrigerio antes de registrar la salida.', 'warning');
        }
    }

    $columna = action_to_column($accion);
    if ($columna === null) {
        redirect_with_message('index.php', 'La accion solicitada no es valida.', 'danger');
    }

    $updateStmt = mysqli_prepare($conn, "UPDATE asistencias SET {$columna} = ? WHERE id = ?");
    mysqli_stmt_bind_param($updateStmt, 'si', $horaActual, $asistencia['id']);
    mysqli_stmt_execute($updateStmt);
    mysqli_stmt_close($updateStmt);

    redirect_with_message(
        'index.php',
        'Se registro correctamente la ' . action_label($accion) . '.',
        'success'
    );
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    redirect_with_message('index.php', 'Ocurrio un error al guardar el registro.', 'danger');
}
