<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Asistencia | Enterprise</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-body: #f8fafc; /* Fondo claro sutil */
            --bg-card: #ffffff;
            --primary: #4f46e5; /* Indigo vibrante */
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #0ea5e9;
            --danger: #ef4444;
            --text-main: #1e293b;
        }

        /* Si prefieres mantener un modo oscuro pero NO NEGRO, usa estos: */
        body.dark-theme {
            --bg-body: #0f172a; /* Azul noche profundo */
            --bg-card: #1e293b; /* Azul pizarra */
            --text-main: #f1f5f9;
            --secondary: #94a3b8;
        }

        body { 
            background-color: var(--bg-body); 
            color: var(--text-main); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            padding: 40px 20px;
            transition: all 0.3s ease;
        }

        .container-pro {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Encabezado con estilo de Dashboard */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .header-title h2 {
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--text-main);
        }

        /* Contenedor de la tabla con efecto Glassmorphism suave */
        .table-container {
            background: var(--bg-card);
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* Estilo de la tabla */
        .table { 
            border-collapse: separate;
            border-spacing: 0 12px;
            margin-top: -12px;
        }

        .table thead th {
            border: none;
            color: var(--secondary);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 20px;
        }

        .table tbody tr {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .table tbody tr:hover {
            transform: translateY(-2px);
        }

        .table td { 
            background: var(--bg-card);
            border-top: 1px solid rgba(0,0,0,0.02);
            border-bottom: 1px solid rgba(0,0,0,0.02);
            padding: 18px 20px;
            vertical-align: middle;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .table td:first-child { border-left: 1px solid rgba(0,0,0,0.02); border-radius: 16px 0 0 16px; }
        .table td:last-child { border-right: 1px solid rgba(0,0,0,0.02); border-radius: 0 16px 16px 0; }

        /* Badges de tiempo Modernos */
        .time-badge {
            font-weight: 600;
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-entry { background: #ecfdf5; color: #065f46; }
        .badge-lunch { background: #fffbeb; color: #92400e; }
        .badge-return { background: #f0f9ff; color: #075985; }
        .badge-exit { background: #fef2f2; color: #991b1b; }

        /* Botón Pro */
        .btn-back {
            background: var(--primary);
            color: white;
            border-radius: 14px;
            font-weight: 600;
            text-decoration: none;
            padding: 12px 24px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #4338ca;
            color: white;
            transform: scale(1.05);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar-circle {
            width: 38px;
            height: 38px;
            background: #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary);
            font-weight: bold;
        }
    </style>
</head>
<body class="dark-theme"> <!-- Borra 'dark-theme' para modo claro -->

    <div class="container-pro">
        <div class="header-section">
            <div class="header-title">
                <p class="text-uppercase small fw-bold mb-1" style="color: var(--primary); letter-spacing: 2px;">Administración</p>
                <h2>Jornadas Laborales</h2>
            </div>
            <a href="index.php" class="btn-back">
                <i class="ph-bold ph-caret-left"></i> Panel Principal
            </a>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha de Registro</th>
                        <th>Colaborador</th>
                        <th>Entrada</th>
                        <th>Almuerzo</th>
                        <th>Retorno</th>
                        <th>Salida</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT a.*, e.nombre FROM asistencias a 
                            JOIN empleados e ON a.cedula_empleado = e.cedula 
                            ORDER BY a.fecha DESC, a.hora_ingreso DESC";
                    $res = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($res)): 
                        $inicial = strtoupper(substr($row['nombre'], 0, 1));
                    ?>
                    <tr>
                        <td class="fw-bold" style="color: var(--secondary);"><?= date('d M, Y', strtotime($row['fecha'])) ?></td>
                        <td>
                            <div class="user-info">
                                <div class="avatar-circle"><?= $inicial ?></div>
                                <div class="fw-bold"><?= $row['nombre'] ?></div>
                            </div>
                        </td>
                        <td>
                            <span class="time-badge badge-entry">
                                <i class="ph-fill ph-clock"></i> <?= $row['hora_ingreso'] ?>
                            </span>
                        </td>
                        <td>
                            <span class="time-badge badge-lunch">
                                <?= $row['inicio_refrigerio'] ? $row['inicio_refrigerio'] : '--:--' ?>
                            </span>
                        </td>
                        <td>
                            <span class="time-badge badge-return">
                                <?= $row['fin_refrigerio'] ? $row['fin_refrigerio'] : '--:--' ?>
                            </span>
                        </td>
                        <td>
                            <span class="time-badge badge-exit">
                                <i class="ph-fill ph-door-open"></i> <?= $row['hora_salida'] ? $row['hora_salida'] : '--:--' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>