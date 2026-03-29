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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reloj Laboral Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* [Insertar aquí el CSS previo del Reloj y los Toasts] */

        /* Estilo extra para el botón de agregar usuario */
        .btn-add-user {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 10px 15px;
            border-radius: 12px;
            backdrop-filter: blur(5px);
            transition: all 0.3s;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .btn-add-user:hover {
            background: white;
            color: #4f46e5;
            transform: translateY(-2px);
        }

        /* Estilo para el Modal Moderno */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 25px;
        }
        .modal-body { padding: 25px; }
        .form-label { font-weight: 600; color: #475569; }
    </style>
</head>
<body>

    <!-- Botón Flotante para Abrir Modal -->
    <button type="button" class="btn-add-user" data-bs-toggle="modal" data-bs-target="#modalRegistro">
        <i class="bi bi-person-plus-fill me-2"></i> Nuevo Empleado
    </button>

    <!-- Notificaciones (Toasts) -->
    <?php if (isset($_SESSION['mensaje'])): 
        $icono = ($_SESSION['tipo_mensaje'] == 'success') ? 'check-circle' : 'exclamation-octagon';
    ?>
        <div class="toast-container">
            <div class="custom-toast alert-<?= $_SESSION['tipo_mensaje']; ?>">
                <div class="toast-icon"><i class="bi bi-<?= $icono; ?>"></i></div>
                <div class="toast-content">
                    <div class="toast-title">Notificación</div>
                    <div class="toast-msg"><?= $_SESSION['mensaje']; ?></div>
                </div>
                <button type="button" class="btn-close-custom" onclick="this.parentElement.remove()">&times;</button>
                <div class="toast-progress"></div>
            </div>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); 
    endif; ?>

    <!-- TARJETA DEL RELOJ (Contenido Principal) -->
    <div class="clock-card">
        <h2>Asistencia</h2>
        <div id="fecha">...</div>
        <div id="reloj">00:00:00</div>
        
        <form action="procesar_marcado.php" method="POST">
            <div class="mb-4">
                <input type="text" name="cedula" class="form-control form-control-lg" placeholder="Cédula para marcar" required>
            </div>
            <div class="btn-grid">
                <button type="submit" name="accion" value="ingreso" class="btn-main btn-entrada">Entrada</button>
                <button type="submit" name="accion" value="ini_refri" class="btn-main btn-refri-ini">Inicio Refri</button>
                <button type="submit" name="accion" value="fin_refri" class="btn-main btn-refri-fin">Fin Refri</button>
                <button type="submit" name="accion" value="salida" class="btn-main btn-salida">Salida</button>
            </div>
        </form>
        <a href="reporte.php" class="footer-link">Ver historial de jornadas →</a>
    </div>

    <!-- MODAL DE REGISTRO DE EMPLEADOS -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-badge me-2"></i>Registrar Nuevo Empleado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Número de Cédula</label>
                            <input type="text" name="cedula_new" class="form-control" placeholder="Ej: 102030" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre_new" class="form-control" placeholder="Ej: Juan Pérez" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cargo</label>
                            <input type="text" name="cargo_new" class="form-control" placeholder="Ej: Supervisor" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="registrar_empleado" class="btn btn-primary px-4" style="border-radius: 10px; background: #4f46e5;">
                            Guardar Empleado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Lógica del reloj (la misma anterior)
        function actualizarReloj() {
            const ahora = new Date();
            document.getElementById('reloj').innerText = ahora.toLocaleTimeString('es-ES', { hour12: false });
            document.getElementById('fecha').innerText = ahora.toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        setInterval(actualizarReloj, 1000);
        actualizarReloj();

        // Auto-cerrar notificación
        setTimeout(() => {
            const toast = document.querySelector('.custom-toast');
            if (toast) toast.remove();
        }, 5000);
    </script>
</body>
</html>