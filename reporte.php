<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log de Actividad | Enterprise</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos y Fuentes -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        /* AQUÍ ESTÁ EL BLOQUE QUE PREGUNTASTE + MEJORAS */
        body { 
            background: #0a0a0b; 
            color: #fff; 
            font-family: 'Inter', sans-serif; 
            padding: 40px;
        }

        .container-pro {
            max-width: 1100px;
            margin: 0 auto;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        /* Estilo para la Tabla Pro */
        .table-container {
            background: #111113;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .table { 
            background: transparent; 
            color: #fff; 
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table th { 
            border: none;
            border-bottom: 1px solid #222; 
            color: #a1a1aa; 
            font-size: 11px; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            padding: 15px;
        }

        .table td { 
            background: rgba(255,255,255,0.02);
            border: none;
            padding: 15px;
            vertical-align: middle;
            font-size: 14px;
        }

        /* Bordes redondeados para las filas */
        .table tr td:first-child { border-radius: 10px 0 0 10px; }
        .table tr td:last-child { border-radius: 0 10px 10px 0; }

        /* Etiquetas de tiempo con estilo */
        .time-badge {
            font-family: monospace;
            padding: 4px 8px;
            border-radius: 6px;
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        .btn-back {
            background: #fff;
            color: #000;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            padding: 10px 20px;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            background: #e2e2e2;
            transform: translateX(-5px);
        }

        /* Decoración de fondo */
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 0);
            background-size: 30px 30px;
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>
<body>

    <div class="container-pro">
        <div class="header-section">
            <div>
                <h2 class="fw-800 mb-1 text-white">Log de Actividad</h2>
                <p class="text-secondary small m-0">Registro en tiempo real de la jornada laboral</p>
            </div>
            <a href="index.php" class="btn-back">
                <i class="ph ph-arrow-left"></i> Volver al Terminal
            </a>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Colaborador</th>
                        <th>Entrada</th>
                        <th>Inicio Refri</th>
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
                        <td class="fw-bold"><?= $row['fecha'] ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="ph ph-user-circle fs-4 me-2 text-secondary"></i>
                                <?= $row['nombre'] ?>
                            </div>
                        </td>
                        <td><span class="time-badge text-success border border-success border-opacity-25"><?= $row['hora_ingreso'] ?></span></td>
                        <td><span class="time-badge text-warning opacity-75"><?= $row['inicio_refrigerio'] ?? '--:--' ?></span></td>
                        <td><span class="time-badge text-info opacity-75"><?= $row['fin_refrigerio'] ?? '--:--' ?></span></td>
                        <td><span class="time-badge text-danger border border-danger border-opacity-25"><?= $row['hora_salida'] ?? '--:--' ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>