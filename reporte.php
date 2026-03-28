<style>
    body { background: #f8fafc; font-family: 'Inter', sans-serif; padding-top: 50px; }
    .table { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    .table thead { background: #4f46e5; color: white; border: none; }
    h2 { font-weight: 800; color: #1e293b; margin-bottom: 30px; }
</style>
<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Historial de Jornadas</h2>
    <a href="index.php" class="btn btn-secondary mb-3">Volver al Reloj</a>
    
    <table class="table table-hover table-bordered shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Fecha</th>
                <th>Empleado</th>
                <th>Ingreso</th>
                <th>Inic. Refri</th>
                <th>Fin Refri</th>
                <th>Salida</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT a.*, e.nombre FROM asistencias a 
                    JOIN empleados e ON a.cedula_empleado = e.cedula 
                    ORDER BY a.fecha DESC, a.hora_ingreso DESC";
            $res = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($res)): ?>
            <tr>
                <td><?= $row['fecha'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td class="text-success fw-bold"><?= $row['hora_ingreso'] ?></td>
                <td class="text-warning"><?= $row['inicio_refrigerio'] ?? '--:--' ?></td>
                <td class="text-info"><?= $row['fin_refrigerio'] ?? '--:--' ?></td>
                <td class="text-danger fw-bold"><?= $row['hora_salida'] ?? '--:--' ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>