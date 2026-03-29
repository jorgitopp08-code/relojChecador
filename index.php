<?php 
include 'db.php';
session_start();

// --- LÓGICA DE REGISTRO DE USUARIO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_empleado'])) {
    $ced = trim($_POST['cedula_new']);
    $nom = trim($_POST['nombre_new']);
    $car = trim($_POST['cargo_new']);
    
    $stmt = $conn->prepare("INSERT INTO empleados (cedula, nombre, cargo) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ced, $nom, $car);
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Empleado registrado con éxito";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error: Cédula ya existe";
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
    <title>Reloj Laboral | Premium Edition</title>
    
    <!-- Recursos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        /* Botón de Agregar Usuario (Esquina Superior) */
        .btn-add-floating {
            position: fixed;
            top: 25px;
            left: 25px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 20px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1000;
        }

        .btn-add-floating:hover {
            background: white;
            color: #6366f1;
            transform: translateY(-3px);
        }

        /* Tarjeta Reloj (Glassmorphism) */
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 35px;
            padding: 50px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            text-align: center;
            color: white;
        }

        #reloj {
            font-size: 5rem;
            font-weight: 800;
            margin: 10px 0;
            letter-spacing: -2px;
            text-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .fecha-top {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.85rem;
            opacity: 0.8;
            font-weight: 600;
        }

        /* Input y Botones */
        .input-pro {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 18px !important;
            color: white !important;
            padding: 15px !important;
            text-align: center;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 25px;
        }

        .input-pro::placeholder { color: rgba(255,255,255,0.6); }

        .btn-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .btn-action {
            padding: 18px;
            border-radius: 20px;
            border: none;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .btn-action i { font-size: 1.5rem; }

        .btn-in { background: #10b981; color: white; grid-column: span 2; }
        .btn-out { background: #ef4444; color: white; grid-column: span 2; }
        .btn-lunch-start { background: #f59e0b; color: white; }
        .btn-lunch-end { background: #0ea5e9; color: white; }

        .btn-action:hover {
            transform: translateY(-5px);
            filter: brightness(1.1);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* Toasts Pro */
        .custom-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px 25px;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 2000;
            animation: slideIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes slideIn { from { transform: translateX(100%); } to { transform: translateX(0); } }

        .report-link {
            display: block;
            margin-top: 30px;
            color: white;
            text-decoration: none;
            opacity: 0.7;
            font-size: 0.9rem;
        }
        .report-link:hover { opacity: 1; text-decoration: underline; }

    </style>
</head>
<body>

    <!-- Botón de Registro -->
    <a href="#" class="btn-add-floating" data-bs-toggle="modal" data-bs-target="#modalUser">
        <i class="ph-bold ph-user-plus"></i> Registrar Nuevo
    </a>

    <!-- Notificaciones -->
    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="custom-toast">
            <i class="ph-bold ph-bell" style="color: #6366f1; font-size: 1.5rem;"></i>
            <span style="font-weight: 600; color: #1e293b;"><?= $_SESSION['mensaje'] ?></span>
        </div>
        <script>setTimeout(() => document.querySelector('.custom-toast').remove(), 3000);</script>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="glass-card">
        <div class="fecha-top" id="fecha">---</div>
        <div id="reloj">00:00</div>
        
        <form action="procesar_marcado.php" method="POST">
            <input type="text" name="cedula" class="form-control input-pro" placeholder="Ingrese Cédula" required autofocus>
            
            <div class="btn-grid">
                <button type="submit" name="accion" value="ingreso" class="btn-action btn-in">
                    <i class="ph-bold ph-sign-in"></i> Entrada Principal
                </button>
                <button type="submit" name="accion" value="ini_refri" class="btn-action btn-lunch-start">
                    <i class="ph-bold ph-coffee"></i> Almuerzo
                </button>
                <button type="submit" name="accion" value="fin_refri" class="btn-action btn-lunch-end">
                    <i class="ph-bold ph-fork-knife"></i> Retorno
                </button>
                <button type="submit" name="accion" value="salida" class="btn-action btn-out">
                    <i class="ph-bold ph-door-open"></i> Salida Turno
                </button>
            </div>
        </form>

        <a href="reporte.php" class="report-link">Ver historial de asistencias <i class="ph ph-arrow-right"></i></a>
    </div>

    <!-- Modal Pro de Registro -->
    <div class="modal fade" id="modalUser" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 25px; border: none;">
                <div class="modal-body p-5">
                    <h4 class="fw-800 mb-4 text-center" style="color: #1e293b;">Crear Nuevo Perfil</h4>
                    <form action="index.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Número de Cédula</label>
                            <input type="text" name="cedula_new" class="form-control" style="border-radius: 12px;" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre Completo</label>
                            <input type="text" name="nombre_new" class="form-control" style="border-radius: 12px;" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Cargo</label>
                            <input type="text" name="cargo_new" class="form-control" style="border-radius: 12px;" required>
                        </div>
                        <button type="submit" name="registrar_empleado" class="btn btn-primary w-100 py-3 fw-bold" style="border-radius: 15px; background: #6366f1;">
                            Finalizar Registro
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