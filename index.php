<?php 
include 'db.php';
session_start();

// --- LÓGICA DE REGISTRO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_empleado'])) {
    $ced = trim($_POST['cedula_new']);
    $nom = trim($_POST['nombre_new']);
    $car = trim($_POST['cargo_new']);
    
    $stmt = $conn->prepare("INSERT INTO empleados (cedula, nombre, cargo) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ced, $nom, $car);
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Registro exitoso de $nom";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error: Cédula ya existente";
        $_SESSION['tipo_mensaje'] = "danger";
    }
    header("Location: index.php"); exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System | Enterprise</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --text-muted: #94a3b8;
            --accent: #cbd5e1;
        }

        body {
            background: var(--bg-gradient);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: #f8fafc;
        }

        /* Botón de Registro Muted */
        .btn-add-floating {
            position: fixed;
            top: 30px;
            left: 30px;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            color: var(--text-muted);
            padding: 10px 18px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-add-floating:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-color: rgba(255,255,255,0.2);
        }

        /* Tarjeta de Cristal Sobria */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: 40px;
            padding: 60px 50px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.5);
            text-align: center;
        }

        .fecha-top {
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        #reloj {
            font-size: 4.5rem;
            font-weight: 800;
            margin-bottom: 30px;
            letter-spacing: -3px;
            color: white;
        }

        /* Inputs Integrados */
        .input-pro {
            background: rgba(0, 0, 0, 0.2) !important;
            border: 1px solid var(--glass-border) !important;
            border-radius: 16px !important;
            color: white !important;
            padding: 14px !important;
            text-align: center;
            font-size: 1.1rem;
            margin-bottom: 30px;
            transition: 0.3s;
        }

        .input-pro:focus {
            border-color: rgba(255,255,255,0.3) !important;
            box-shadow: none !important;
        }

        /* Botones con Colores Desaturados (Muted) */
        .btn-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .btn-action {
            padding: 16px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.05);
            font-weight: 600;
            font-size: 0.8rem;
            transition: 0.3s;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-in { background: #064e3b; grid-column: span 2; } /* Verde bosque oscuro */
        .btn-lunch-start { background: #78350f; } /* Ambar tierra */
        .btn-lunch-end { background: #0c4a6e; } /* Azul profundo */
        .btn-out { background: #7f1d1d; grid-column: span 2; } /* Rojo vino oscuro */

        .btn-action:hover {
            filter: brightness(1.3);
            transform: translateY(-2px);
        }

        /* Notificación Minimalista */
        .toast-minimal {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            color: #0f172a;
            padding: 12px 24px;
            border-radius: 100px;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 2000;
        }

        .report-link {
            display: block;
            margin-top: 40px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .report-link:hover { color: white; }

        /* Modal Oscuro */
        .modal-content {
            background: #1e293b;
            color: white;
            border: 1px solid var(--glass-border);
            border-radius: 28px;
        }
        .form-control {
            background: rgba(0,0,0,0.2);
            border: 1px solid var(--glass-border);
            color: white;
        }
        .form-control:focus { background: rgba(0,0,0,0.3); color: white; border-color: #444; }
    </style>
</head>
<body>

    <!-- Botón Registro -->
    <a href="#" class="btn-add-floating" data-bs-toggle="modal" data-bs-target="#modalUser">
        <i class="ph-bold ph-plus"></i> Añadir Colaborador
    </a>

    <!-- Notificación -->
    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="toast-minimal">
            <i class="ph-fill ph-check-circle" style="color: #10b981;"></i>
            <?= $_SESSION['mensaje'] ?>
        </div>
        <script>setTimeout(() => document.querySelector('.toast-minimal').remove(), 3000);</script>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="glass-card">
        <div class="fecha-top" id="fecha">---</div>
        <div id="reloj">00:00</div>
        
        <form action="procesar_marcado.php" method="POST">
            <input type="text" name="cedula" class="form-control input-pro" placeholder="ID de Empleado" required autofocus autocomplete="off">
            
            <div class="btn-grid">
                <button type="submit" name="accion" value="ingreso" class="btn-action btn-in">
                    <i class="ph-bold ph-arrow-square-in"></i> Registrar Entrada
                </button>
                <button type="submit" name="accion" value="ini_refri" class="btn-action btn-lunch-start">
                    <i class="ph-bold ph-coffee"></i> Receso
                </button>
                <button type="submit" name="accion" value="fin_refri" class="btn-action btn-lunch-end">
                    <i class="ph-bold ph-play"></i> Fin Receso
                </button>
                <button type="submit" name="accion" value="salida" class="btn-action btn-out">
                    <i class="ph-bold ph-arrow-square-out"></i> Registrar Salida
                </button>
            </div>
        </form>

        <a href="reporte.php" class="report-link">Acceder al Historial Completo →</a>
    </div>

    <!-- Modal Registro -->
    <div class="modal fade" id="modalUser" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-5">
                    <h5 class="fw-bold mb-4">Nueva Ficha de Colaborador</h5>
                    <form action="index.php" method="POST">
                        <div class="mb-3">
                            <label class="small mb-1 opacity-50">Cédula / ID</label>
                            <input type="text" name="cedula_new" class="form-control p-3 border-0" required>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1 opacity-50">Nombre Completo</label>
                            <input type="text" name="nombre_new" class="form-control p-3 border-0" required>
                        </div>
                        <div class="mb-4">
                            <label class="small mb-1 opacity-50">Cargo Actual</label>
                            <input type="text" name="cargo_new" class="form-control p-3 border-0" required>
                        </div>
                        <button type="submit" name="registrar_empleado" class="btn btn-light w-100 py-3 fw-bold shadow-sm">
                            Guardar Registro
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function update() {
            const now = new Date();
            document.getElementById('reloj').innerText = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', hour12: false });
            document.getElementById('fecha').innerText = now.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long' });
        }
        setInterval(update, 1000);
        update();
    </script>
</body>
</html>