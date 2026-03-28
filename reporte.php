<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

$flashMessage = get_flash_message();
$cedula = normalize_cedula((string) ($_GET['cedula'] ?? ''));
$empleado = null;
$registros = [];
$error = null;

if ($cedula !== '') {
    try {
        $employeeStmt = mysqli_prepare($conn, 'SELECT cedula, nombre, cargo FROM empleados WHERE cedula = ? LIMIT 1');
        mysqli_stmt_bind_param($employeeStmt, 's', $cedula);
        mysqli_stmt_execute($employeeStmt);
        mysqli_stmt_bind_result($employeeStmt, $employeeCedula, $employeeNombre, $employeeCargo);

        if (mysqli_stmt_fetch($employeeStmt)) {
            $empleado = [
                'cedula' => $employeeCedula,
                'nombre' => $employeeNombre,
                'cargo' => $employeeCargo,
            ];
        } else {
            $error = 'No se encontro un empleado con esa cedula.';
        }
        mysqli_stmt_close($employeeStmt);

        if ($empleado !== null) {
            $recordsStmt = mysqli_prepare(
                $conn,
                'SELECT fecha, hora_ingreso, inicio_refrigerio, fin_refrigerio, hora_salida
                 FROM asistencias
                 WHERE cedula_empleado = ?
                 ORDER BY fecha DESC, hora_ingreso DESC'
            );
            mysqli_stmt_bind_param($recordsStmt, 's', $cedula);
            mysqli_stmt_execute($recordsStmt);
            mysqli_stmt_bind_result(
                $recordsStmt,
                $fecha,
                $horaIngreso,
                $inicioRefrigerio,
                $finRefrigerio,
                $horaSalida
            );

            while (mysqli_stmt_fetch($recordsStmt)) {
                $registros[] = [
                    'fecha' => $fecha,
                    'hora_ingreso' => $horaIngreso,
                    'inicio_refrigerio' => $inicioRefrigerio,
                    'fin_refrigerio' => $finRefrigerio,
                    'hora_salida' => $horaSalida,
                ];
            }
            mysqli_stmt_close($recordsStmt);
        }
    } catch (Throwable $exception) {
        error_log($exception->getMessage());
        $error = 'No fue posible consultar los registros en este momento.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reporte de Asistencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">Historial de Jornadas</h1>
                <p class="text-muted mb-0">Consulta unicamente los registros asociados a una cedula.</p>
            </div>
            <a href="index.php" class="btn btn-secondary">Volver al Reloj</a>
        </div>

        <?php if ($flashMessage): ?>
            <div class="alert alert-<?= e($flashMessage['type']) ?>" role="alert">
                <?= e($flashMessage['text']) ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label for="cedula" class="form-label">Cedula</label>
                        <input
                            type="text"
                            id="cedula"
                            name="cedula"
                            class="form-control"
                            inputmode="numeric"
                            pattern="\d{5,20}"
                            maxlength="20"
                            value="<?= e($cedula) ?>"
                            placeholder="Ingresa la cedula a consultar"
                            required
                        >
                    </div>
                    <div class="col-md-4 d-grid">
                        <button type="submit" class="btn btn-primary">Consultar registros</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($cedula === ''): ?>
            <div class="alert alert-info">Ingresa una cedula para consultar los registros.</div>
        <?php elseif ($error !== null): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php elseif ($empleado !== null): ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-4">
                        <h2 class="h5 mb-1"><?= e($empleado['nombre']) ?></h2>
                        <p class="text-muted mb-0">
                            Cedula: <?= e($empleado['cedula']) ?>
                            <?php if (!empty($empleado['cargo'])): ?>
                                | Cargo: <?= e($empleado['cargo']) ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <?php if ($registros === []): ?>
                        <div class="alert alert-warning mb-0">Este empleado aun no tiene jornadas registradas.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Ingreso</th>
                                        <th>Inic. Refri</th>
                                        <th>Fin Refri</th>
                                        <th>Salida</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($registros as $registro): ?>
                                        <tr>
                                            <td><?= e($registro['fecha']) ?></td>
                                            <td class="text-success fw-bold"><?= e($registro['hora_ingreso'] ?: '--:--') ?></td>
                                            <td class="text-warning"><?= e($registro['inicio_refrigerio'] ?: '--:--') ?></td>
                                            <td class="text-info"><?= e($registro['fin_refrigerio'] ?: '--:--') ?></td>
                                            <td class="text-danger fw-bold"><?= e($registro['hora_salida'] ?: '--:--') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
