<?php 
include 'db.php';
session_start();

// --- LÓGICA PARA REGISTRAR NUEVO EMPLEADO DESDE EL MODAL ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_empleado'])) {
    $ced = trim($_POST['cedula_new']);
    $nom = trim($_POST['nombre_new']);
    $car = trim($_POST['cargo_new']);

    // Verificar si ya existe
    $check = $conn->prepare("SELECT cedula FROM empleados WHERE cedula = ?");
    $check->bind_param("s", $ced);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $_SESSION['mensaje'] = "Error: La cédula $ced ya está registrada.";
        $_SESSION['tipo_mensaje'] = "danger";
    } else {
        $ins = $conn->prepare("INSERT INTO empleados (cedula, nombre, cargo) VALUES (?, ?, ?)");
        $ins->bind_param("sss", $ced, $nom, $car);
        if ($ins->execute()) {
            $_SESSION['mensaje'] = "Empleado $nom registrado con éxito.";
            $_SESSION['tipo_mensaje'] = "success";
        }
    }
    header("Location: index.php");
    exit();
}
?>

<<?php 
include 'db.php';
session_start();

// Lógica de Registro de Usuario Pro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_empleado'])) {
    $ced = trim($_POST['cedula_new']);
    $nom = trim($_POST['nombre_new']);
    $car = trim($_POST['cargo_new']);
    
    $stmt = $conn->prepare("INSERT INTO empleados (cedula, nombre, cargo) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ced, $nom, $car);
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Empleado registrado: $nom";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error: Cédula duplicada";
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
    <title>Enterprise Time Control</title>
    
    <!-- Fuentes y Recursos Pro -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-deep: #0a0a0b;
            --card-bg: #111113;
            --accent: #ffffff;
            --border: rgba(255, 255, 255, 0.08);
            --text-mute: #a1a1aa;
        }

        body {
            background-color: var(--bg-deep);
            color: white;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Fondo Decorativo de Red */
        body::before {
            content: "";
            position: absolute;
            width: 100%; height: 100%;
            background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 0);
            background-size: 40px 40px;
            z-index: -1;
        }

        /* Contenedor Principal Pro */
        .main-dashboard {
            width: 100%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 20px;
            padding: 20px;
        }

        /* Panel Izquierdo: Reloj y Estado */
        .status-panel {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .system-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .dot { width: 8px; height: 8px; background: #10b981; border-radius: 50%; animation: pulse 2s infinite; }

        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }

        #reloj-pro {
            font-family: 'Geist Mono', monospace;
            font-size: 4.5rem;
            letter-spacing: -4px;
            margin: 20px 0;
            font-weight: 700;
        }

        /* Panel Derecho: Acciones */
        .actions-panel {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px;
            position: relative;
        }

        .input-group-pro {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 15px;
            margin-bottom: 30px;
            transition: 0.3s;
        }

        .input-group-pro:focus-within {
            border-color: var(--accent);
            background: rgba(255,255,255,0.05);
        }

        .input-group-pro input {
            background: transparent;
            border: none;
            color: white;
            width: 100%;
            font-size: 1.2rem;
            outline: none;
            text-align: center;
        }

        /* Botones Estilo Grid Neumórfico Oscuro */
        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .btn-pro {
            background: #18181b;
            border: 1px solid var(--border);
            color: white;
            padding: 20px;
            border-radius: 18px;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-pro i { font-size: 24px; color: var(--text-mute); }

        .btn-pro:hover {
            background: #27272a;
            transform: translateY(-5px);
            border-color: rgba(255,255,255,0.2);
        }

        .btn-pro:hover i { color: var(--accent); }

        .btn-pro.primary { background: var(--accent); color: black; }
        .btn-pro.primary i { color: black; }

        /* Notificaciones Superiores */
        .toast-pro {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #ffffff;
            color: #000;
            padding: 15px 25px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            z-index: 10000;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    </style>
</head>
<body>

    <!-- Notificaciones Pro -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="toast-pro" id="toast">
            <i class="ph-bold ph-bell-ringing"></i>
            <?= $_SESSION['mensaje']; ?>
        </div>
        <script>setTimeout(() => document.getElementById('toast').remove(), 4000);</script>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="main-dashboard">
        <!-- Lado Izquierdo -->
        <div class="status-panel">
            <div>
                <div class="system-badge">
                    <div class="dot"></div> System Live
                </div>
                <div id="reloj-pro">00:00</div>
                <div id="fecha-pro" style="color: var(--text-mute); font-weight: 500;">---</div>
            </div>
            
            <div class="mt-4">
                <button class="btn btn-outline-light w-100 mb-2" style="border-radius: 12px; border: 1px solid var(--border);" data-bs-toggle="modal" data-bs-target="#modalAdd">
                    <i class="ph ph-user-plus me-2"></i> Nuevo Perfil
                </button>
                <a href="reporte.php" class="text-white text-decoration-none d-block text-center mt-3 opacity-50">Log de Actividad</a>
            </div>
        </div>

        <!-- Lado Derecho -->
        <div class="actions-panel">
            <h4 class="mb-4 fw-800">Terminal de Asistencia</h4>
            
            <form action="procesar_marcado.php" method="POST">
                <div class="input-group-pro">
                    <label class="d-block text-center mb-2 text-uppercase small opacity-50 fw-bold">ID de Colaborador</label>
                    <input type="text" name="cedula" placeholder="000-000-000" required autofocus>
                </div>

                <div class="action-grid">
                    <button type="submit" name="accion" value="ingreso" class="btn-pro primary">
                        <i class="ph-bold ph-sign-in"></i> Entrada
                    </button>
                    <button type="submit" name="accion" value="salida" class="btn-pro">
                        <i class="ph-bold ph-sign-out"></i> Salida
                    </button>
                    <button type="submit" name="accion" value="ini_refri" class="btn-pro">
                        <i class="ph-bold ph-coffee"></i> Inic. Receso
                    </button>
                    <button type="submit" name="accion" value="fin_refri" class="btn-pro">
                        <i class="ph-bold ph-bowl-food"></i> Fin Receso
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Registro Nivel Pro -->
    <div class="modal fade" id="modalAdd" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border-secondary text-white" style="border-radius: 20px;">
                <div class="modal-body p-4">
                    <h5 class="mb-4">Crear Credencial Nueva</h5>
                    <form action="index.php" method="POST">
                        <div class="mb-3">
                            <input type="text" name="cedula_new" class="form-control bg-transparent text-white border-secondary" placeholder="Cédula" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="nombre_new" class="form-control bg-transparent text-white border-secondary" placeholder="Nombre Completo" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="cargo_new" class="form-control bg-transparent text-white border-secondary" placeholder="Cargo / Área" required>
                        </div>
                        <button type="submit" name="registrar_empleado" class="btn btn-light w-100 py-2">Generar Registro</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            
            document.getElementById('reloj-pro').innerHTML = `${h}:${m}<span style="font-size: 1.5rem; opacity: 0.3; margin-left: 5px;">${s}</span>`;
            
            const options = { weekday: 'long', day: 'numeric', month: 'short' };
            document.getElementById('fecha-pro').innerText = now.toLocaleDateString('es-ES', options).toUpperCase();
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>